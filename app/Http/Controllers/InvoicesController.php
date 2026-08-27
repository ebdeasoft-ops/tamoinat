<?php

namespace App\Http\Controllers;

use App\Models\return_sales;
use App\Models\invoices;
use App\Models\sales;
use Illuminate\Http\Request;
use App\Models\Avt;
use App\Models\Delivery_product_to_the_customer;
use App\Models\products;
use App\Models\customers;
use App\Models\resource_purchases;
use App\Models\supllier;
use App\Models\settings;
use App\Services\Zatca\QRCodeString;
use App\Services\Zatca\Invoice\Client;
use App\Services\Zatca\Invoice\Supplier;
use App\Services\Zatca\Invoice\Delivery;
use App\Services\Zatca\Invoice\PaymentType;
use App\Services\Zatca\Invoice\PIH;
use App\Services\Zatca\Invoice\ReturnReason;
use App\Services\Zatca\Invoice\BillingReference;
use App\Services\Zatca\Invoice\AdditionalDocumentReference;
use App\Services\Zatca\Invoice\LegalMonetaryTotal;
use App\Services\Zatca\Invoice\TaxesTotal;
use App\Services\Zatca\Invoice\TaxSubtotal;
use App\Services\Zatca\Invoice\LineTaxCategory;
use App\Services\Zatca\Invoice\InvoiceLine;
use App\Services\Zatca\Invoice\AllowanceCharge;
use App\Services\Zatca\Invoice\InvoiceGenerator;
use App\Services\Zatca\QRCode;
use App\Services\Zatca\ZatcaConfig;
use Illuminate\Support\Facades\Response;
use DOMDocument;
use Hassanhelfi\NumberToArabic\NumToArabic;
use Ramsey\Uuid\Uuid;

use Mcamara\LaravelLocalization\Facades\LaravelLocalization as LaravelLocalization;

class InvoicesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

   public function save_invoice_sale(Request $request){

$cashamount=0;
$bankamount = 0;
$creaditamount = 0;
$Bank_transfer = 0;
$customerId=$request->clientnamesearch;

 $avtSaleRate = Avt::find(1);
        $tax_value_rate=$avtSaleRate->AVT;



$paymentMethod=$request->payment_type;
        if($request->payment_type=='Cash'){
        $cashamount=$request->grandTotal;
    }elseif($request->payment_type=='Shabka'){
        $bankamount=$request->grandTotal;

    }elseif($request->payment_type=='Bank_transfer'){
        $Bank_transfer=$request->grandTotal;

    }else{
        $creaditamount=$request->grandTotal;
    }

       $confirminvoice=  invoices::create(
                [
                    'customer_id' => $customerId,
                    'user_id' => Auth()->user()->id,
                    'Price' => ($request->totalSum) ,
                    'Added_Value' => ($request->totalTax ) ,
                    'status' => Auth()->user()->branchs_id == $request->branchs_id ? 0 : 1,
                    'branchs_id' => Auth()->User()->branch->id,
                    'discountOnProduct' => 0,
                    'discount' => 0,
                    'Number_of_Quantity' => 0,
                    'note' => $request->notes,
                    'save' => 1,
                    'morepayment_way' => 1,
                    'cashamount' => $cashamount,
                    'bankamount' => $bankamount,
                    'creaditamount' => $creaditamount,
                    'Bank_transfer' => $Bank_transfer,
                    'Pay' => $paymentMethod,
                    'issue_date' => substr(\Carbon\Carbon::now()->addHours(3), 0, 10),
                    'issue_time' => substr(\Carbon\Carbon::now()->addHours(3), 12),  
                    'created_at' => \Carbon\Carbon::now()->addHours(3),
                    'updated_at' => \Carbon\Carbon::now()->addHours(3),
                ]
            );

     
          
            

$total_cost=0;

            foreach($request->products as $sale) {
                $productdata = products::find($sale['product_id']);
                $total_cost+=$productdata->purchasingـprice??0*$sale['quentity'];
                $price_withoud_tax=round( ($sale['price']*100)/(($tax_value_rate*100)+100),2);
                $tax=$sale['price']-$price_withoud_tax;
                if (Auth()->user()->branchs_id == $productdata->branchs_id) {
              sales::create([
                        'user_id' => Auth()->user()->id,
                        'save' => 1,
                        'product_id' => $sale['product_id'],
                        'invoice_id' => $confirminvoice->id,
                        'branch_id' => Auth()->User()->branch->id,
                        'Discount_Value' => 0,
                        'Added_Value' => $tax,
                        'Unit_Price' =>$price_withoud_tax,
                        'price_with_tax' => $sale['price'],
                        'quantity' => $sale['quentity'],
                        'created_at' => \Carbon\Carbon::now()->addHours(3),
    ]);




                    if ($productdata->parent_inv_itemcard_id != 0) {
                        $product = products::find($productdata->parent_inv_itemcard_id);

                        $updatedproduct = products::where('id', $productdata->parent_inv_itemcard_id)->update(
                            [
                                'All_QUENTITY' => ($product->QUENTITY_all_Retails - $sale['quentity']) / $product->retail_uom_quntToParent,
                                'QUENTITY' => (int)(($product->QUENTITY_all_Retails - $sale['quentity']) / $product->retail_uom_quntToParent),
                                'QUENTITY_Retail' => ((($product->QUENTITY_all_Retails - $sale['quentity'])) % $product->retail_uom_quntToParent),
                                'QUENTITY_all_Retails' => ($product->QUENTITY_all_Retails - $sale['quentity']),
                            ]
                        );
                        products::where('id', $sale['product_id'])->update(
                            [
                                'All_QUENTITY' => ($product->QUENTITY_all_Retails - $sale['quentity']),
                            ]
                        );
                     } else {
                        $product = products::find($sale['product_id']);

                        $updatedproduct = products::where('id', $sale['product_id'])->update(
                            [
                                'All_QUENTITY' => ($product->All_QUENTITY - $sale['quentity']),
                                'QUENTITY' => (int)($product->All_QUENTITY - $sale['quentity']),
                                'QUENTITY_Retail' => ($product->All_QUENTITY - $sale['quentity']) - ((int)($product->All_QUENTITY - $sale['quentity'])),
                                'QUENTITY_all_Retails' => ($product->QUENTITY_all_Retails) -$sale['quentity']* $product->retail_uom_quntToParent,
                            ]
                        );
                        products::where('parent_inv_itemcard_id', $sale['product_id'])->update(
                            [
                                'All_QUENTITY' =>($product->QUENTITY_all_Retails) -$sale['quentity']* $product->retail_uom_quntToParent,
                            ]
                        );
                    }
                
   

}
            }
                                return $confirminvoice->id;

        }

  function sent_to_zatca_return_items($request)
    {
        // - Then Call Invoice required data from database depend on your query statment and required company id

        $setting = settings::find(1);
    
        $previous_invoice=null;
        $invoice = invoices::find($request);


        
        if($invoice->document_type == 'standard' ){
            if(is_null($invoice->customer->name) || is_null($invoice->customer->postcode) || is_null($invoice->customer->address) ||is_null( $invoice->customer->sub_city)||
            is_null( $invoice->customer->plot_identification) || is_null($invoice->customer->building_number) ||is_null( $invoice->customer->street_name)|| is_null($invoice->customer->tax_no)||strlen($invoice->customer->tax_no)!=15)
          {
            return "  \n Please enter the full national address and tax number information. Thank you يرجل ادخال بيانات العنوان الوطني و الرقم الضريبيي كاملا وشكرا";

          }
        
           }
        $invprevious =$setting ->previous_hash_invoice ;

if($invprevious==null){
    $previous_invoice='X+zrZv/IbzjZUnhsbWlsecLbwjndTpG0ZynXOif7V+k=';

}else{
    $previous_invoice= $invprevious;
}
$myuuid = Uuid::uuid4();

$created_at=\Carbon\Carbon::now()->addHours(3);
invoices::find($request)->update(
       [
        'issue_date_return' => substr($created_at, 0, 10),
       'issue_time_return' => substr($created_at, 11),
       'uuid'=>$myuuid
       ]
   );

        $invoice = invoices::find($request);
        $avtSaleRate = Avt::find(1);
        $tax_value_rate=$avtSaleRate->AVT;

        $rat_tax=$tax_value_rate*100;
        
        $itemTaxCategory = (new LineTaxCategory())
        ->setTaxCategory('S')
        ->setTaxPercentage($rat_tax)
        ->getElement();
        $total_withot_tax_sum=0;
        $tax_sum=0;
        $total_with_tax_sum=0;
        $invoiceLines=[];


    
        foreach (return_sales::where("invoice_id", $request)->where('return_quantity', '!=', 0)->where('send_zatca', 0)->get() as $item) {
                return_sales::find($item->id)->update(['send_zatca'=>1]);
                $price_each_element_withoud_tax=number_format(($item->return_Unit_Price-($item->discountvalue/$item->return_quantity)),2,'.','');
                $temp=($item->return_Unit_Price-($item->discountvalue/$item->return_quantity))*$item->return_quantity;
                $total_withot_tax=number_format($temp,2,'.','');
                $temp=(($item->return_Unit_Price-($item->discountvalue/$item->return_quantity))*$tax_value_rate)*$item->return_quantity;
                $tax=number_format($temp,2,'.','');
                $totlal_element=$total_withot_tax+$tax;

                $total_with_tax=number_format($totlal_element,2,'.','');
                $total_withot_tax_sum=$total_withot_tax_sum+$total_withot_tax*1;
                $tax_sum=$tax_sum+$tax;
                $total_with_tax_sum=$total_with_tax_sum+$total_with_tax;
                $invoiceLines[] =(new InvoiceLine())
                ->setLineID($item->product_id)
                ->setLineName($item->productData->name)
                ->setLineCurrency('SAR')
                ->setLinePrice(number_format($price_each_element_withoud_tax,2,'.',''))
                ->setLineQuantity($item->return_quantity)
                ->setLineSubTotal($total_withot_tax)
                ->setLineTaxTotal($tax)
                ->setLineNetTotal($total_with_tax)
                ->setLineTaxCategories($itemTaxCategory)
                ->setLineDiscountReason( 'Discount on product')
                ->setLineDiscountAmount(0)
                ->getElement();
        }



        $total_withot_tax_sum=number_format($total_withot_tax_sum,2,'.','');
        $tax_sum=number_format($tax_sum,2,'.','');;
        $total_with_tax_sum=number_format($total_with_tax_sum,2,'.','');




  
   
// clients data


$client = (new Client())
->setVatNumber($invoice->customer->tax_no)
->setStreetName($invoice->customer->street_name)
->setBuildingNumber($invoice->customer->building_number)
->setPlotIdentification( $invoice->customer->plot_identification)
->setSubDivisionName($invoice->customer->sub_city)
->setCityName($invoice->customer->address)
->setPostalNumber($invoice->customer->postcode)
->setCountryName('SA')
->setClientName($invoice->customer->name);


        $supplier = (new Supplier())
        ->setCrn( $setting->crn)
        ->setStreetName( $setting->street_name)
        ->setBuildingNumber($setting->building_number)
        ->setPlotIdentification($setting->plot_identification)
        ->setSubDivisionName($setting->region)
        ->setCityName($setting->city)
        ->setPostalNumber($setting->postal_number)
        ->setCountryName('SA')
        ->setVatNumber($setting->trn)
        ->setVatName( $setting->name);

        $delivery = (new Delivery())
        ->setDeliveryDateTime( $invoice->issue_date);
    
        $paymentType = (new PaymentType())
        ->setPaymentType('10');
    
        $returnReason = (new ReturnReason())
        ->setReturnReason('Sales returns');
    
        $previous_hash = (new PIH())
        ->setPIH($previous_invoice);  // note this value it from step 3 , 4
         $billingReference = (new BillingReference())
        ->setBillingReference($request); // note this used when type credit or debit this value of parent invoice id
    
        $additionalDocumentReference = (new AdditionalDocumentReference())
        ->setInvoiceID(  $setting->invoices_count+1); // note this value it from step 1
    
        $legalMonetaryTotal = (new LegalMonetaryTotal())
        ->setTotalCurrency('SAR')
        ->setLineExtensionAmount(  $total_withot_tax_sum)
        ->setTaxExclusiveAmount(  $total_withot_tax_sum)
        ->setTaxInclusiveAmount($total_with_tax_sum)
        ->setAllowanceTotalAmount(0)
        ->setPrepaidAmount(0)
        ->setPayableAmount($total_with_tax_sum);
       
        $taxesTotal = (new TaxesTotal())
        ->setTaxCurrencyCode('SAR')
        ->setTaxTotal($tax_sum);
      
        $taxSubtotal = (new TaxSubtotal())
        ->setTaxCurrencyCode('SAR')
        ->setTaxableAmount(  $total_withot_tax_sum)
        ->setTaxAmount($tax_sum)
        ->setTaxCategory('S')
        ->setTaxPercentage($rat_tax)
        ->getElement();
    
    
        $allowanceCharge = (new AllowanceCharge())
        ->setAllowanceChargeCurrency('SAR')
        ->setAllowanceChargeIndex('1')
        ->setAllowanceChargeAmount(0)
        ->setAllowanceChargeTaxCategory('S')
        ->setAllowanceChargeTaxPercentage($rat_tax)
        ->getElement();
        
        
             if($invoice->customer_id == 1)
        {
            
        $response = (new InvoiceGenerator())
        ->setZatcaEnv($setting->is_production?'core':'simulation')
        ->setZatcaLang('en')
        ->setInvoiceNumber($invoice->NOTICE_Number)
        ->setInvoiceUuid($myuuid) // this value from step 6
        ->setInvoiceIssueDate($invoice->issue_date_return)
        ->setInvoiceIssueTime($invoice->issue_time_return)
        ->setInvoiceType(($invoice->document_type == 'simplified') ? '0200000' : '0100000',"381")
        ->setInvoiceCurrencyCode('SAR')
        ->setInvoiceTaxCurrencyCode('SAR')
        ->setInvoiceBillingReference($billingReference) // use this when document type is credit or debit
        ->setInvoiceAdditionalDocumentReference($additionalDocumentReference)
        ->setInvoicePIH($previous_hash)
        ->setInvoiceSupplier($supplier)
        ->setInvoiceDelivery($delivery)
        ->setInvoicePaymentType($paymentType)
        ->setInvoiceReturnReason($returnReason) //use this when document type is credit or debit
        ->setInvoiceLegalMonetaryTotal($legalMonetaryTotal)
        ->setInvoiceTaxesTotal($taxesTotal)
        ->setInvoiceTaxSubTotal($taxSubtotal)
        ->setInvoiceAllowanceCharges($allowanceCharge)
        ->setInvoiceLines(...$invoiceLines)
        ->setCertificateEncoded($setting->production_certificate)
        ->setPrivateKeyEncoded( $setting->private_key)
        ->setCertificateSecret($setting->production_secret)
        ->sendDocument(true); // when you use production certifiacte for (simulation , core) dont forget set sendDocument(true)


            
        }else{
        $response = (new InvoiceGenerator())
        ->setZatcaEnv($setting->is_production?'core':'simulation')
        ->setZatcaLang('en')
        ->setInvoiceNumber($invoice->NOTICE_Number)
        ->setInvoiceUuid($myuuid) // this value from step 6
        ->setInvoiceIssueDate($invoice->issue_date_return)
        ->setInvoiceIssueTime($invoice->issue_time_return)
        ->setInvoiceType(($invoice->document_type == 'simplified') ? '0200000' : '0100000',"381")
        ->setInvoiceCurrencyCode('SAR')
        ->setInvoiceTaxCurrencyCode('SAR')
        ->setInvoiceBillingReference($billingReference) // use this when document type is credit or debit
        ->setInvoiceAdditionalDocumentReference($additionalDocumentReference)
        ->setInvoicePIH($previous_hash)
        ->setInvoiceSupplier($supplier)
        ->setInvoiceClient($client)
        ->setInvoiceDelivery($delivery)
        ->setInvoicePaymentType($paymentType)
        ->setInvoiceReturnReason($returnReason) //use this when document type is credit or debit
        ->setInvoiceLegalMonetaryTotal($legalMonetaryTotal)
        ->setInvoiceTaxesTotal($taxesTotal)
        ->setInvoiceTaxSubTotal($taxSubtotal)
        ->setInvoiceAllowanceCharges($allowanceCharge)
        ->setInvoiceLines(...$invoiceLines)
        ->setCertificateEncoded($setting->production_certificate)
        ->setPrivateKeyEncoded( $setting->private_key)
        ->setCertificateSecret($setting->production_secret)
        ->sendDocument(true); // when you use production certifiacte for (simulation , core) dont forget set sendDocument(true)

}
        if ($response['success']) {
  settings::find(1)->update(['previous_hash_invoice'=>$response['hash'],
                                       'invoices_count'=> $setting->invoices_count+1
                                      ]);
            invoices::find($request)->update(
                [
                    'qr_zatca_return' => \Carbon\Carbon::now(),
                    'sent_to_zatca_status_return' => "PASS",
                    'xmltags_return' =>$response['xml'],
                    'xml_return'=>$invoice->document_type == 'simplified'?NULL:$response['response']->clearedInvoice

                ]
            );
            return 1;

        } else {

            return '|||'.$response['response']->reportingStatus.'   ||| ERROR MESSAGE    :-   '.$response['response']->validationResults->errorMessages[0]->message;
        }
    }
  
    function sent_to_zatca($request)
    {
        // - Then Call Invoice required data from database depend on your query statment and required company id

        $setting = settings::find(1);
       
        ### Zatca Integration have two steps second : Send Invoices to zatca Step example :
        // - Add below line to start of controller file which used
        $previous_invoice=null;
        $invoice = invoices::find($request);
        $invprevious =$setting ->previous_hash_invoice ;

if($invprevious==null){
    $previous_invoice='X+zrZv/IbzjZUnhsbWlsecLbwjndTpG0ZynXOif7V+k=';

}else{
    $previous_invoice= $invprevious;
}
        $myuuid = Uuid::uuid4();

        invoices::find($request)->update(
            [
                'invoice_counter' =>  $setting->invoices_count+1,
                'invoice_number' => $invoice->id,
                'invoiceUUid' =>$myuuid ,
                'document_type' => $invoice->customer_id == 1? 'simplified' : 'standard',
                'invoice_type' => "388", //  "388" NORMAL INVOICE , "383"  DEBIT_NOTE , "381" CREDIT_NOTE
                'issue_date' => substr($invoice->created_at, 0, 10),
                'issue_time' => substr($invoice->created_at, 11),
            ]
        );

        $invoice = invoices::find($request);




        
        if($invoice->document_type == 'standard' ){
            if(is_null($invoice->customer->name) || is_null($invoice->customer->postcode) || is_null($invoice->customer->address) ||is_null( $invoice->customer->sub_city)||
            is_null( $invoice->customer->plot_identification) || is_null($invoice->customer->building_number) ||is_null( $invoice->customer->street_name)|| is_null($invoice->customer->tax_no)||strlen($invoice->customer->tax_no)!=15)
          {
            return "  \n Please enter the full national address and tax number information. Thank you يرجل ادخال بيانات العنوان الوطني و الرقم الضريبيي كاملا وشكرا";

          }
        
           }
        $avtSaleRate = Avt::find(1);
        $tax_value_rate=$avtSaleRate->AVT;

        $rat_tax=$tax_value_rate*100;
        
        $itemTaxCategory = (new LineTaxCategory())
        ->setTaxCategory('S')
        ->setTaxPercentage($rat_tax)
        ->getElement();
        $total_withot_tax_sum=0;
        $tax_sum=0;
        $total_with_tax_sum=0;
        $invoiceLines=[];


    
        foreach (sales::where("invoice_id", $request)->where('quantity', '!=', 0)->get() as $item) {
                $price_each_element_withoud_tax=number_format(($item->Unit_Price-($item->Discount_Value/$item->quantity)),2,'.','');
                $temp=($item->Unit_Price-($item->Discount_Value/$item->quantity))*$item->quantity;
                $total_withot_tax=number_format($temp,2,'.','');
                $temp=(($item->Unit_Price-($item->Discount_Value/$item->quantity))*$tax_value_rate)*$item->quantity;
                $tax=number_format($temp,2,'.','');
                $totlal_element=$total_withot_tax+$tax;




                $total_with_tax=number_format($totlal_element,2,'.','');
                $total_withot_tax_sum=$total_withot_tax_sum+$total_withot_tax*1;
                $tax_sum=$tax_sum+$tax;
                $total_with_tax_sum=$total_with_tax_sum+$total_with_tax;
                $invoiceLines[] =(new InvoiceLine())
                ->setLineID($item->product_id)
                ->setLineName($item->productData->product_name)
                ->setLineCurrency('SAR')
                ->setLinePrice(number_format($price_each_element_withoud_tax,2,'.',''))
                ->setLineQuantity($item->quantity)
                ->setLineSubTotal($total_withot_tax)
                ->setLineTaxTotal($tax)
                ->setLineNetTotal($total_with_tax)
                ->setLineTaxCategories($itemTaxCategory)
                ->setLineDiscountReason( 'Discount on product')
                ->setLineDiscountAmount(0)
                ->getElement();
        }
//  return $invoiceLines;
        $total_withot_tax_sum=number_format($total_withot_tax_sum,2,'.','');
        $tax_sum=number_format($tax_sum,2,'.','');;
        $total_with_tax_sum=number_format($total_with_tax_sum,2,'.','');



        // - If Invoice type is standard invoice (B2B) you must provide full buyer information as below :

  
   
// clients data
        $client = (new Client())
        ->setVatNumber($invoice->customer->tax_no)
        ->setStreetName($invoice->customer->street_name)
        ->setBuildingNumber($invoice->customer->building_number)
        ->setPlotIdentification( $invoice->customer->plot_identification)
        ->setSubDivisionName($invoice->customer->sub_city)
        ->setCityName($invoice->customer->address)
        ->setPostalNumber($invoice->customer->postcode)
        ->setCountryName('SA')
        ->setClientName($invoice->customer->name);
    

    //  return $client->getElement();
        $supplier = (new Supplier())
        ->setCrn( $setting->crn)
        ->setStreetName( $setting->street_name)
        ->setBuildingNumber($setting->building_number)
        ->setPlotIdentification($setting->plot_identification)
        ->setSubDivisionName($setting->region)
        ->setCityName($setting->city)
        ->setPostalNumber($setting->postal_number)
        ->setCountryName('SA')
        ->setVatNumber($setting->trn)
        ->setVatName( $setting->name);
        // return $supplier->getElement();

        $delivery = (new Delivery())
        ->setDeliveryDateTime( $invoice->issue_date);
    
        $paymentType = (new PaymentType())
        ->setPaymentType('10');
    
        $returnReason = (new ReturnReason())
        ->setReturnReason('SET_RETURN_REASON');
    
        $previous_hash = (new PIH())
        ->setPIH($previous_invoice);  // note this value it from step 3 , 4
        // $billingReference = (new BillingReference())
        // ->setBillingReference('23'); // note this used when type credit or debit this value of parent invoice id
    
        $additionalDocumentReference = (new AdditionalDocumentReference())
            ->setInvoiceID(  $setting->invoices_count+1); // note this value it from step 1
        // ->setInvoiceID( $request); // note this value it from step 1

        $legalMonetaryTotal = (new LegalMonetaryTotal())
        ->setTotalCurrency('SAR')
        ->setLineExtensionAmount(  $total_withot_tax_sum)
        ->setTaxExclusiveAmount(  $total_withot_tax_sum)
        ->setTaxInclusiveAmount($total_with_tax_sum)
        ->setAllowanceTotalAmount(0)
        ->setPrepaidAmount(0)
        ->setPayableAmount($total_with_tax_sum);
       
        $taxesTotal = (new TaxesTotal())
        ->setTaxCurrencyCode('SAR')
        ->setTaxTotal($tax_sum);
      
        $taxSubtotal = (new TaxSubtotal())
        ->setTaxCurrencyCode('SAR')
        ->setTaxableAmount(  $total_withot_tax_sum)
        ->setTaxAmount($tax_sum)
        ->setTaxCategory('S')
        ->setTaxPercentage($rat_tax)
        ->getElement();
    
    
        $allowanceCharge = (new AllowanceCharge())
        ->setAllowanceChargeCurrency('SAR')
        ->setAllowanceChargeIndex('1')
        ->setAllowanceChargeAmount(0)
        ->setAllowanceChargeTaxCategory('S')
        ->setAllowanceChargeTaxPercentage($rat_tax)
        ->getElement();
        
             if($invoice->customer_id == 1)
        {
            
        $response = (new InvoiceGenerator())
        ->setZatcaEnv($setting->is_production?'core':'simulation')
        ->setZatcaLang('en')
        ->setInvoiceNumber($request)
        ->setInvoiceUuid($invoice->invoiceUUid) // this value from step 6
        ->setInvoiceIssueDate($invoice->issue_date)
        ->setInvoiceIssueTime($invoice->issue_time)
        ->setInvoiceType(($invoice->document_type == 'simplified') ? '0200000' : '0100000',$invoice->invoice_type)
        ->setInvoiceCurrencyCode('SAR')
        ->setInvoiceTaxCurrencyCode('SAR')
        //->setInvoiceBillingReference($billingReference)  use this when document type is credit or debit
        ->setInvoiceAdditionalDocumentReference($additionalDocumentReference)
        ->setInvoicePIH($previous_hash)
        ->setInvoiceSupplier($supplier)
        ->setInvoiceDelivery($delivery)
        ->setInvoicePaymentType($paymentType)
        //->setInvoiceReturnReason($returnReason) use this when document type is credit or debit
        ->setInvoiceLegalMonetaryTotal($legalMonetaryTotal)
        ->setInvoiceTaxesTotal($taxesTotal)
        ->setInvoiceTaxSubTotal($taxSubtotal)
        ->setInvoiceAllowanceCharges($allowanceCharge)
        ->setInvoiceLines(...$invoiceLines)
        ->setCertificateEncoded($setting->production_certificate)
        ->setPrivateKeyEncoded( $setting->private_key)
        ->setCertificateSecret($setting->production_secret)
        ->sendDocument(true); // when you use production certifiacte for (simulation , core) dont forget set sendDocument(true)
        
        }
        else{
        $response = (new InvoiceGenerator())
        ->setZatcaEnv($setting->is_production?'core':'simulation')
        ->setZatcaLang('en')
        ->setInvoiceNumber($request)
        ->setInvoiceUuid($invoice->invoiceUUid) // this value from step 6
        ->setInvoiceIssueDate($invoice->issue_date)
        ->setInvoiceIssueTime($invoice->issue_time)
        ->setInvoiceType(($invoice->document_type == 'simplified') ? '0200000' : '0100000',$invoice->invoice_type)
        ->setInvoiceCurrencyCode('SAR')
        ->setInvoiceTaxCurrencyCode('SAR')
        //->setInvoiceBillingReference($billingReference)  use this when document type is credit or debit
        ->setInvoiceAdditionalDocumentReference($additionalDocumentReference)
        ->setInvoicePIH($previous_hash)
        ->setInvoiceSupplier($supplier)
        ->setInvoiceClient($client)
        ->setInvoiceDelivery($delivery)
        ->setInvoicePaymentType($paymentType)
        //->setInvoiceReturnReason($returnReason) use this when document type is credit or debit
        ->setInvoiceLegalMonetaryTotal($legalMonetaryTotal)
        ->setInvoiceTaxesTotal($taxesTotal)
        ->setInvoiceTaxSubTotal($taxSubtotal)
        ->setInvoiceAllowanceCharges($allowanceCharge)
        ->setInvoiceLines(...$invoiceLines)
        ->setCertificateEncoded($setting->production_certificate)
        ->setPrivateKeyEncoded( $setting->private_key)
        ->setCertificateSecret($setting->production_secret)
        ->sendDocument(true); // when you use production certifiacte for (simulation , core) dont forget set sendDocument(true)
        }
        //   return $response;
            
            
        if ($response['success']) {
             settings::find(1)->update(['previous_hash_invoice'=>$response['hash'],
                                       'invoices_count'=> $setting->invoices_count+1
                                      ]);
            invoices::find($request)->update(
                [
                    'signing_time' => \Carbon\Carbon::now(),
                    'hash' => $response['hash'],
                    'xml' => $response['xml'],
                    'sent_to_zatca_status' => "PASS",
                    'sent_to_zatca' => 1,
                    'clearedInvoice'=>$invoice->document_type == 'simplified'?NULL:$response['response']->clearedInvoice

                ]
            );
        
            return 1;
        }  else {

           return '|||'.$response['response']->reportingStatus.'   ||| ERROR MESSAGE    :-   '.$response['response']->validationResults->errorMessages[0]->message;
        }
    }


  




    function sendzatca_fromsale($request)
    {
        // - Then Call Invoice required data from database depend on your query statment and required company id

        $setting = settings::find(1);
       
        ### Zatca Integration have two steps second : Send Invoices to zatca Step example :
        // - Add below line to start of controller file which used
        $previous_invoice=null;
        $invoice = invoices::find($request);
        $invprevious =$setting ->previous_hash_invoice ;

if($invprevious==null){
    $previous_invoice='X+zrZv/IbzjZUnhsbWlsecLbwjndTpG0ZynXOif7V+k=';

}
else{
    $previous_invoice= $invprevious;
}

        $myuuid = Uuid::uuid4();

        invoices::find($request)->update(
            [
                'invoice_counter' =>  $setting->invoices_count+1,
                'invoice_number' => $invoice->id,
                'invoiceUUid' => $myuuid,
                'document_type' => $invoice->customer_id == 1  ? 'simplified' : 'standard',
                'invoice_type' => "388", //  "388" NORMAL INVOICE , "383"  DEBIT_NOTE , "381" CREDIT_NOTE
                'issue_date' => substr($invoice->created_at, 0, 10),
                'issue_time' => substr($invoice->created_at, 11),
            ]
        );


        $invoice = invoices::find($request);

        if($invoice->document_type == 'standard' ){
            if(is_null($invoice->customer->name) || is_null($invoice->customer->postcode) || is_null($invoice->customer->address) ||is_null( $invoice->customer->sub_city)||
            is_null( $invoice->customer->plot_identification) || is_null($invoice->customer->building_number) ||is_null( $invoice->customer->street_name)|| is_null($invoice->customer->tax_no)||strlen($invoice->customer->tax_no)!=15)
          {
            return "  \n Please enter the full national address and tax number information. Thank you يرجل ادخال بيانات العنوان الوطني و الرقم الضريبيي كاملا وشكرا";

          }
        
           }
        $avtSaleRate = Avt::find(1);
        $tax_value_rate=$avtSaleRate->AVT;

        $rat_tax=$tax_value_rate*100;
        
        $itemTaxCategory = (new LineTaxCategory())
        ->setTaxCategory('S')
        ->setTaxPercentage($rat_tax)
        ->getElement();
        $total_withot_tax_sum=0;
        $tax_sum=0;
        $total_with_tax_sum=0;
        $invoiceLines=[];


    
        foreach (sales::where("invoice_id", $request)->where('quantity', '!=', 0)->get() as $item) {
                $price_each_element_withoud_tax=number_format(($item->Unit_Price-($item->Discount_Value/$item->quantity)),2,'.','');
                $temp=($item->Unit_Price-($item->Discount_Value/$item->quantity))*$item->quantity;
                $total_withot_tax=number_format($temp,2,'.','');
                $temp=(($item->Unit_Price-($item->Discount_Value/$item->quantity))*$tax_value_rate)*$item->quantity;
                $tax=number_format($temp,2,'.','');
                $totlal_element=$total_withot_tax+$tax;

              
        


                $total_with_tax=number_format($totlal_element,2,'.','');
                $total_withot_tax_sum=$total_withot_tax_sum+$total_withot_tax*1;
                $tax_sum=$tax_sum+$tax;
                $total_with_tax_sum=$total_with_tax_sum+$total_with_tax;
                $invoiceLines[] =(new InvoiceLine())
                ->setLineID($item->product_id)
                ->setLineName($item->productData->name)
                ->setLineCurrency('SAR')
                ->setLinePrice(number_format($price_each_element_withoud_tax,2,'.',''))
                ->setLineQuantity($item->quantity)
                ->setLineSubTotal($total_withot_tax)
                ->setLineTaxTotal($tax)
                ->setLineNetTotal($total_with_tax)
                ->setLineTaxCategories($itemTaxCategory)
                ->setLineDiscountReason( 'Discount on product')
                ->setLineDiscountAmount(0)
                ->getElement();
        }
//  return $invoiceLines;
        $total_withot_tax_sum=number_format($total_withot_tax_sum,2,'.','');
        $tax_sum=number_format($tax_sum,2,'.','');;
        $total_with_tax_sum=number_format($total_with_tax_sum,2,'.','');



        // - If Invoice type is standard invoice (B2B) you must provide full buyer information as below :

  
   
// clients data
$client = (new Client())
->setVatNumber($invoice->customer->tax_no)
->setStreetName($invoice->customer->street_name)
->setBuildingNumber($invoice->customer->building_number)
->setPlotIdentification( $invoice->customer->plot_identification)
->setSubDivisionName($invoice->customer->sub_city)
->setCityName($invoice->customer->address)
->setPostalNumber($invoice->customer->postcode)
->setCountryName('SA')
->setClientName($invoice->customer->name);


    //  return $client->getElement();
        $supplier = (new Supplier())
        ->setCrn( $setting->crn)
        ->setStreetName( $setting->street_name)
        ->setBuildingNumber($setting->building_number)
        ->setPlotIdentification($setting->plot_identification)
        ->setSubDivisionName($setting->region)
        ->setCityName($setting->city)
        ->setPostalNumber($setting->postal_number)
        ->setCountryName('SA')
        ->setVatNumber($setting->trn)
        ->setVatName( $setting->name);
        // return $supplier->getElement();

        $delivery = (new Delivery())
        ->setDeliveryDateTime( $invoice->issue_date);
    
        $paymentType = (new PaymentType())
        ->setPaymentType('10');
    
        $returnReason = (new ReturnReason())
        ->setReturnReason('SET_RETURN_REASON');
    
        $previous_hash = (new PIH())
        ->setPIH($previous_invoice);  // note this value it from step 3 , 4
        // $billingReference = (new BillingReference())
        // ->setBillingReference('23'); // note this used when type credit or debit this value of parent invoice id
    
        $additionalDocumentReference = (new AdditionalDocumentReference())
        ->setInvoiceID(  $setting->invoices_count+1); // note this value it from step 1
    
        $legalMonetaryTotal = (new LegalMonetaryTotal())
        ->setTotalCurrency('SAR')
        ->setLineExtensionAmount(  $total_withot_tax_sum)
        ->setTaxExclusiveAmount(  $total_withot_tax_sum)
        ->setTaxInclusiveAmount($total_with_tax_sum)
        ->setAllowanceTotalAmount(0)
        ->setPrepaidAmount(0)
        ->setPayableAmount($total_with_tax_sum);
       
        $taxesTotal = (new TaxesTotal())
        ->setTaxCurrencyCode('SAR')
        ->setTaxTotal($tax_sum);
      
        $taxSubtotal = (new TaxSubtotal())
        ->setTaxCurrencyCode('SAR')
        ->setTaxableAmount(  $total_withot_tax_sum)
        ->setTaxAmount($tax_sum)
        ->setTaxCategory('S')
        ->setTaxPercentage($rat_tax)
        ->getElement();
    
    
        $allowanceCharge = (new AllowanceCharge())
        ->setAllowanceChargeCurrency('SAR')
        ->setAllowanceChargeIndex('1')
        ->setAllowanceChargeAmount(0)
        ->setAllowanceChargeTaxCategory('S')
        ->setAllowanceChargeTaxPercentage($rat_tax)
        ->getElement();
        
        
        
             if($invoice->customer_id == 1)
        {
             $response = (new InvoiceGenerator())
        ->setZatcaEnv($setting->is_production?'core':'simulation')
        ->setZatcaLang('en')
        ->setInvoiceNumber($request)
        ->setInvoiceUuid($invoice->invoiceUUid) // this value from step 6
        ->setInvoiceIssueDate($invoice->issue_date)
        ->setInvoiceIssueTime($invoice->issue_time)
        ->setInvoiceType(($invoice->document_type == 'simplified') ? '0200000' : '0100000',$invoice->invoice_type)
        ->setInvoiceCurrencyCode('SAR')
        ->setInvoiceTaxCurrencyCode('SAR')
        //->setInvoiceBillingReference($billingReference)  use this when document type is credit or debit
        ->setInvoiceAdditionalDocumentReference($additionalDocumentReference)
        ->setInvoicePIH($previous_hash)
        ->setInvoiceSupplier($supplier)
        ->setInvoiceDelivery($delivery)
        ->setInvoicePaymentType($paymentType)
        //->setInvoiceReturnReason($returnReason) use this when document type is credit or debit
        ->setInvoiceLegalMonetaryTotal($legalMonetaryTotal)
        ->setInvoiceTaxesTotal($taxesTotal)
        ->setInvoiceTaxSubTotal($taxSubtotal)
        ->setInvoiceAllowanceCharges($allowanceCharge)
        ->setInvoiceLines(...$invoiceLines)
        ->setCertificateEncoded($setting->production_certificate)
        ->setPrivateKeyEncoded( $setting->private_key)
        ->setCertificateSecret($setting->production_secret)
        ->sendDocument(true); // when you use production certifiacte for (simulation , core) dont forget set sendDocument(true)
            
        }else{
            
            $response = (new InvoiceGenerator())
        ->setZatcaEnv($setting->is_production?'core':'simulation')
        ->setZatcaLang('en')
        ->setInvoiceNumber($request)
        ->setInvoiceUuid($invoice->invoiceUUid) // this value from step 6
        ->setInvoiceIssueDate($invoice->issue_date)
        ->setInvoiceIssueTime($invoice->issue_time)
        ->setInvoiceType(($invoice->document_type == 'simplified') ? '0200000' : '0100000',$invoice->invoice_type)
        ->setInvoiceCurrencyCode('SAR')
        ->setInvoiceTaxCurrencyCode('SAR')
        //->setInvoiceBillingReference($billingReference)  use this when document type is credit or debit
        ->setInvoiceAdditionalDocumentReference($additionalDocumentReference)
        ->setInvoicePIH($previous_hash)
        ->setInvoiceSupplier($supplier)
        ->setInvoiceClient($client)
        ->setInvoiceDelivery($delivery)
        ->setInvoicePaymentType($paymentType)
        //->setInvoiceReturnReason($returnReason) use this when document type is credit or debit
        ->setInvoiceLegalMonetaryTotal($legalMonetaryTotal)
        ->setInvoiceTaxesTotal($taxesTotal)
        ->setInvoiceTaxSubTotal($taxSubtotal)
        ->setInvoiceAllowanceCharges($allowanceCharge)
        ->setInvoiceLines(...$invoiceLines)
        ->setCertificateEncoded($setting->production_certificate)
        ->setPrivateKeyEncoded( $setting->private_key)
        ->setCertificateSecret($setting->production_secret)
        ->sendDocument(true); // when you use production certifiacte for (simulation , core) dont forget set sendDocument(true) 
            
            
        }
       
    //    return $response;
            if ($response['success']) {
  settings::find(1)->update(['previous_hash_invoice'=>$response['hash'],
                                       'invoices_count'=> $setting->invoices_count+1
                                      ]);
                invoices::find($request)->update(
                    [
                        'signing_time' => \Carbon\Carbon::now(),
                        'hash' => $response['hash'],
                        'xml' => $response['xml'],
                        'sent_to_zatca_status' => "PASS",
                        'sent_to_zatca' => 1,
                        'clearedInvoice'=>$invoice->document_type == 'simplified'?NULL:$response['response']->clearedInvoice
    
                    ]
            );
        
            return 1;
        }  else {

            // return $response;
           return '|||'.$response['response']->reportingStatus.'   ||| ERROR MESSAGE    :-   '.$response['response']->validationResults->errorMessages[0]->message;
        }
    }


    function dwonloadxml($id)
    {
        // return $id;
        $invoice = invoices::find($id);
        $xml = new DOMDocument;
        $xml->loadXML(base64_decode( $invoice->clearedInvoice??$invoice->xml,true));
        $xml->formatOutput = true;
        $namefile = "invoice_" . $invoice->id . '_' . date("Y_m_d") . 'T' . date("h_i") . ".xml";
        $xml->save('result.xml');
        $xml = file_get_contents(public_path('result.xml'));
        $filepath = public_path('result.xml');
        $headers = [
            'Content-Type' => 'application/xml',
        ];
        $respons = Response::download($filepath, $namefile, $headers);
        // unlink($namefile);

        return  $respons;
    }
 
    


    public function index()
    {
        //
        $data = [];
        app()->setLocale(LaravelLocalization::getCurrentLocale());

        return view('products.salesreturned', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function getproductbyCode($request)
    {
        $updateProduct = products::where('barcode', $request)->first();
        return $updateProduct;
    }
    public function getproductbyCodeandbranch($branch, $request)
    {
        $updateProduct = products::where('barcode', $request)->where('branchs_id', $branch)->first();
        $updatedproduct[] = ['nameum' => $updateProduct->Parent_uom->name];
        return $updateProduct;
    }
    public function updatecustomerDataRecipt(Request $request)
    {

        $invoices = invoices::where('id', $request->id)->first();


        if ($invoices->Pay == 'Credit') {
            $customerdata = customers::find($invoices->customer_id);
            $avt = Avt::find(1);
            $saleavt = $avt->AVT;
            $updateCustomer = customers::where('id', $invoices->customer_id)->update(
                [
                    'Balance' => $customerdata->Balance - (($invoices->Price - $invoices->discount) + (($invoices->Price - $invoices->discount) * $saleavt))
                ]
            );
            $customerdatanew = customers::find($request->customerId);

            $updateCustomer = customers::where('id', $request->customerId)->update(
                [
                    'Balance' => $customerdatanew->Balance + (($invoices->Price - $invoices->discount) + (($invoices->Price - $invoices->discount) * $saleavt))
                ]
            );
        }
        $InvoiceData = invoices::where('id', $request->id)->update(
            ['customer_id' => $request->customerId]
        );
        $data = invoices::where('branchs_id', Auth()->user()->branchs_id)->where('save', 1)->where('status', 1)->orderby('id', 'desc')->paginate(20);
        return view('ajax_Recent_Invoices', compact('data'));
    }
    public function updatecustomerDataInvoice(Request $request)
    {

        $invoices = invoices::where('id', $request->id)->first();


        if ($invoices->Pay == 'Credit') {
            $customerdata = customers::find($invoices->customer_id);
            $avt = Avt::find(1);
            $saleavt = $avt->AVT;
            $updateCustomer = customers::where('id', $invoices->customer_id)->update(
                [
                    'Balance' => $customerdata->Balance - (($invoices->Price - $invoices->discount) + (($invoices->Price - $invoices->discount) * $saleavt))
                ]
            );
            $customerdatanew = customers::find($request->customerId);

            $updateCustomer = customers::where('id', $request->customerId)->update(
                [
                    'Balance' => $customerdatanew->Balance + (($invoices->Price - $invoices->discount) + (($invoices->Price - $invoices->discount) * $saleavt))
                ]
            );
        }
        $InvoiceData = invoices::where('id', $request->id)->update(
            ['customer_id' => $request->customerId]
        );
        $data = invoices::where('branchs_id', Auth()->user()->branchs_id)->where('save', 1)->where('status', 0)->orderby('id', 'desc')->paginate(20);
        return view('ajax_Recent_Invoices', compact('data'));
    }
    public function updatecustomerDataInvoicepurchases(Request $request)
    {

        $invoices = resource_purchases::where('orderId', $request->id)->first();


        if ($invoices->Pay_Method_Name == 'Credit') {
            $customerdata = supllier::find($invoices->suplier_id);
           
            $updateCustomer = supllier::where('id', $invoices->suplier_id)->update(
                [
                    'In_debt' => $customerdata->In_debt - (($invoices->In_debt - $invoices->discount))
                ]
            );
            $customerdatanew = supllier::find($request->customerId);

            $updateCustomer = supllier::where('id', $request->customerId)->update(
                [
                    'In_debt' => $customerdatanew->In_debt + (($invoices->In_debt - $invoices->discount))
                ]
            );
        }
        $InvoiceData = resource_purchases::where('orderId', $request->id)->update(
            ['suplier_id' => $request->customerId]
        );
        $data = resource_purchases::where('branchs_id', Auth()->user()->branchs_id)->where('save', 1)->orderby('id', 'desc')->paginate(20);
        return view('ajax_Recent_Invoices_purchases', compact('data'));
    }

    public function getByCode(Request $request)
    {
        //
        $checkbarode = $request->Code;
        $scalecode = substr($checkbarode, 0, 2);
        $quantity=$request->quantity;
        if ($scalecode == "99") {
            $productcode = substr($checkbarode, 2, 5);;
            $weight = substr($checkbarode, 7);
            $kg = substr($weight, 0, 2);
            $replacezero=substr($kg, 0, 1);
            if( $replacezero==0){
                $kg = substr($weight, 1, 1);
 
            }
            $gram = substr($weight, 2, 3);
            $checkbarode = $scalecode;
            $finalwight= $kg.'.'.$gram;
            $quantity=$finalwight;
            $updateProduct = products::where('branchs_id', Auth()->user()->branchs_id)->where('barcode', $productcode)->first();

        }

else{
    $updateProduct = products::where('barcode', $request->Code)->where('branchs_id', Auth()->user()->branchs_id)->first();

}
        //return $updateProduct;
        $avtSaleRate = Avt::find(1);

  
        $invoiceNumber = $request->invoice_number;
        if ($request->invoice_number == null) {
            // return ($request->pay);

            $Invoice = invoices::create(
                [
                    'customer_id' => $request->clientnamesearch ?? 1,
                    'user_id' => Auth()->user()->id,
                    'Price' => ($updateProduct->price) *$quantity,
                    'Added_Value' => (round(($updateProduct->price),2) * $avtSaleRate->AVT) * $quantity,
                    'Pay' => $request->pay,
                    'status' => Auth()->user()->branchs_id == $updateProduct->branchs_id ? 0 : 1,
                    'branchs_id' => Auth()->User()->branch->id,
                    'discountOnProduct' => 0,
                    'discount' => 0,
                    'Number_of_Quantity' => $quantity,
                    'note' => $request->note,
                    'created_at' => \Carbon\Carbon::now()->addHours(3),
                    'updated_at' => \Carbon\Carbon::now()->addHours(3),
                ]
            );

            $invoiceNumber = $Invoice->id;
        } else {

            $InvoiceData = invoices::find($invoiceNumber);
            $Invoice = invoices::where('id',  $invoiceNumber)->Update(
                [
                    'discount' => 0,
                    'Price' => $InvoiceData->Price + (($updateProduct->price) * $quantity),
                    'Added_Value' => $InvoiceData->Added_Value + (round(($updateProduct->price) * $avtSaleRate->AVT,2) * $quantity),
                    'Number_of_Quantity' => $InvoiceData->Number_of_Quantity + $quantity,
                    'note' => $request->note,
                    'status' => $InvoiceData->status != 1 &  Auth()->user()->branchs_id == $updateProduct->branchs_id ? 0 : 1,
                    'updated_at' => \Carbon\Carbon::now()->addHours(3),
                ]
            );
        }
        $checksale = sales::where('invoice_id', $invoiceNumber)->where('product_id', $updateProduct->id)->first();

        if ($checksale != null) {

            sales::where('invoice_id', $invoiceNumber)->where('product_id', $updateProduct->id)->update(
                [
                    'quantity' =>  $checksale->quantity + $quantity
                ]
            );
        } else {
            $productSales = sales::create(
                [
                    'product_id' => $updateProduct->id,
                    'invoice_id' => $invoiceNumber,
                    'branch_id' => Auth()->User()->branch->id,
                    'Discount_Value' => 0,
                    'Added_Value' => ($updateProduct->price) * $avtSaleRate->AVT,
                    'Unit_Price' => $updateProduct->price,
                    'quantity' =>  $quantity,
                    'created_at' => \Carbon\Carbon::now()->addHours(3),
                ]
            );
        }



        // if ($request->pay == "Credit") {
        //     $customerdata = customers::find($request->clientnamesearch);

        //     $updateCustomer = customers::where('id', $request->clientnamesearch)->update(
        //         [
        //             'Balance' => $customerdata->Balance + ((($request->product_price * $request->quantity) - $request->product_price_after_dis) + ((($request->quantity * $request->product_price) - $request->product_price_after_dis) * $avtSaleRate->AVT))
        //         ]
        //     );
        // }
        $products = sales::where('invoice_id',  $invoiceNumber)->get();
        //return $product;
        $allProdctsD = [];
        $i = 0;
        foreach ($products as $product) {
            $updateProduct = products::find($product->product_id);

            $i++;
            $allProdctsD[] = [
                'Product_Code' => $product->productData->barcode,
                'product_name' => $product->productData->name,
                'quantity' => $product->quantity,
                'Unit_Price' => $product->Unit_Price,
                'Discount_Value' => $product->Discount_Value,
                'Added_Value' => $product->Added_Value,
                'count' => $i,
                'id' => $product->id
            ];
        }


        $customer = customers::find($request->clientnamesearch);
        $InvoiceData = invoices::find($invoiceNumber);
        $data = [
            "invoicetotal_price" => $InvoiceData->Price - $InvoiceData->discount,
            "invoicetotal_addedvalue" => round($InvoiceData->Added_Value,2),
            "invoicetotal_discount" => $InvoiceData->discount,
            'invoice_number' => $invoiceNumber,
            'pay' => $request->pay,
            'customer' => $customer,
            'product' => $allProdctsD,
            "invoice_id" => $invoiceNumber
        ];

        if (Auth()->user()->branchs_id != $updateProduct->branchs_id) {
            Delivery_product_to_the_customer::create(
                [
                    'branch_from' => Auth()->user()->branchs_id,
                    'branch_to' => $updateProduct->branchs_id,
                    'user_from' => Auth()->user()->id,
                    'product_id' => $updateProduct->id,
                    'invoice_id' => $invoiceNumber,
                    'quantity' => $request->quantity,
                    'status' => 0,
                    'created_at' => \Carbon\Carbon::now()->addHours(3),
                ]
            );
        }
        return ($data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */


    public function saveInvoice($invoiceId)
    {
        $avtSaleRate = Avt::find(1);

        $invoice = invoices::find($invoiceId);
        if ($invoice == null) {
            return [0];
        } else {
            invoices::find($invoiceId)->update(
                [
                    'save' => 1
                ]
            );
            sales::where('invoice_id', $invoiceId)->update(
                [
                    'save' => 1

                ]

            );
            // sales::where('invoice_id', $invoiceId)->where('quantity',0)->delete();
            foreach (sales::where('invoice_id', $invoiceId)->get() as $sale) {
                $productdata = products::find($sale->product_id);
                if (Auth()->user()->branchs_id == $productdata->branchs_id) {
                    if ($productdata->parent_inv_itemcard_id != 0) {
                        $product = products::find($productdata->parent_inv_itemcard_id);

                        $updatedproduct = products::where('id', $productdata->parent_inv_itemcard_id)->update(
                            [
                                'All_QUENTITY' => ($product->QUENTITY_all_Retails - $sale['quentity']) / $product->retail_uom_quntToParent,
                                'QUENTITY' => (int)(($product->QUENTITY_all_Retails - $sale['quentity']) / $product->retail_uom_quntToParent),
                                'QUENTITY_Retail' => ((($product->QUENTITY_all_Retails - $sale['quentity'])) % $product->retail_uom_quntToParent),
                                'QUENTITY_all_Retails' => ($product->QUENTITY_all_Retails - $sale['quentity']),
                            ]
                        );
                        products::where('parent_inv_itemcard_id', $sale->product_id)->update(
                            [
                                'All_QUENTITY' => ($product->QUENTITY_all_Retails - $sale['quentity']),
                            ]
                        );
                    } else {
                        $product = products::find($sale->product_id);

                        $updatedproduct = products::where('id', $sale->product_id)->update(
                            [
                                'All_QUENTITY' => ($product->All_QUENTITY - $sale['quentity']),
                                'QUENTITY' => (int)($product->All_QUENTITY - $sale['quentity']),
                                'QUENTITY_Retail' => ($product->All_QUENTITY - $sale['quentity']) - ((int)($product->All_QUENTITY - $sale['quentity'])),
                                'QUENTITY_all_Retails' => ($product->All_QUENTITY - $sale['quentity']) * $product->retail_uom_quntToParent,
                            ]
                        );
                        products::where('parent_inv_itemcard_id', $sale->product_id)->update(
                            [
                                'All_QUENTITY' => ($product->All_QUENTITY - $sale['quentity']) * $product->retail_uom_quntToParent,
                            ]
                        );
                    }
                }
            }
            return [1];
        }
    }
    public function updatepaymentconfirmpaymentpurchases($invoiceId, $cashamount, $bankamount, $creaditamount, $Bank_transfer, $paymentMethod)
    {


        $invoice = resource_purchases::where('orderId',$invoiceId)->first();
        if ($invoiceId == null) {
            return [0];
        } else {
            if ($invoice->Pay_Method_Name == 'Credit') {
                $customerdata = supllier::find($invoice->suplier_id);

                $updateCustomer = supllier::where('id', $invoice->suplier_id)->update(
                    [
                        'In_debt' => $customerdata->In_debt - ($cashamount + $bankamount + $creaditamount + $Bank_transfer)
                    ]
                );
            }
            resource_purchases::where('orderId',$invoiceId)->update(
                [
                 
                    'Pay_Method_Name' => $paymentMethod
                ]
            );



            if ($creaditamount != 0 || $creaditamount != null) {
                $customerdata = supllier::find($invoice->suplier_id);

                $updateCustomer = supllier::where('id', $invoice->suplier_id)->update(
                    [
                        'In_debt' => $customerdata->In_debt + ($creaditamount)
                    ]
                );
            }
        }
        $data = resource_purchases::where('branchs_id', Auth()->user()->branchs_id)->where('save', 1)->orderby('id', 'desc')->paginate(20);
        return view('ajax_Recent_Invoices_purchases', compact('data'));
    }
    public function updatepaymentconfirmpaymentReciept($invoiceId, $cashamount, $bankamount, $creaditamount, $Bank_transfer, $paymentMethod)
    {


        $invoice = invoices::find($invoiceId);
        if ($invoice == null) {
            return [0];
        } else {
            if ($invoice->Pay == 'Credit') {
                $customerdata = customers::find($invoice->customer_id);

                $updateCustomer = customers::where('id', $invoice->customer_id)->update(
                    [
                        'Balance' => $customerdata->Balance - ($cashamount + $bankamount + $creaditamount + $Bank_transfer)
                    ]
                );
            }
            invoices::find($invoiceId)->update(
                [
                    'save' => 1,
                    'morepayment_way' => 1,
                    'cashamount' => $cashamount,
                    'bankamount' => $bankamount,
                    'creaditamount' => $creaditamount,
                    'Bank_transfer' => $Bank_transfer,
                    'Pay' => $paymentMethod
                ]
            );



            if ($creaditamount != 0 || $creaditamount != null) {
                $customerdata = customers::find($invoice->customer_id);

                $updateCustomer = customers::where('id', $invoice->customer_id)->update(
                    [
                        'Balance' => $customerdata->Balance + ($creaditamount)
                    ]
                );
            }
        }
        $data = invoices::where('branchs_id', Auth()->user()->branchs_id)->where('save', 1)->where('status', 1)->orderby('id', 'desc')->paginate(20);
        return view('ajax_Recent_Invoices', compact('data'));
    }

    public function updatepaymentconfirmpayment($invoiceId, $cashamount, $bankamount, $creaditamount, $Bank_transfer, $paymentMethod)
    {


        $invoice = invoices::find($invoiceId);
        if ($invoice == null) {
            return [0];
        } else {
            if ($invoice->Pay == 'Credit') {
                $customerdata = customers::find($invoice->customer_id);

                $updateCustomer = customers::where('id', $invoice->customer_id)->update(
                    [
                        'Balance' => $customerdata->Balance - ($cashamount + $bankamount + $creaditamount + $Bank_transfer)
                    ]
                );
            }
            invoices::find($invoiceId)->update(
                [
                    'save' => 1,
                    'morepayment_way' => 1,
                    'cashamount' => $cashamount,
                    'bankamount' => $bankamount,
                    'creaditamount' => $creaditamount,
                    'Bank_transfer' => $Bank_transfer,
                    'Pay' => $paymentMethod
                ]
            );



            if ($creaditamount != 0 || $creaditamount != null) {
                $customerdata = customers::find($invoice->customer_id);

                $updateCustomer = customers::where('id', $invoice->customer_id)->update(
                    [
                        'Balance' => $customerdata->Balance + ($creaditamount)
                    ]
                );
            }
        }
        $data = invoices::where('branchs_id', Auth()->user()->branchs_id)->where('save', 1)->where('status', 0)->orderby('id', 'desc')->paginate(20);
        return view('ajax_Recent_Invoices', compact('data'));
    }

    public function confirmpaymentconfirmpayment($invoiceId, $cashamount, $bankamount, $creaditamount, $Bank_transfer, $paymentMethod)
    {
        $cashamount ?? 0;
        $bankamount ?? 0;
        $creaditamount ?? 0;
        $Bank_transfer ?? 0;

        $invoice = invoices::find($invoiceId);
        if ($invoice == null) {
            return [0];
        } else {
            invoices::find($invoiceId)->update(
                [
                    'save' => 1,
                    'morepayment_way' => 1,
                    'cashamount' => $cashamount,
                    'bankamount' => $bankamount,
                    'creaditamount' => $creaditamount,
                    'Bank_transfer' => $Bank_transfer,
                    'Pay' => $paymentMethod,
                    'issue_date' => substr(\Carbon\Carbon::now()->addHours(3), 0, 10),
                    'issue_time' => substr(\Carbon\Carbon::now()->addHours(3), 12),                ]
            );
            sales::where('invoice_id', $invoiceId)->update(
                [
                    'save' => 1

                ]
            );
            foreach (sales::where('invoice_id', $invoiceId)->get() as $sale) {
                $productdata = products::find($sale->product_id);
                if (Auth()->user()->branchs_id == $productdata->branchs_id) {
                    if ($productdata->parent_inv_itemcard_id != 0) {
                        $product = products::find($productdata->parent_inv_itemcard_id);

                        $updatedproduct = products::where('id', $productdata->parent_inv_itemcard_id)->update(
                            [
                                'All_QUENTITY' => ($product->QUENTITY_all_Retails - $sale->quantity) / $product->retail_uom_quntToParent,
                                'QUENTITY' => (int)(($product->QUENTITY_all_Retails - $sale->quantity) / $product->retail_uom_quntToParent),
                                'QUENTITY_Retail' => ((($product->QUENTITY_all_Retails - $sale->quantity)) % $product->retail_uom_quntToParent),
                                'QUENTITY_all_Retails' => ($product->QUENTITY_all_Retails - $sale->quantity),
                            ]
                        );
                        products::where('id', $sale->product_id)->update(
                            [
                                'All_QUENTITY' => ($product->QUENTITY_all_Retails - $sale->quantity),
                            ]
                        );
                     } else {
                        $product = products::find($sale->product_id);

                        $updatedproduct = products::where('id', $sale->product_id)->update(
                            [
                                'All_QUENTITY' => ($product->All_QUENTITY - $sale->quantity),
                                'QUENTITY' => (int)($product->All_QUENTITY - $sale->quantity),
                                'QUENTITY_Retail' => ($product->All_QUENTITY - $sale->quantity) - ((int)($product->All_QUENTITY - $sale->quantity)),
                                'QUENTITY_all_Retails' => ($product->QUENTITY_all_Retails) -$sale->quantity* $product->retail_uom_quntToParent,
                            ]
                        );
                        products::where('parent_inv_itemcard_id', $sale->product_id)->update(
                            [
                                'All_QUENTITY' =>($product->QUENTITY_all_Retails) -$sale->quantity* $product->retail_uom_quntToParent,
                            ]
                        );
                    }
                }
            }

            if ($creaditamount != 0 || $creaditamount != null) {
                $customerdata = customers::find($invoice->customer_id);

                $updateCustomer = customers::where('id', $invoice->customer_id)->update(
                    [
                        'Balance' => $customerdata->Balance + ($creaditamount)
                    ]
                );
            }
        }
        return [1];
    }








    public function store(Request $request)
    {
        //

        // return Auth()->User()->branch->id;
        app()->setLocale(LaravelLocalization::getCurrentLocale());
        $avtSaleRate = Avt::find(1);
        //return $avtSaleRate->AVT;
        $updateProduct = products::find($request->productNo);

        //return $updateProduct;


        // if ($updateProduct->numberofpice >= $request->quantity) {
        if (true) {

            if (Auth()->user()->branchs_id == $updateProduct->branchs_id) {
                // products::where('id', $request->productNo)->Update([
                //     'numberofpice' => $updateProduct->numberofpice - $request->quantity,
                //     'numberـofـsales' => $updateProduct->numberـofـsales + $request->quantity
                // ]);
            }
            $invoiceNumber = $request->invoice_number;
            if ($request->invoice_number == null) {
                // return ($request->pay);

                $Invoice = invoices::create(
                    [
                        'customer_id' => $request->clientnamesearch ?? 1,
                        'user_id' => Auth()->user()->id,
                        'Price' => round(($request->product_price) * $request->quantity,2),
                        'Added_Value' => round((($request->product_price) * $avtSaleRate->AVT),2) * $request->quantity,
                        'Pay' => $request->pay,
                        'status' => Auth()->user()->branchs_id == $updateProduct->branchs_id ? 0 : 1,
                        'branchs_id' => Auth()->User()->branch->id,
                        'discountOnProduct' => $request->product_price_after_dis,
                        'discount' => $request->product_price_after_dis,
                        'Number_of_Quantity' => $request->quantity,
                        'note' => $request->note,
                        'created_at' => \Carbon\Carbon::now()->addHours(3),
                        'updated_at' => \Carbon\Carbon::now()->addHours(3),
                    ]
                );

                $invoiceNumber = $Invoice->id;
            } else {

                $InvoiceData = invoices::find($invoiceNumber);
                $Invoice = invoices::where('id',  $invoiceNumber)->Update(
                    [
                        'discount' => $request->product_price_after_dis,
                        'Price' => $InvoiceData->Price + round(($request->product_price) * $request->quantity,2),
                        'Added_Value' => $InvoiceData->Added_Value + round(($request->product_price) * $avtSaleRate->AVT,2)* $request->quantity,
                        'Number_of_Quantity' => $InvoiceData->Number_of_Quantity + $request->quantity,
                        'discount' => $InvoiceData->discount + $request->product_price_after_dis,
                        'discountOnProduct' => $InvoiceData->discountOnProduct + $request->product_price_after_dis,
                        'note' => $request->note,
                        'status' => $InvoiceData->status != 1 &  Auth()->user()->branchs_id == $updateProduct->branchs_id ? 0 : 1,
                        'updated_at' => \Carbon\Carbon::now()->addHours(3),
                    ]
                );
            }
            $checksale = sales::where('invoice_id', $invoiceNumber)->where('product_id', $request->productNo)->first();

            if ($checksale != null) {
                $InvoiceData = invoices::find($invoiceNumber);
                $Invoice = invoices::where('id',  $invoiceNumber)->Update(
                    [
                        'Price' => $InvoiceData->Price  -round( ($checksale->Unit_Price * $checksale->quantity),2)+ round((($request->product_price) *$checksale->quantity),2),
                        'Added_Value' => $InvoiceData->Added_Value - round( ($checksale->Added_Value * $checksale->quantity),2) + round((($request->product_price * $avtSaleRate->AVT)),2)* $checksale->quantity ,

                    ]
                );
                sales::where('invoice_id', $invoiceNumber)->where('product_id', $request->productNo)->update(
                    [
                        'Added_Value' => round(($request->product_price) * $avtSaleRate->AVT,2),
                        'Unit_Price' => $request->product_price,
                        'quantity' => $request->quantity + $checksale->quantity
                    ]
                );
            } else {
                $productSales = sales::create(
                    [
                        'product_id' => $request->productNo,
                        'invoice_id' => $invoiceNumber,
                        'branch_id' => Auth()->User()->branch->id,
                        'Discount_Value' => $request->product_price_after_dis,
                        'Added_Value' => round(($request->product_price) * $avtSaleRate->AVT,2),
                        'Unit_Price' => $request->product_price,
                        'quantity' => $request->quantity,
                        'created_at' => \Carbon\Carbon::now()->addHours(3),
                    ]
                );
            }
        } else {
            $message = LaravelLocalization::getCurrentLocale() == 'ar' ? 'عدم وجود مخزون من هذه المنتج' : 'Out of stock of this product';

            session()->flash('delete',  $message);
            $data = ["notfount"];

            // return view('products.sales',compact('data'));
            return $data;
        }



        $products = sales::where('invoice_id', $invoiceNumber)->get();
        //return $product;
        $allProdctsD = [];
        $i = 0;
        foreach ($products as $product) {
            $i++;
            $updateProduct = products::find($product->product_id);

            $allProdctsD[] = [
                'Product_Code' => $product->productData->barcode,
                'product_name' => $product->productData->name,
                'quantity' => $product->quantity,
                'Unit_Price' => $product->Unit_Price,
                'Discount_Value' => $product->Discount_Value,
                'reamingquantity' => $updateProduct->numberofpice - $product->quantity,
                'Added_Value' => $product->Added_Value,
                'count' => $i,
                'id' => $product->id
            ];
        }


        $customer = customers::find($request->clientnamesearch);
        $InvoiceData = invoices::find($invoiceNumber);

        $data = [
            "invoicetotal_price" => $InvoiceData->Price - $InvoiceData->discount,
            "invoicetotal_addedvalue" =>$InvoiceData->Added_Value,
            "invoicetotal_discount" => $InvoiceData->discount,
            'invoice_number' => $invoiceNumber,
            'pay' => $request->pay,
            'customer' => $customer,
            'product' => $allProdctsD,
            "invoice_id" => $invoiceNumber
        ];


        // $data=[
        //     'sales_id'=>$productSales->id      ,
        //     'invoice_number'=>$invoiceNumber,
        //     'product_code'=>$productSales->productData->Product_Code ,
        //     'product_name'=>$productSales->productData->product_name ,
        //     'quentity'=>$productSales->quantity,
        //     "price"=>$productSales->Unit_Price,
        //     'discount'=> $productSales->Discount_Value  ,
        //     "addedvalue"=>$productSales-> Added_Value  ,
        //     "total"=>($productSales-> Added_Value*$productSales->quantity)+($productSales->Unit_Price*$productSales->quantity)
        //         ];
        //return view('products.sales',compact('data'));
        if (Auth()->user()->branchs_id != $updateProduct->branchs_id) {
            Delivery_product_to_the_customer::create(
                [
                    'branch_from' => Auth()->user()->branchs_id,
                    'branch_to' => $updateProduct->branchs_id,
                    'user_from' => Auth()->user()->id,
                    'product_id' => $updateProduct->id,
                    'invoice_id' => $invoiceNumber,
                    'quantity' => $request->quantity,
                    'status' => 0,
                    'created_at' => \Carbon\Carbon::now()->addHours(3),
                ]
            );
        }
        return ($data);
    }


    public function printreturnInvoice($request)
    {
        //
        //  return $request;
        app()->setLocale(LaravelLocalization::getCurrentLocale());


        app()->setLocale(LaravelLocalization::getCurrentLocale());

        $avtSaleRate = Avt::find(1);

        $saleData = return_sales::where("invoice_id", $request)->get();
        $InvoiceData = invoices::find($request);
        $data = [

            'salesData' => $saleData,
            'invoiceData' =>  $InvoiceData,
        ];

        return  view('products.printInvoicesToClientReturnSales', compact('data'));
    }


    public function printreturnReturnSalesInvoice($request)
    {
        //
        //  return $request;
        app()->setLocale(LaravelLocalization::getCurrentLocale());


        app()->setLocale(LaravelLocalization::getCurrentLocale());

        $avtSaleRate = Avt::find(1);

        $saleData = return_sales::where("invoice_id", $request)->where("quantity", '!=', 0)->get();
        $InvoiceData = invoices::find($request);
        $data = [

            'salesData' => $saleData,
            'invoiceData' =>  $InvoiceData,
        ];

        return  view('products.printInvoicesToClientReturnSales', compact('data'));
    }
    public function showInvoiceRecent($request)
    {
        //
        //  return $request;

        app()->setLocale(LaravelLocalization::getCurrentLocale());

        $avtSaleRate = Avt::find(1);

        $saleData = sales::where("invoice_id", $request)->where('quantity', '!=', 0)->get();
        $InvoiceData = invoices::find($request);
  $totAL=$InvoiceData->Bank_transfer +  $InvoiceData->creaditamount + $InvoiceData->bankamount + $InvoiceData->cashamount;

                     $totAL= number_format($totAL, 2);
    
    
            list($whole, $decimal) = explode('.',str_replace(",","",$totAL));
             $check=str_split($decimal);
             if($check[0]=="0"){
               $decimal =(int)$check[1] ;
             }
             else{
            $decimal =$decimal ;
     
             }
        $data = [
            "invoicetotal_price" => $InvoiceData->Price,
            "invoicetotal_addedvalue" => $InvoiceData->Added_Value,
            "invoicetotal_discount" => $InvoiceData->discount,
            'salesData' => $saleData,
            'invoiceData' =>  $InvoiceData,
            'totatextlriyales'=>$whole==0?' ':NumToArabic::number2Word(round((int)$whole,2)) .'  ريال',
            'totatextlrihalala'=>$decimal!='00'?NumToArabic::number2Word(round((int)$decimal,2)). '   هللة':'فقط', 
        ];

        //  return $data;
        return  view('products.printInvoicesReturnToClientRecentSales', compact('data'));
    }
    public function showRecieptRecent($request)
    {
        //
        //  return $request;


        $avtSaleRate = Avt::find(1);
        app()->setLocale(LaravelLocalization::getCurrentLocale());

        $saleData = sales::where("invoice_id", $request)->get();
        $InvoiceData = invoices::find($request);
        $data = [
            "invoicetotal_price" => $InvoiceData->Price - $InvoiceData->discount,
            "invoicetotal_addedvalue" => ($InvoiceData->Price - $InvoiceData->discount) * $avtSaleRate->AVT,
            "invoicetotal_discount" => $InvoiceData->discount,

            'salesData' => $saleData,
            'invoiceData' =>  $InvoiceData,
        ];
        // return $InvoiceData->customer->name;
        return  view('products.printInvoicesToCustomer', compact('data'));
    }

    public function showInvoice($request)
    {
        //
        //  return $request;

        app()->setLocale(LaravelLocalization::getCurrentLocale());

        $avtSaleRate = Avt::find(1);

        $saleData = sales::where("invoice_id", $request)->get();
        $InvoiceData = invoices::find($request);
  $totAL=$InvoiceData->Bank_transfer +  $InvoiceData->creaditamount + $InvoiceData->bankamount + $InvoiceData->cashamount;

                     $totAL= number_format($totAL, 2);
    
    
            list($whole, $decimal) = explode('.',str_replace(",","",$totAL));
             $check=str_split($decimal);
             if($check[0]=="0"){
               $decimal =(int)$check[1] ;
             }
             else{
            $decimal =$decimal ;
     
             }
        $data = [
            "invoicetotal_price" => $InvoiceData->Price - $InvoiceData->discount,
            "invoicetotal_addedvalue" => $InvoiceData->Added_Value ,
            "invoicetotal_discount" => $InvoiceData->discount,
            'salesData' => $saleData,
            'invoiceData' =>  $InvoiceData,
            'totatextlriyales'=>$whole==0?' ':NumToArabic::number2Word(round((int)$whole,2)) .'  ريال',
            'totatextlrihalala'=>$decimal!='00'?NumToArabic::number2Word(round((int)$decimal,2)). '   هللة':'فقط', 
        ];

        //  return $data;
        return  view('products.printInvoicesToClient', compact('data'));
    }


    public function changechustomerInInvoice($orderId, $newUserId)
    {

        $invoices = invoices::where('id', $orderId)->first();


        if ($invoices->Pay == 'Credit') {
            $customerdata = customers::find($invoices->customer_id);
            $avt = Avt::find(1);
            $saleavt = $avt->AVT;
            // $updateCustomer = customers::where('id', $invoices->customer_id)->update(
            //     [
            //         'Balance' => $customerdata->Balance - (($invoices->Price - $invoices->discount) + (($invoices->Price - $invoices->discount) * $saleavt))
            //     ]
            // );
            $customerdatanew = customers::find($newUserId);

            // $updateCustomer = customers::where('id', $newUserId)->update(
            //     [
            //         'Balance' => $customerdatanew->Balance + (($invoices->Price - $invoices->discount) + (($invoices->Price - $invoices->discount) * $saleavt))
            //     ]
            // );
        }
        $InvoiceData = invoices::where('id', $orderId)->update(
            ['customer_id' => $newUserId]
        );
        return 'Done';
    }

    public function changePaymethodIninvoice($orderId, $paymentMethod)
    {
        $invoices = invoices::where('id', $orderId)->first();

        // if ($invoices->Pay == 'Credit') {
        //     $customerdata = customers::find($invoices->customer_id);

        //     $updateCustomer = customers::where('id', $invoices->customer_id)->update(
        //         [
        //             'Balance' => $customerdata->Balance - ($invoices->Price + $invoices->Added_Value - $invoices->discount)
        //         ]
        //     );
        // }
        $InvoiceData = invoices::where('id', $orderId)->update(
            ['Pay' => $paymentMethod]
        );
        $invoices = invoices::where('id', $orderId)->first();
        // if ($paymentMethod == "Credit") {
        //     $customerdata = customers::find($invoices->customer_id);

        //     $updateCustomer = customers::where('id', $invoices->customer_id)->update(
        //         [
        //             'Balance' => $customerdata->Balance + ($invoices->Price + $invoices->Added_Value - $invoices->discount)
        //         ]
        //     );
        // }
        return 'Done';
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\invoices  $invoices
     * @return \Illuminate\Http\Response
     */
    public function printInvoice(Request $request)
    {
        //
        //  return $request;
        app()->setLocale(LaravelLocalization::getCurrentLocale());

        if ($request->show_invoice_number == null) {
            $products = products::where('branchs_id', Auth()->User()->branchs_id)->paginate(20);
            session()->flash('nodataprint', '');

            return view('products.sales', compact('products'));
        }
        app()->setLocale(LaravelLocalization::getCurrentLocale());
        $avtSaleRate = Avt::find(1);


        $saleData = sales::where("invoice_id", $request->show_invoice_number)->where('quantity', '!=', 0)->get();
        $InvoiceData = invoices::find($request->show_invoice_number);
       $totAL=$InvoiceData->Bank_transfer +  $InvoiceData->creaditamount + $InvoiceData->bankamount + $InvoiceData->cashamount;

                     $totAL= number_format($totAL, 2);
    
    
            list($whole, $decimal) = explode('.',str_replace(",","",$totAL));
             $check=str_split($decimal);
             if($check[0]=="0"){
               $decimal =(int)$check[1] ;
             }
             else{
            $decimal =$decimal ;
     
             }
        $data = [
            "invoicetotal_price" => $InvoiceData->Price - $InvoiceData->discount,
            "invoicetotal_addedvalue" => $InvoiceData->Added_Value ,
            "invoicetotal_discount" => $InvoiceData->discount,
            'salesData' => $saleData,
            'invoiceData' =>  $InvoiceData,
            'totatextlriyales'=>$whole==0?' ':NumToArabic::number2Word(round((int)$whole,2)) .'  ريال',
            'totatextlrihalala'=>$decimal!='00'?NumToArabic::number2Word(round((int)$decimal,2)). '   هللة':'فقط', 
        ];

        //  return $data;
        return  view('products.printInvoicesToClient', compact('data'));

        // $saleData= sales::where("invoice_id",$invoicesid)->get();
        // $InvoiceData=invoices::find($invoicesid);
        // $data=[
        //     'salesData'=>$saleData ,
        //     'invoiceData'=>  $InvoiceData,
        // ];
        //return $data;
        return  view('products.printInvoicesToClient', compact('data'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\invoices  $invoices
     * @return \Illuminate\Http\Response
     */

    public function increaseProduct(Request $request)
    {
        //
        // return  $request;
        $avtSaleRate = Avt::find(1);

        app()->setLocale(LaravelLocalization::getCurrentLocale());

        $saleData = sales::find($request->id);
        $productData = products::find($saleData->product_id);
        if (true) {


            $productSales = sales::where('id', $request->id)->update(
                [
                    'quantity' => $saleData->quantity + $request->increasequantity,
                    'updated_at' => \Carbon\Carbon::now()->addHours(3),
                ]
            );

            $productData = products::find($saleData->product_id);
            // if (Auth()->user()->branchs_id == $productData->branchs_id) {

            //     products::where('id', $saleData->product_id)->Update([
            //         'numberofpice' => $productData->numberofpice - $request->increasequantity
            //     ]);
            // }
            $InvoiceData = invoices::find($saleData->invoice_id);

            $Invoice = invoices::where('id',  $saleData->invoice_id)->Update(
                [

                    'Price' => round($InvoiceData->Price + ($saleData->Unit_Price * $request->increasequantity), 2),
                    'Added_Value' => round(($InvoiceData->Added_Value + ($saleData->Added_Value * $request->increasequantity)),
                        2
                    ),
                    'Number_of_Quantity' => $InvoiceData->Number_of_Quantity + $request->increasequantity,
                    'updated_at' => \Carbon\Carbon::now()->addHours(3),
                ]
            );


            $InvoiceData = invoices::find($saleData->invoice_id);
            // if ($InvoiceData->Pay == "Credit") {
            //     $customerdata = customers::find($InvoiceData->customer_id);
            //     // return ($customerdata->Balance-(($request->return_quentity*$saleData->Unit_Price)+($request->return_quentity*$saleData->Added_Value)));
            //     $updateCustomer = customers::where('id', $InvoiceData->customer_id)->update(
            //         [
            //             'Balance' => $customerdata->Balance + (($request->increasequantity * $saleData->Unit_Price) + ($request->increasequantity * $saleData->Added_Value)),
            //             'updated_at' => \Carbon\Carbon::now()->addHours(3),

            //         ]
            //     );
            // }

            $products = sales::where('invoice_id', $saleData->invoice_id)->get();
            $allProdctsD = [];
            $i = 0;
            foreach ($products as $product) {
                $updateProduct = products::find($product->product_id);

                $i++;
                $allProdctsD[] = [
                    'Product_Code' => $product->productData->barcode,
                    'product_name' => $product->productData->name,
                    'quantity' => $product->quantity,
                    'Unit_Price' => $product->Unit_Price,
                    'reamingquantity' => $updateProduct->numberofpice - $product->quantity,
                    'Discount_Value' => $product->Discount_Value,
                    'Added_Value' => $product->Added_Value,
                    'count' => $i,
                    'id' => $product->id
                ];
            }
            //return $product;
            $data = [
                "invoicetotal_price" => $InvoiceData->Price - $InvoiceData->discount,
                "invoicetotal_addedvalue" => ($InvoiceData->Added_Value) ,
                "invoicetotal_discount" => $InvoiceData->discount,
                'product' => $allProdctsD,
                "invoice_id" => $saleData->invoice_id
            ];
            return $data;
        } else {
            return ["notfount"];
        }
        return view('products.sales', compact('data'));
    }
    public function printReceiptToStorehouse(Request $request)
    {
        //
        app()->setLocale(LaravelLocalization::getCurrentLocale());

        $avtSaleRate = Avt::find(1);
        // return $request;
        if ($request->show_invoice_number == null) {
            $products = products::where('branchs_id', Auth()->User()->branchs_id)->paginate(20);
            // return $products;
            session()->flash('nodataprint', '');
            return view('products.Receipt', compact('products'));
        }
        app()->setLocale(LaravelLocalization::getCurrentLocale());

        $saleData = sales::where("invoice_id", $request->show_invoice_number)->where('quantity', '!=', 0)->get();
        $InvoiceData = invoices::find($request->show_invoice_number);
        $data = [
            "invoicetotal_price" => $InvoiceData->Price - $InvoiceData->discount,
            "invoicetotal_addedvalue" => ($InvoiceData->Price - $InvoiceData->discount) * $avtSaleRate->AVT,
            "invoicetotal_discount" => $InvoiceData->discount,

            'salesData' => $saleData,
            'invoiceData' =>  $InvoiceData,
        ];
        // return $InvoiceData->customer->name;
        return  view('products.printInvoicesToCustomer', compact('data'));
    }




    public function editRecipt(Request $request)
    {
        //
        // return  $request;
        $avtSaleRate = Avt::find(1);

        app()->setLocale(LaravelLocalization::getCurrentLocale());

        $saleData = sales::find($request->id);
        $productSales = sales::where('id', $request->id)->update(
            [
                'quantity' => $saleData->quantity - $request->return_quentity,
                'updated_at' => \Carbon\Carbon::now()->addHours(3),
            ]
        );

        $productData = products::find($saleData->product_id);
        // products::where('id', $saleData->product_id)->Update([
        //     'numberofpice' => $productData->numberofpice + $request->return_quentity
        // ]);
        $InvoiceData = invoices::find($saleData->invoice_id);

        $Invoice = invoices::where('id',  $saleData->invoice_id)->Update(
            [

                'Price' => round(($InvoiceData->Price - ($saleData->Unit_Price * $request->return_quentity)), 2),
                'Added_Value' => round(($InvoiceData->Added_Value - ($saleData->saleData * $request->return_quentity )), 2),

                'Number_of_Quantity' => $InvoiceData->Number_of_Quantity - $request->return_quentity,
                'updated_at' => \Carbon\Carbon::now()->addHours(3),
            ]
        );


        $InvoiceData = invoices::find($saleData->invoice_id);
        if ($InvoiceData->Pay == "Credit") {
            $customerdata = customers::find($InvoiceData->customer_id);
            // return ($customerdata->Balance-(($request->return_quentity*$saleData->Unit_Price)+($request->return_quentity*$saleData->Added_Value)));
            // $updateCustomer = customers::where('id', $InvoiceData->customer_id)->update(
            //     [
            //         'Balance' => $customerdata->Balance - (($request->return_quentity * $saleData->Unit_Price) + ($request->return_quentity * $saleData->Added_Value)),
            //         'updated_at' => \Carbon\Carbon::now()->addHours(3),

            //     ]
            // );
        }

        $products = sales::where('invoice_id', $saleData->invoice_id)->get();
        $allProdctsD = [];
        $i = 0;
        foreach ($products as $product) {
            $i++;
            $allProdctsD[] = [
                'Product_Code' => $product->productData->barcode,
                'product_name' => $product->productData->name,
                'quantity' => $product->quantity,
                'Unit_Price' => $product->Unit_Price,
                'Discount_Value' => $product->Discount_Value,
                'Added_Value' => $product->Added_Value,
                'count' => $i,
                'id' => $product->id
            ];
        }
        //return $product;
        $data = [
            "invoicetotal_price" => $InvoiceData->Price - $InvoiceData->discount,
            "invoicetotal_addedvalue" => ($InvoiceData->Price - $InvoiceData->discount) * $avtSaleRate->AVT,
            "invoicetotal_discount" => $InvoiceData->discount,
            'product' => $allProdctsD,
            "invoice_id" => $saleData->invoice_id
        ];
        return $data;
        return view('products.sales', compact('data'));
    }


    public function returnAll(Request $request)
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());
        $message = '';
        $avtSaleRate = Avt::find(1);

        $InvoiceData = invoices::find($request->invoice_no_delete_All);


        $saleData = sales::where('invoice_id', $request->invoice_no_delete_All)->get();
        $count = count($saleData);

        foreach ($saleData as $sale) {
            $updateProduct = products::find($sale->product_id);
            if ($updateProduct->branchs_id == $InvoiceData->branchs_id) {

                $productdata = products::find($sale->product_id);
                if ($productdata->parent_inv_itemcard_id != 0) {
                    $product = products::find($productdata->parent_inv_itemcard_id);

                    $updatedproduct = products::where('id', $productdata->parent_inv_itemcard_id)->update(
                        [
                            'All_QUENTITY' => ($product->QUENTITY_all_Retails + $sale->quantity) / $product->retail_uom_quntToParent,
                            'QUENTITY' => (int)(($product->QUENTITY_all_Retails + $sale->quantity) / $product->retail_uom_quntToParent),
                            'QUENTITY_Retail' => ((($product->QUENTITY_all_Retails + $sale->quantity)) % $product->retail_uom_quntToParent),
                            'QUENTITY_all_Retails' => ($product->QUENTITY_all_Retails + $sale->quantity),
                        ]
                    );
                    products::where('id', $sale->product_id)->update(
                        [
                            'All_QUENTITY' => ($product->QUENTITY_all_Retails + $sale->quantity),
                        ]
                    );
                } else {
                    $product = products::find($sale->product_id);

                    $updatedproduct = products::where('id', $sale->product_id)->update(
                        [
                            'All_QUENTITY' => ($product->All_QUENTITY +  $sale->quantity),
                            'QUENTITY' => (int)($product->All_QUENTITY + $sale->quantity),
                            'QUENTITY_Retail' => ($product->All_QUENTITY + $sale->quantity) - ((int)($product->All_QUENTITY + $sale->quantity)),
                            'QUENTITY_all_Retails' => ($product->All_QUENTITY + $sale->quantity) * $product->retail_uom_quntToParent,
                        ]
                    );
                    products::where('parent_inv_itemcard_id', $sale->product_id)->update(
                        [
                            'All_QUENTITY' => ($product->All_QUENTITY +  $sale->quantity) * $product->retail_uom_quntToParent,
                        ]
                    );
                }

                $message = LaravelLocalization::getCurrentLocale() == 'ar' ? "تم عملية الاسترجاع بنجاح شكرا" : "The recovery process was successful. Thank you.";
                session()->flash('success', $message);
            } else {

                $mproduct = products::where('branchs_id', $InvoiceData->branchs_id)->where('barcode', $updateProduct->barcode)->first();
                if ($mproduct != null) {


                    if ($$mproduct->parent_inv_itemcard_id != 0) {
                        $product = products::find($productdata->parent_inv_itemcard_id);

                        $updatedproduct = products::where('id', $productdata->parent_inv_itemcard_id)->update(
                            [
                                'All_QUENTITY' => ($product->QUENTITY_all_Retails + $sale->quantity) / $product->retail_uom_quntToParent,
                                'QUENTITY' => (int)(($product->QUENTITY_all_Retails + $sale->quantity) / $product->retail_uom_quntToParent),
                                'QUENTITY_Retail' => ((($product->QUENTITY_all_Retails + $sale->quantity)) % $product->retail_uom_quntToParent),
                                'QUENTITY_all_Retails' => ($product->QUENTITY_all_Retails + $sale->quantity),
                            ]
                        );
                        products::where('id', $$mproduct->id)->update(
                            [
                                'All_QUENTITY' => ($product->QUENTITY_all_Retails +  $sale->quantity),
                            ]
                        );
                    } else {
                        $product = products::find($sale->product_id);

                        $updatedproduct = products::where('id', $sale->product_id)->update(
                            [
                                'All_QUENTITY' => ($product->All_QUENTITY +  $sale->quantity),
                                'QUENTITY' => (int)($product->All_QUENTITY + $sale->quantity),
                                'QUENTITY_Retail' => ($product->All_QUENTITY + $sale->quantity) - ((int)($product->All_QUENTITY + $sale->quantity)),
                                'QUENTITY_all_Retails' => ($product->All_QUENTITY + $sale->quantity) * $product->retail_uom_quntToParent,
                            ]
                        );
                        products::where('parent_inv_itemcard_id', $saleData->product_id)->update(
                            [
                                'All_QUENTITY' => ($product->All_QUENTITY +  $sale->quantity) * $product->retail_uom_quntToParent,
                            ]
                        );
                    }


                    $message = LaravelLocalization::getCurrentLocale() == 'ar' ? "تم عملية الاسترجاع بنجاح شكرا" : "The recovery process was successful. Thank you.";
                    session()->flash('success', $message);
                } else {
                    $product = sales::where('invoice_id', $sale->invoice_id)->get();
                    $InvoiceData = invoices::where('id',  $sale->invoice_id)->first();
                    //return $product;
                    $productrecive = products::find($sale->product_id);

                    if ($productrecive->parent_inv_itemcard_id != 0) {
                        $parentproduct = products::find($productrecive->parent_inv_itemcard_id);



                        $newproductParent = products::create(
                            [
                                'item_code' => $parentproduct->item_code,
                                'barcode' =>  $parentproduct->barcode,
                                'name' =>  $parentproduct->name,
                                'item_type' => $parentproduct->item_type,
                                'inv_itemcard_categories_id' =>  $parentproduct->inv_itemcard_categories_id,
                                'parent_inv_itemcard_id' => 0,
                                'does_has_retailunit' => $parentproduct->does_has_retailunit,
                                'retail_uom_id' =>  $parentproduct->retail_uom_id,
                                'uom_id' =>  $parentproduct->uom_id,
                                'retail_uom_quntToParent' =>  $parentproduct->retail_uom_quntToParent,
                                'added_by' => Auth()->user()->id,
                                'updated_by' => Auth()->user()->id,
                                'active' =>  $parentproduct->active,
                                'date' => \Carbon\Carbon::now()->addHours(3),
                                'branchs_id' => Auth()->user()->branchs_id,
                                'price' =>  $parentproduct->price,
                                'price_retail' => $parentproduct->price_retail,
                                'photo' =>  $parentproduct->photo,
                                'cost_price' => 0,
                                'cost_price_retail' => 0,
                                'has_fixced_price' =>  $parentproduct->has_fixced_price,
                                'created_at' => \Carbon\Carbon::now()->addHours(3),
                                'Product_Location' => 'Transfer',
                                'minmum_quantity_stock_alart' =>  $parentproduct->minmum_quantity_stock_alart,
                            ]
                        );
                        $newproducts = products::create(
                            [
                                'item_code' => $productrecive->item_code,
                                'barcode' => $productrecive->barcode,
                                'name' => $productrecive->name,
                                'item_type' => $productrecive->item_type,
                                'inv_itemcard_categories_id' => $productrecive->inv_itemcard_categories_id,
                                'parent_inv_itemcard_id' => $newproductParent->id,
                                'does_has_retailunit' => $productrecive->does_has_retailunit,
                                'retail_uom_id' => $productrecive->retail_uom_id,
                                'uom_id' => $productrecive->uom_id,
                                'retail_uom_quntToParent' => $productrecive->retail_uom_quntToParent,
                                'added_by' => Auth()->user()->id,
                                'updated_by' => Auth()->user()->id,
                                'active' => $productrecive->active,
                                'date' => \Carbon\Carbon::now()->addHours(3),
                                'branchs_id' => Auth()->user()->branchs_id,
                                'price' => $productrecive->price,
                                'price_retail' => $productrecive->price_retail,
                                'photo' => $productrecive->photo,
                                'cost_price' => 0,
                                'cost_price_retail' => 0,
                                'has_fixced_price' => $productrecive->has_fixced_price,
                                'created_at' => \Carbon\Carbon::now()->addHours(3),
                                'Product_Location' => 'Transfer',
                                'minmum_quantity_stock_alart' => $productrecive->minmum_quantity_stock_alart,
                                "cost_price" => 0,
                                'prodection_date' => $productrecive->prodection_date,
                                "cost_price_retail" => 0
                            ]
                        );
                    } else {

                        $newproductParent = products::create(
                            [
                                'item_code' => $productrecive->barcode,
                                'barcode' => $productrecive->barcode,
                                'name' => $productrecive->name,
                                'item_type' => $productrecive->item_type,
                                'inv_itemcard_categories_id' => $productrecive->inv_itemcard_categories_id,
                                'parent_inv_itemcard_id' => $productrecive->parent_inv_itemcard_id,
                                'does_has_retailunit' => $productrecive->does_has_retailunit,
                                'retail_uom_id' => $productrecive->retail_uom_id,
                                'uom_id' => $productrecive->uom_id,
                                'retail_uom_quntToParent' => 0,
                                'added_by' => Auth()->user()->id,
                                'updated_by' => Auth()->user()->id,
                                'active' => $productrecive->active,
                                'date' => \Carbon\Carbon::now()->addHours(3),
                                'branchs_id' => Auth()->user()->branchs_id,
                                'price' => $productrecive->price,
                                'price_retail' => $productrecive->price_retail,
                                'cost_price' => 0,
                                'cost_price_retail' => 0,
                                'photo' => $productrecive->photo,
                                'has_fixced_price' => $productrecive->has_fixced_price,
                                'created_at' => \Carbon\Carbon::now()->addHours(3),
                                'Product_Location' => 'Transfer',
                                'minmum_quantity_stock_alart' => $productrecive->minmum_quantity_stock_alart,
                                "cost_price" => 0,
                                "cost_price_retail" => 0
                            ]
                        );
                    }


                    if ($productrecive->parent_inv_itemcard_id == 0) {

                        $updatedproduct = products::where('id', $newproductParent->id)->update(
                            [
                                'All_QUENTITY' => ($sale->quantity) / $productrecive->retail_uom_quntToParent,
                                'QUENTITY' => (int)($sale->quantity),
                                'QUENTITY_Retail' => ($sale->quantity) - ((int)($sale->quantity)),
                                'QUENTITY_all_Retails' => ($sale->quantity) * $productrecive->retail_uom_quntToParent,
                                "cost_price" => $productrecive->cost_price,
                                "cost_price_retail" =>  $productrecive->cost_price_retail * $productrecive->retail_uom_quntToParent,
                                // 'updated_at' => \Carbon\Carbon::now()->addHours(3),


                            ]
                        );
                    } else {

                        $updatedproductdata = products::where('id', $newproductParent->id)->update(
                            [
                                'All_QUENTITY' => ($sale->quantity) / $productrecive->retail_uom_quntToParent,
                                'QUENTITY' => (int)(($sale->quantity) / $productrecive->retail_uom_quntToParent),
                                'QUENTITY_Retail' => ((($sale->quantity)) % $productrecive->retail_uom_quntToParent) * $productrecive->retail_uom_quntToParent,
                                'QUENTITY_all_Retails' => ($sale->quantity),
                                "cost_price" =>  $productrecive->cost_price * $productrecive->retail_uom_quntToParent,
                                "cost_price_retail" =>  $productrecive->cost_price_retail,
                                // 'updated_at' => \Carbon\Carbon::now()->addHours(3),

                            ]
                        );
                        $updatedproduct = products::where('parent_inv_itemcard_id', $newproductParent->id)->update(
                            [
                                'All_QUENTITY' => ($sale->quantity),
                                'QUENTITY' => 0,
                                'QUENTITY_Retail' => 0,
                                'QUENTITY_all_Retails' => 0,
                                "cost_price" => $productrecive->cost_price,
                                "cost_price_retail" => 0,
                                // 'updated_at' => \Carbon\Carbon::now()->addHours(3),

                            ]
                        );
                    }
                    $productname = $updateProduct->name;

                    $message = LaravelLocalization::getCurrentLocale() == 'ar' ?  "  تم عملية الاسترجاع. المنتج المسترجع غير مسجل لديكم مسبقا تم تسجيل  " . $productname . "  بنفس رقم المنتج  شكرا  " : "The product is not previously registered. It has been registered with a name " . $productname . " and a product number, such as the number ";
                    // products::where('id', $newproducts->id)->Update([
                    //     'numberofpice' =>  $request->return_quentity,
                    // ]);
                    session()->flash('createnewproduct', $message);
                }
            }


            $invicedis = invoices::where('id',  $sale->invoice_id)->first();

            if ($count == 1) {

                $return_sales = return_sales::create([
                    'product_id' => $sale->product_id,
                    'invoice_id' => $sale->invoice_id,
                    'branch_id' => Auth()->User()->branch->id,
                    'discountvalue' => $sale->Discount_Value,
                    'discountoninvoice' => $invicedis->discount - $sale->Discount_Value,
                    'return_Added_Value' => $sale->Added_Value,
                    'return_Unit_Price' => $sale->Unit_Price,
                    'return_quantity' => $sale->quantity,
                    'created_at' => \Carbon\Carbon::now()->addHours(3),

                ]);
            } else {
                $return_sales = return_sales::create([
                    'product_id' => $sale->product_id,
                    'invoice_id' => $sale->invoice_id,
                    'branch_id' => Auth()->User()->branch->id,
                    'discountvalue' => $sale->Discount_Value,
                    'return_Added_Value' => $sale->Added_Value,
                    'return_Unit_Price' => $sale->Unit_Price,
                    'return_quantity' => $sale->quantity,
                    'created_at' => \Carbon\Carbon::now()->addHours(3),

                ]);
            }
            $mSale = sales::where('id', $sale->id)->first();


            $productSales = sales::where('id', $sale->id)->update(
                [
                    'quantity' => 0,
                    'quantityreturn' => $sale->quantity,
                    'updated_at' => \Carbon\Carbon::now()->addHours(3),
                    'Discount_Value' => 0
                ]
            );
            $count--;
        }




        // return   $productSales;

        if ($InvoiceData->Pay == "Credit") {
            $avtSaleRate = Avt::find(1);

            $invicedis = invoices::find($request->invoice_no_delete_All);

            $customerdata = customers::find($InvoiceData->customer_id);
            $updateCustomer = customers::where('id', $InvoiceData->customer_id)->update(
                [
                    'Balance' => $customerdata->Balance - (($invicedis->Price - $invicedis->discount) + (($invicedis->Price - $invicedis->discount) * $avtSaleRate->AVT)),
                    'updated_at' => \Carbon\Carbon::now()->addHours(3),

                ]
            );
        }



        $Invoice = invoices::where('id', $request->invoice_no_delete_All)->Update(
            [

                'Price' => 0,
                'Added_Value' => 0,
                'Number_of_Quantity' => 0,
                'discountOnInvoice' => $InvoiceData->discount - $InvoiceData->discountOnProduct,
                'discount' => 0,
                'updated_at' => \Carbon\Carbon::now()->addHours(3),
            ]
        );



        $data = [
            'message' => $message,
        ];
        return $data;
        return view($page, compact('products'));
    }



    public function updateproductallDataInvoices(Request $request)
    {
        //
        // return  $request;
        $avtSaleRate = Avt::find(1);

        app()->setLocale(LaravelLocalization::getCurrentLocale());

        $saleData = sales::find($request->id);
        $newquantity = 0;

        $productData = products::find($saleData->product_id);

        $newquantity = $request->quentity - $saleData->quantity;
        if (true) {
            // if (Auth()->user()->branchs_id == $productData->branchs_id) {

            //     products::where('id', $saleData->product_id)->Update([
            //         'numberofpice' => $productData->numberofpice + ($newquantity)
            //     ]);
            // }





            $InvoiceData = invoices::find($saleData->invoice_id);
            $customerdata = customers::find($InvoiceData->customer_id);

            // if ($InvoiceData->Pay == "Credit") {

            //     // $updateCustomer = customers::where('id', $InvoiceData->customer_id)->update(
            //     //     [
            //     //         'Balance' => $customerdata->Balance - ((($saleData->quantity * $saleData->Unit_Price) - $saleData->Discount_Value) + ((($saleData->quantity  * $saleData->Unit_Price) - $saleData->Discount_Value) * $avtSaleRate->AVT)),
            //     //         'updated_at' => \Carbon\Carbon::now()->addHours(3),

            //     //     ]
            //     // );
            //     $customerdata = customers::find($InvoiceData->customer_id);

            //     $updateCustomer = customers::where('id', $InvoiceData->customer_id)->update(
            //         [

            //             'Balance' => $customerdata->Balance + ((($request->quentity * $request->price)) + ((($request->quentity * $request->price) - $request->discount) * $avtSaleRate->AVT)),
            //             'updated_at' => \Carbon\Carbon::now()->addHours(3),

            //         ]
            //     );
            // }


            $Invoice = invoices::where('id',  $saleData->invoice_id)->Update(
                [

                    'Price' => $InvoiceData->Price - (($saleData->Unit_Price * $saleData->quantity)),
                    'Added_Value' => $InvoiceData->Added_Value - round((($saleData->Added_Value * $saleData->quantity) ),2),
                    'Number_of_Quantity' => $InvoiceData->Number_of_Quantity - $saleData->quantity,
                    'discount' => $InvoiceData->discount - $saleData->Discount_Value,
                    'updated_at' => \Carbon\Carbon::now()->addHours(3),
                ]
            );
            $InvoiceData = invoices::find($saleData->invoice_id);

            $Invoice = invoices::where('id',  $saleData->invoice_id)->Update(
                [

                    'Price' => $InvoiceData->Price + (($request->quentity * $request->price)),
                    'Added_Value' => $InvoiceData->Added_Value +( round((($avtSaleRate->AVT * $request->price)),2) * $request->quentity),
                    'Number_of_Quantity' => $InvoiceData->Number_of_Quantity + $request->quentity,
                    'discount' => $InvoiceData->discount + $request->discount,
                    'updated_at' => \Carbon\Carbon::now()->addHours(3),
                ]
            );

            $InvoiceData = invoices::find($saleData->invoice_id);

            // if ($InvoiceData->Number_of_Quantity == 0 && $InvoiceData->Pay == "Credit") {

            //     $updateCustomer = customers::where('id', $InvoiceData->customer_id)->update(
            //         [
            //             'Balance' => $customerdata->Balance - $InvoiceData->discount,
            //             'updated_at' => \Carbon\Carbon::now()->addHours(3),

            //         ]
            //     );
            // }


            if ($InvoiceData->Number_of_Quantity == 0) {
                $Invoice = invoices::where('id',  $saleData->invoice_id)->Update(
                    [
                        'discount' => 0,
                    ]
                );
            }
            $productSales = sales::where('id', $request->id)->update(
                [
                    'quantity' => $request->quentity,
                    'updated_at' => \Carbon\Carbon::now()->addHours(3),
                    'Discount_Value' => $request->discount,
                    'Unit_Price' => $request->price,
                    'Added_Value' => $request->avt

                ]
            );

            $products = sales::where('invoice_id', $saleData->invoice_id)->get();
            $allProdctsD = [];
            $i = 0;
            foreach ($products as $product) {
                $updateProduct = products::find($product->product_id);

                $i++;
                $allProdctsD[] = [
                    'Product_Code' => $product->productData->barcode,
                    'product_name' => $product->productData->name,
                    'quantity' => $product->quantity,
                    'Unit_Price' => $product->Unit_Price,
                    'reamingquantity' => $updateProduct->numberofpice - $product->quantity,
                    'Discount_Value' => $product->Discount_Value,
                    'Added_Value' => $product->Added_Value,
                    'count' => $i,
                    'id' => $product->id
                ];
            }
            $InvoiceData = invoices::find($saleData->invoice_id);

            //return $product;
            $data = [
                "invoicetotal_price" => $InvoiceData->Price - $InvoiceData->discount,
                "invoicetotal_addedvalue" => $InvoiceData->Added_Value,
                "invoicetotal_discount" => $InvoiceData->discount,
                'product' => $allProdctsD,
                "invoice_id" => $saleData->invoice_id
            ];
            return $data;
        } else {
            return [];
        }
    }









    public function edit(Request $request)
    {
        //
        // return  $request;
        $avtSaleRate = Avt::find(1);

        app()->setLocale(LaravelLocalization::getCurrentLocale());

        $saleData = sales::find($request->id);


        $productData = products::find($saleData->product_id);
        if (true) {

            // products::where('id', $saleData->product_id)->Update([
            //     'numberofpice' => $productData->numberofpice + $request->return_quentity
            // ]);
        }



        $InvoiceData = invoices::find($saleData->invoice_id);
        // if ($InvoiceData->Pay == "Credit") {
        //     $customerdata = customers::find($InvoiceData->customer_id);
        //     // return ($customerdata->Balance-(($request->return_quentity*$saleData->Unit_Price)+($request->return_quentity*$saleData->Added_Value)));
        //     $updateCustomer = customers::where('id', $InvoiceData->customer_id)->update(
        //         [
        //             'Balance' => $customerdata->Balance - ((($request->return_quentity * $saleData->Unit_Price) - $saleData->Discount_Value) + ((($request->return_quentity * $saleData->Unit_Price) - $saleData->Discount_Value) * $avtSaleRate->AVT)),
        //             'updated_at' => \Carbon\Carbon::now()->addHours(3),

        //         ]
        //     );
        // }

        $Invoice = invoices::where('id',  $saleData->invoice_id)->Update(
            [

                'Price' => round($InvoiceData->Price - (($saleData->Unit_Price * $request->return_quentity)), 2),
                'Added_Value' => round($InvoiceData->Added_Value - (($saleData->Added_Value * $request->return_quentity)), 2),
                'Number_of_Quantity' => $InvoiceData->Number_of_Quantity - $request->return_quentity,
                'updated_at' => \Carbon\Carbon::now()->addHours(3),
            ]
        );
        $InvoiceData = invoices::find($saleData->invoice_id);

        if ($InvoiceData->Number_of_Quantity == 0 && $InvoiceData->Pay == "Credit") {
            $InvoiceData = invoices::find($saleData->invoice_id);

            // $updateCustomer = customers::where('id', $InvoiceData->customer_id)->update(
            //     [
            //         'Balance' => $customerdata->Balance - $InvoiceData->discount,
            //         'updated_at' => \Carbon\Carbon::now()->addHours(3),

            //     ]
            // );
        }


        if ($InvoiceData->Number_of_Quantity == 0) {
            $Invoice = invoices::where('id',  $saleData->invoice_id)->Update(
                [
                    'discount' => 0,
                ]
            );
        } else {
            $Invoice = invoices::where('id',  $saleData->invoice_id)->Update(
                [
                    'discount' => $InvoiceData->discount - $saleData->Discount_Value,
                    'discountOnProduct' => $InvoiceData->discountOnProduct - $saleData->Discount_Value,
                ]
            );
        }
        $productSales = sales::where('id', $request->id)->update(
            [
                'quantity' => $saleData->quantity - $request->return_quentity,
                'updated_at' => \Carbon\Carbon::now()->addHours(3),
                'Discount_Value' => 0
            ]
        );

        $products = sales::where('invoice_id', $saleData->invoice_id)->get();
        $allProdctsD = [];
        $i = 0;
        foreach ($products as $product) {
            $i++;
            $updateProduct = products::find($product->product_id);

            $allProdctsD[] = [
                'Product_Code' => $product->productData->barcode,
                'product_name' => $product->productData->name,
                'quantity' => $product->quantity,
                'Unit_Price' => $product->Unit_Price,
                'reamingquantity' =>  $updateProduct->numberofpice - $product->quantity,
                'Discount_Value' => $product->Discount_Value,
                'Added_Value' => $product->Added_Value,
                'count' => $i,
                'id' => $product->id
            ];
        }
        $InvoiceData = invoices::find($saleData->invoice_id);

        //return $product;
        $data = [
            "invoicetotal_price" => $InvoiceData->Price - $InvoiceData->discount,
            "invoicetotal_addedvalue" => $InvoiceData->Added_Value,
            "invoicetotal_discount" => $InvoiceData->discount,
            'product' => $allProdctsD,
            "invoice_id" => $saleData->invoice_id
        ];
        return $data;
        return view('products.sales', compact('data'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\invoices  $invoices
     * @return \Illuminate\Http\Response
     */

    public function return_sale(Request $request)
    {
        //
        $avtSaleRate = Avt::find(1);

        app()->setLocale(LaravelLocalization::getCurrentLocale());

        $product = sales::where('invoice_id', $request->invoice_no)->where('save', 1)->get();
        if (count($product) == 0) {
            $message = LaravelLocalization::getCurrentLocale() == 'ar' ? '  لم يتم العثور علي فاتورة بهذة الرقم' : 'No invoice with this number was found';

            session()->flash('notfountreturnproduct', $message);
            $data = [];
            return view('products.salesreturned', compact('data'));
        } else {
            $InvoiceData = invoices::find($request->invoice_no);

            $data = [
                "invoicetotal_price" => $InvoiceData->Price - $InvoiceData->discount,
                "invoicetotal_addedvalue" => ($InvoiceData->Price - $InvoiceData->discount) * $avtSaleRate->AVT,
                "invoicetotal_discount" => $InvoiceData->discount,
                'product' => $product,
                "invoice_id" => $request->invoice_no
            ];
            session()->flash('foundinvoice', '   تم العثور علي فاتورة ');

            return view('products.salesreturned', compact('data'));
        }
    }

    public function update_return_Sale(Request $request)
    {
        //
        app()->setLocale(LaravelLocalization::getCurrentLocale());
        $avtSaleRate = Avt::find(1);
        $returnshabkavalue = 0;
        $saleData = sales::find($request->id);
        $sale = sales::find($request->id);
        //return $saleData;
        $updateProduct = products::find($saleData->product_id);
        $InvoiceData = invoices::find($saleData->invoice_id);

        if ($updateProduct->branchs_id == $InvoiceData->branchs_id) {
            $productdata = products::find($saleData->product_id);
            if ($productdata->parent_inv_itemcard_id != 0) {
                $product = products::find($productdata->parent_inv_itemcard_id);

                $updatedproduct = products::where('id', $productdata->parent_inv_itemcard_id)->update(
                    [
                        'All_QUENTITY' => ($product->QUENTITY_all_Retails + $request->return_quentity) / $product->retail_uom_quntToParent,
                        'QUENTITY' => (int)(($product->QUENTITY_all_Retails + $request->return_quentity) / $product->retail_uom_quntToParent),
                        'QUENTITY_Retail' => ((($product->QUENTITY_all_Retails + $request->return_quentity)) % $product->retail_uom_quntToParent),
                        'QUENTITY_all_Retails' => ($product->QUENTITY_all_Retails + $request->return_quentity),
                    ]
                );
                products::where('id', $saleData->product_id)->update(
                    [
                        'All_QUENTITY' => ($product->QUENTITY_all_Retails +  $request->return_quentity),
                    ]
                );
            } else {
                $product = products::find($saleData->product_id);

                $updatedproduct = products::where('id', $saleData->product_id)->update(
                    [
                        'All_QUENTITY' => ($product->All_QUENTITY +  $request->return_quentity),
                        'QUENTITY' => (int)($product->All_QUENTITY + $request->return_quentity),
                        'QUENTITY_Retail' => ($product->All_QUENTITY + $request->return_quentity) - ((int)($product->All_QUENTITY + $request->return_quentityy)),
                        'QUENTITY_all_Retails' => ($product->All_QUENTITY +  $request->return_quentity) * $product->retail_uom_quntToParent,
                    ]
                );
                products::where('parent_inv_itemcard_id', $saleData->product_id)->update(
                    [
                        'All_QUENTITY' => ($product->All_QUENTITY +  $request->return_quentity) * $product->retail_uom_quntToParent,
                    ]
                );
            }

            $message = LaravelLocalization::getCurrentLocale() == 'ar' ? "تم عملية الاسترجاع بنجاح شكرا" : "The recovery process was successful. Thank you.";
            session()->flash('success', $message);
        } else {
            $mproduct = products::where('branchs_id', $InvoiceData->branchs_id)->where('barcode', $updateProduct->barcode)->first();
            if ($mproduct != null) {


                if ($$mproduct->parent_inv_itemcard_id != 0) {
                    $product = products::find($mproduct->parent_inv_itemcard_id);

                    $updatedproduct = products::where('id', $mproduct->parent_inv_itemcard_id)->update(
                        [
                            'All_QUENTITY' => ($product->QUENTITY_all_Retails + $request->return_quentity) / $product->retail_uom_quntToParent,
                            'QUENTITY' => (int)(($product->QUENTITY_all_Retails + $request->return_quentity) / $product->retail_uom_quntToParent),
                            'QUENTITY_Retail' => ((($product->QUENTITY_all_Retails + $request->return_quentity)) % $product->retail_uom_quntToParent),
                            'QUENTITY_all_Retails' => ($product->QUENTITY_all_Retails + $request->return_quentity),
                        ]
                    );
                    products::where('id', $$mproduct->id)->update(
                        [
                            'All_QUENTITY' => ($product->QUENTITY_all_Retails +  $request->return_quentity),
                        ]
                    );
                } else {
                    $product = products::find($saleData->product_id);

                    $updatedproduct = products::where('id', $saleData->product_id)->update(
                        [
                            'All_QUENTITY' => ($product->All_QUENTITY +  $request->return_quentity),
                            'QUENTITY' => (int)($product->All_QUENTITY + $request->return_quentity),
                            'QUENTITY_Retail' => ($product->All_QUENTITY + $request->return_quentity) - ((int)($product->All_QUENTITY + $request->return_quentityy)),
                            'QUENTITY_all_Retails' => ($product->All_QUENTITY +  $request->return_quentity) * $product->retail_uom_quntToParent,
                        ]
                    );
                    products::where('parent_inv_itemcard_id', $saleData->product_id)->update(
                        [
                            'All_QUENTITY' => ($product->All_QUENTITY +  $request->return_quentity) * $product->retail_uom_quntToParent,
                        ]
                    );
                }


                $message = LaravelLocalization::getCurrentLocale() == 'ar' ? "تم عملية الاسترجاع بنجاح شكرا" : "The recovery process was successful. Thank you.";
                session()->flash('success', $message);
            } else {
                $product = sales::where('invoice_id', $saleData->invoice_id)->get();
                $InvoiceData = invoices::where('id',  $saleData->invoice_id)->first();
                //return $product;
                $saleData = sales::find($request->id);

                $productrecive = products::find($saleData->product_id);
                $parentproduct = products::find($productrecive->parent_inv_itemcard_id);

                if ($productrecive->parent_inv_itemcard_id != 0) {



                    $newproductParent = products::create(
                        [
                            'item_code' => $parentproduct->item_code,
                            'barcode' =>  $parentproduct->barcode,
                            'name' =>  $parentproduct->name,
                            'item_type' => $parentproduct->item_type,
                            'inv_itemcard_categories_id' =>  $parentproduct->inv_itemcard_categories_id,
                            'parent_inv_itemcard_id' => 0,
                            'does_has_retailunit' => $parentproduct->does_has_retailunit,
                            'retail_uom_id' =>  $parentproduct->retail_uom_id,
                            'uom_id' =>  $parentproduct->uom_id,
                            'retail_uom_quntToParent' =>  $parentproduct->retail_uom_quntToParent,
                            'added_by' => Auth()->user()->id,
                            'updated_by' => Auth()->user()->id,
                            'active' =>  $parentproduct->active,
                            'date' => \Carbon\Carbon::now()->addHours(3),
                            'branchs_id' => Auth()->user()->branchs_id,
                            'price' =>  $parentproduct->price,
                            'price_retail' => $parentproduct->price_retail,
                            'photo' =>  $parentproduct->photo,
                            'has_fixced_price' =>  $parentproduct->has_fixced_price,
                            'created_at' => \Carbon\Carbon::now()->addHours(3),
                            'Product_Location' => 'Transfer',
                            'minmum_quantity_stock_alart' =>  $parentproduct->minmum_quantity_stock_alart,
                        ]
                    );
                    $newproducts = products::create(
                        [
                            'item_code' => $productrecive->item_code,
                            'barcode' => $productrecive->barcode,
                            'name' => $productrecive->name,
                            'item_type' => $productrecive->item_type,
                            'inv_itemcard_categories_id' => $productrecive->inv_itemcard_categories_id,
                            'parent_inv_itemcard_id' => $newproductParent->id,
                            'does_has_retailunit' => $productrecive->does_has_retailunit,
                            'retail_uom_id' => $productrecive->retail_uom_id,
                            'uom_id' => $productrecive->uom_id,
                            'retail_uom_quntToParent' => $productrecive->retail_uom_quntToParent,
                            'added_by' => Auth()->user()->id,
                            'updated_by' => Auth()->user()->id,
                            'active' => $productrecive->active,
                            'date' => \Carbon\Carbon::now()->addHours(3),
                            'branchs_id' => Auth()->user()->branchs_id,
                            'price' => $productrecive->price,
                            'price_retail' => $productrecive->price_retail,
                            'photo' => $productrecive->photo,
                            // 'cost_price' => $productrecive->cost_price,
                            // 'cost_price_retail' => $productrecive->cost_price_retail,
                            'has_fixced_price' => $productrecive->has_fixced_price,
                            'created_at' => \Carbon\Carbon::now()->addHours(3),
                            'Product_Location' => 'Transfer',
                            'minmum_quantity_stock_alart' => $productrecive->minmum_quantity_stock_alart,
                        ]
                    );
                } else {

                    $newproductParent = products::create(
                        [
                            'item_code' => $parentproduct->item_code,
                            'barcode' =>  $parentproduct->barcode,
                            'name' =>  $parentproduct->name,
                            'item_type' => $parentproduct->item_type,
                            'inv_itemcard_categories_id' =>  $parentproduct->inv_itemcard_categories_id,
                            'parent_inv_itemcard_id' => 0,
                            'does_has_retailunit' => $parentproduct->does_has_retailunit,
                            'retail_uom_id' =>  $parentproduct->retail_uom_id,
                            'uom_id' =>  $parentproduct->uom_id,
                            'retail_uom_quntToParent' =>  $parentproduct->retail_uom_quntToParent,
                            'added_by' => Auth()->user()->id,
                            'updated_by' => Auth()->user()->id,
                            'active' =>  $parentproduct->active,
                            'date' => \Carbon\Carbon::now()->addHours(3),
                            'branchs_id' => Auth()->user()->branchs_id,
                            'price' =>  $parentproduct->price,
                            'price_retail' => $parentproduct->price_retail,
                            'photo' =>  $parentproduct->photo,
                            'has_fixced_price' =>  $parentproduct->has_fixced_price,
                            'created_at' => \Carbon\Carbon::now()->addHours(3),
                            'Product_Location' => 'Transfer',
                            'minmum_quantity_stock_alart' =>  $parentproduct->minmum_quantity_stock_alart,
                        ]
                    );
                }



                if ($productrecive->parent_inv_itemcard_id != 0) {

                    $updatedproductdata = products::where('id', $newproductParent->id)->update(
                        [
                            'All_QUENTITY' => ($request->return_quentity) / $newproductParent->retail_uom_quntToParent,
                            'QUENTITY' => (int)(($request->return_quentity) / $newproductParent->retail_uom_quntToParent),
                            'QUENTITY_Retail' => ($request->return_quentity) - ((int)($request->return_quentity)),
                            'QUENTITY_all_Retails' => ($request->return_quentity),
                            "cost_price" =>  $parentproduct->cost_price * $newproductParent->retail_uom_quntToParent,
                            "cost_price_retail" =>  $parentproduct->cost_price_retail,
                        ]
                    );
                    $updatedproduct = products::where('id', $newproducts->id)->update(
                        [
                            'All_QUENTITY' => ($request->return_quentity),
                            'QUENTITY' => 0,
                            'QUENTITY_Retail' => 0,
                            'QUENTITY_all_Retails' => 0,
                            "cost_price" =>  $productrecive->cost_price * $newproductParent->retail_uom_quntToParent,
                            "cost_price_retail" =>  $productrecive->cost_price_retail,
                        ]
                    );
                } else {
                    $updatedproduct = products::where('id', $newproducts->id)->update(
                        [
                            'All_QUENTITY' => ($request->return_quentity),
                            'QUENTITY' => (int)($request->return_quentity),
                            'QUENTITY_Retail' => ($request->return_quentity) - ((int)($request->return_quentity)),
                            'QUENTITY_all_Retails' => ($request->return_quentity) * $newproductParent->retail_uom_quntToParent,
                            "cost_price" =>  $parentproduct->cost_price,
                            "cost_price_retail" =>  $parentproduct->cost_price_retail / $newproductParent->retail_uom_quntToParent,
                        ]
                    );
                }
                $productname = $updateProduct->product_name;

                $message = LaravelLocalization::getCurrentLocale() == 'ar' ?  "  تم عملية الاسترجاع. المنتج المسترجع غير مسجل لديكم مسبقا تم تسجيل  " . $productname . "  بنفس رقم المنتج  شكرا  " : "The product is not previously registered. It has been registered with a name " . $productname . " and a product number, such as the number ";
                // products::where('id', $newproducts->id)->Update([
                //     'numberofpice' =>  $request->return_quentity,
                // ]);
                session()->flash('createnewproduct', $message);
            }
        }



        $saleData = sales::find($request->id);



        $finddsalefordiscount = sales::find($request->id);


        $invicedis = invoices::find($saleData->invoice_id);
        if (count(sales::where('invoice_id', $saleData->invoice_id)->where('quantity', '!=', 0)->get()) == 1) {
            $return_sales = return_sales::create([
                'product_id' => $saleData->product_id,
                'invoice_id' => $saleData->invoice_id,
                'branch_id' => Auth()->User()->branch->id,
                'return_Added_Value' => $saleData->Added_Value,
                'return_Unit_Price' => $saleData->Unit_Price,
                'discountvalue' => $finddsalefordiscount->Discount_Value,
                'discountoninvoice' => $invicedis->discount - $finddsalefordiscount->Discount_Value,
                'returnshabkavalue' => $returnshabkavalue ?? 0,
                'return_quantity' => $request->return_quentity,
                'created_at' => \Carbon\Carbon::now()->addHours(3),

            ]);
        } else {
            $return_sales = return_sales::create([
                'product_id' => $saleData->product_id,
                'invoice_id' => $saleData->invoice_id,
                'branch_id' => Auth()->User()->branch->id,
                'return_Added_Value' => $saleData->Added_Value,
                'return_Unit_Price' => $saleData->Unit_Price,
                'discountvalue' => $saleData->Discount_Value,
                'returnshabkavalue' => $returnshabkavalue,
                'return_quantity' => $request->return_quentity,
                'created_at' => \Carbon\Carbon::now()->addHours(3),

            ]);
        }




        sales::find($request->id)->update(
            [
                'quantity' => $finddsalefordiscount->quantity - $request->return_quentity,
                'quantityreturn' => $finddsalefordiscount->quantityreturn + $request->return_quentity,
                'Discount_Value' => 0
            ]
        );





        //  return  $return_sales;
        $Invoice = invoices::where('id',  $saleData->invoice_id)->Update(
            [
                'Price' => round($InvoiceData->Price - (($saleData->Unit_Price * $request->return_quentity) - $saleData->Discount_Value), 2),
                'Added_Value' => round($InvoiceData->Added_Value - ((($saleData->Unit_Price * $request->return_quentity) - $saleData->Discount_Value) * $avtSaleRate->AVT), 2),
                'Number_of_Quantity' => $InvoiceData->Number_of_Quantity - $request->return_quentity,
                'updated_at' => \Carbon\Carbon::now()->addHours(3),
            ]
        );
        $Invoice = invoices::where('id',  $saleData->invoice_id)->Update(
            [
                'discountOnInvoice' => $InvoiceData->discount - $saleData->Discount_Value,
                'discount' => $InvoiceData->discount - $saleData->Discount_Value,
            ]
        );
        $InvoiceData = invoices::find($saleData->invoice_id);
        $customerdata = customers::find($InvoiceData->customer_id);

        if ($InvoiceData->Number_of_Quantity == 0) {
            $Invoice = invoices::where('id',  $saleData->invoice_id)->Update(
                [
                    'Price' => 0,
                    'Added_Value' => 0,
                    'discount' => 0,

                ]
            );

            if ($InvoiceData->Pay == "Credit") {
                $updateCustomer = customers::where('id', $InvoiceData->customer_id)->update(
                    [
                        'Balance' => $customerdata->Balance - ((($request->return_quentity * $saleData->Unit_Price) -  $InvoiceData->discountOnInvoice) + ((($request->return_quentity * $saleData->Unit_Price) -  $InvoiceData->discountOnInvoice) * $avtSaleRate->AVT)),
                        'updated_at' => \Carbon\Carbon::now()->addHours(3),

                    ]
                );
            }
        } else {
            if ($InvoiceData->Pay == "Credit") {
                $updateCustomer = customers::where('id', $InvoiceData->customer_id)->update(
                    [
                        'Balance' => $customerdata->Balance - ((($request->return_quentity * $saleData->Unit_Price) -  $saleData->Discount_Value) + ((($request->return_quentity * $saleData->Unit_Price) -  $saleData->Discount_Value) * $avtSaleRate->AVT)),
                        'updated_at' => \Carbon\Carbon::now()->addHours(3),

                    ]
                );
            }
        }
        $InvoiceData = invoices::find($saleData->invoice_id);

        $productconvert = [];
        $product = sales::where('invoice_id', $saleData->invoice_id)->get();
        $InvoiceData = invoices::where('id',  $saleData->invoice_id)->first();
        //return $product;
        $i = 0;
        foreach ($product as $item) {
            $i++;
            if ($item->quantity > 0) {
                $productconvert[] = [
                    'count' => $i,
                    'Product_Code' => $item->productData->barcode,
                    'product_name' => $item->productData->name,
                    'quantity' => $item->quantity,
                    'Unit_Price' => $item->Unit_Price,
                    'Discount_Value' => $item->Discount_Value,
                    "id" => $item->id


                ];
            }
        }
        $data = [
            "invoicetotal_price" => $InvoiceData->Price - $InvoiceData->discount,
            "invoicetotal_addedvalue" => round(($InvoiceData->Price - $InvoiceData->discount) * $avtSaleRate->AVT, 2),
            "invoicetotal_discount" => $InvoiceData->discount,
            'total' => round(($InvoiceData->Price - $InvoiceData->discount) + ($InvoiceData->Price - $InvoiceData->discount) * $avtSaleRate->AVT, 2),
            'product' => $productconvert,
            "invoice_id" => $saleData->invoice_id,
            "message" => $message
        ];
        return $data;
    }






    public function update(Request $request)
    {
        //
        //   return $request;
        app()->setLocale(LaravelLocalization::getCurrentLocale());
        $avtSaleRate = Avt::find(1);

        $saleData = sales::find($request->id);
        //return $saleData;
        $updateProduct = products::find($saleData->product_id);

        products::where('id', $saleData->product_id)->Update([
            'numberofpice' => $updateProduct->numberofpice + $request->return_quentity,
            'numberـofـsales' => $updateProduct->numberـofـsales - $request->return_quentity
        ]);


        $InvoiceData = invoices::find($saleData->invoice_id);
        if ($InvoiceData->Pay == "Credit") {
            $customerdata = customers::find($InvoiceData->customer_id);
            // return ($customerdata->Balance-(($request->return_quentity*$saleData->Unit_Price)+($request->return_quentity*$saleData->Added_Value)));
            $updateCustomer = customers::where('id', $InvoiceData->customer_id)->update(
                [
                    'Balance' => $customerdata->Balance - ((($request->return_quentity * $saleData->Unit_Price) - $saleData->Discount_Value) + ((($request->return_quentity * $saleData->Unit_Price) - $saleData->Discount_Value) * $avtSaleRate->AVT)),
                    'updated_at' => \Carbon\Carbon::now()->addHours(3),

                ]
            );
        }
        if ($InvoiceData->Number_of_Quantity == 0) {
            $Invoice = invoices::where('id',  $saleData->invoice_id)->Update(
                [
                    'discount' => 0,
                ]
            );
        } else {
            $Invoice = invoices::where('id',  $saleData->invoice_id)->Update(
                [
                    'discount' => $InvoiceData->discount - $saleData->Discount_Value,
                ]
            );
        }

        $return_sales = return_sales::create([
            'product_id' => $saleData->product_id,
            'invoice_id' => $saleData->invoice_id,
            'branch_id' => Auth()->User()->branch->id,
            'return_Added_Value' => $saleData->Added_Value,
            'return_Unit_Price' => $saleData->Unit_Price,
            'return_quantity' => $request->return_quentity,
            'created_at' => \Carbon\Carbon::now()->addHours(3),

        ]);
        //  return  $return_sales;
        $Invoice = invoices::where('id',  $saleData->invoice_id)->Update(
            [

                'Price' => round($InvoiceData->Price - (($saleData->Unit_Price * $request->return_quentity)), 2),
                'Added_Value' => round($InvoiceData->Added_Value - ((($saleData->Unit_Price * $request->return_quentity)) * $avtSaleRate->AVT), 2),
                'Number_of_Quantity' => $InvoiceData->Number_of_Quantity - $request->return_quentity,
                'updated_at' => \Carbon\Carbon::now()->addHours(3),
            ]
        );
        $productSales = sales::where('id', $request->id)->update(
            [
                'quantity' => $saleData->quantity - $request->return_quentity,
                'updated_at' => \Carbon\Carbon::now()->addHours(3),
                'Discount_Value' => 0
            ]
        );
        $InvoiceData = invoices::find($saleData->invoice_id);

        if ($InvoiceData->Number_of_Quantity == 0 && $InvoiceData->Pay == "Credit") {
            $InvoiceData = invoices::find($saleData->invoice_id);

            $updateCustomer = customers::where('id', $InvoiceData->customer_id)->update(
                [
                    'Balance' => $customerdata->Balance - $InvoiceData->discount,
                    'updated_at' => \Carbon\Carbon::now()->addHours(3),

                ]
            );
        }
        $product = sales::where('invoice_id', $saleData->invoice_id)->get();
        //return $product;


        $InvoiceData = invoices::find($saleData->invoice_id);

        $data = [
            "invoicetotal_price" => $InvoiceData->Price - $InvoiceData->discount,
            "invoicetotal_addedvalue" => ($InvoiceData->Price - $InvoiceData->discount) * $avtSaleRate->AVT,
            "invoicetotal_discount" => $InvoiceData->discount,
            'product' => $product,
            "invoice_id" => $saleData->invoice_id
        ];
        return $data;
        return view('products.salesreturned', compact('data'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\invoices  $invoices
     * @return \Illuminate\Http\Response
     */

    public function makeTotalDiscont($invoiceId, $discountValue)
    {
        $avtSaleRate = Avt::find(1);


        $Invoices = invoices::where('id',  $invoiceId)->first();
        $totalafterdiscount = (($Invoices->Price - $Invoices->discount) + round((($Invoices->Price - $Invoices->discount) * $avtSaleRate->AVT), 2)) - $discountValue;
        $pricenew = round($totalafterdiscount * 100 / 115, 2);
        $discountvalue = ($Invoices->Price - $Invoices->discount) - $pricenew;
        if ($discountvalue)
            invoices::where('id',  $invoiceId)->update([
                'discount' => $Invoices->discount + $discountvalue
            ]);
        $Invoices = invoices::where('id',  $invoiceId)->first();
        return [
            'totalprice' => round(($Invoices->Price - $Invoices->discount), 2),
            'addedvalueafterdiscount' => round((($Invoices->Price - $Invoices->discount) * $avtSaleRate->AVT), 2),
            "discount" => $Invoices->discount
        ];
    }


    public function cancelInvoiceDiscont($invoiceId)
    {
        $avtSaleRate = Avt::find(1);

        $Invoices = invoices::where('id',  $invoiceId)->first();


        $discountonInvoice = $Invoices->discount - $Invoices->discountOnProduct;

        invoices::where('id',  $invoiceId)->update([
            'discount' => $Invoices->discount - $discountonInvoice,
        ]);
        $Invoices = invoices::where('id',  $invoiceId)->first();

        return [
            'totalprice' => round(($Invoices->Price - $Invoices->discount), 2),
            'addedvalueafterdiscount' => round((($Invoices->Price - $Invoices->discount) * $avtSaleRate->AVT), 2),
            "discount" => $Invoices->discount
        ];
    }

    public function Receipt(Request $request)
    {
        //
        $avtSaleRate = Avt::find(1);

        app()->setLocale(LaravelLocalization::getCurrentLocale());

        $updateProduct = products::find($request->productNo);
        // return $updateProduct;
        if ($updateProduct->numberofpice >= 1) {
            products::where('id', $request->productNo)->Update([
                'numberofpice' => $updateProduct->numberofpice - $request->quantity,
            ]);
            $invoiceNumber = $request->invoice_number;
            if ($request->invoice_number == null) {
                $Invoice = invoices::create(
                    [
                        'customer_id' => $request->clientnamesearch ?? 1,
                        'user_id' => Auth()->user()->id,
                        'Price' => $request->product_price - $request->product_price_after_dis,
                        'Added_Value' => ($request->product_price - $request->product_price_after_dis) * $avtSaleRate->AVT,
                        'Pay' => $request->pay,
                        'Number_of_Quantity' => $request->quantity,
                        'created_at' => \Carbon\Carbon::now()->addHours(3),
                        'updated_at' => \Carbon\Carbon::now()->addHours(3),
                    ]
                );
                $invoiceNumber = $Invoice->id;
            } else {
                $InvoiceData = invoices::find($invoiceNumber);
                $Invoice = invoices::where('id',  $invoiceNumber)->Update(
                    [

                        'Price' => round($InvoiceData->Price + ($request->product_price - $request->product_price_after_dis), 2),
                        'Added_Value' => round(($InvoiceData->Added_Value + (($request->product_price - $request->product_price_after_dis) * $avtSaleRate->AVT)), 2),
                        'Number_of_Quantity' => $InvoiceData->Number_of_Quantity + $request->quantity,
                        'updated_at' => \Carbon\Carbon::now()->addHours(3),
                    ]
                );
            }
            $productSales = sales::create(
                [
                    'product_id' => $request->productNo,
                    'invoice_id' => $invoiceNumber,
                    'Discount_Value' => $request->product_price_after_dis,
                    'Added_Value' => ($request->product_price - $request->product_price_after_dis) * $avtSaleRate->AVT,
                    'Unit_Price' => $request->product_price - $request->product_price_after_dis,
                    'quantity' => $request->quantity,
                    'branch_id' => Auth()->User()->branch->id,

                    'created_at' => \Carbon\Carbon::now()->addHours(3),
                ]
            );
        } else {
            $message = LaravelLocalization::getCurrentLocale() == 'ar' ? 'عدم وجود مخزون من هذه المنتج' : 'No stock of this product';

            session()->flash('delete', $message);
            $data = [
                "invoice_id" => null
            ];

            return view('products.Receipt', compact('data'));
        }
        $product = sales::where('invoice_id', $invoiceNumber)->get();
        //return $product;
        $data = [
            'product' => $product,
            "invoice_id" => $invoiceNumber
        ];

        return view('products.Receipt', compact('data'));
    }
}
