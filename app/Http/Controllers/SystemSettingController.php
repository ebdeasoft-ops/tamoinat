<?php

namespace App\Http\Controllers;


use App\Models\system_setting;
use App\Models\settings;
use App\Helpers\General;
use App\Services\Zatca\OnBoarding;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\products;
use Illuminate\Support\Facades\Http;

use Mcamara\LaravelLocalization\Facades\LaravelLocalization as LaravelLocalization;

class SystemSettingController extends Controller
{

    public function CONNECT_TO_HUNGER_SETTING()
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());
        $data = settings::find(1);
        return view('system_setting.CONNECT_TO_HUNGER_SETTING', compact('data'));          //
    }


    function connect_start_hunger(Request $request)
    {

        if ($request->typeconnect == 1) {
            settings::find(id: 1)->update([
                'TOKEN' => $request->TOKEN,
                'sendbox' => 0,
                'production' => 1,
            ]);


        } else {


            settings::find(id: 1)->update([
                'sendbox' => 1,
                'token_sendbox' => $request->TOKEN,
                'production' => 0,
            ]);
        }
        session()->flash('RegisterDone', 'تم تخزين التوكن هنجر ستيسن بنجاح شكرا     The token is stored Hunger statuion has been completed successfully Thank you');
        $products = products::get();
        $data = settings::find(1);



        //return $response->json();
        return view('system_setting.CONNECT_TO_HUNGER_SETTING', compact('data'));



    }


    public function hangerStationTestFlow()
    {
        $data = settings::find(1);
        $baseUrl = $data->production == 1 ? env('HANGER_API_url') : env('HANGER_API_sendbox_url');
        $token = $data->production == 1 ? $data->TOKEN : $data->token_sendbox;
        // 1️⃣ Create Product
        $productResponse = Http::withToken($token)->post("$baseUrl/products", [
            "sku" => "TEST-001",
            "name" => "Test Product",
            "description" => "Full integration test",
            "price" => 150,
            "currency" => "SAR",
            "quantity" => 50,
            "weight" => 0.5,
            "length" => 30,
            "width" => 20,
            "height" => 5,
            "category" => "Apparel",
            "cod_available" => true
        ]);

        if (!$productResponse->successful()) {
            return [
                "step" => "create_product",
                "error" => $productResponse->body()
            ];
        }

        // 2️⃣ Update Inventory
        $inventoryResponse = Http::withToken($token)->put("$baseUrl/inventory", [
            "sku" => "TEST-001",
            "quantity" => 100
        ]);

        if (!$inventoryResponse->successful()) {
            return [
                "step" => "update_inventory",
                "error" => $inventoryResponse->body()
            ];
        }

        // 3️⃣ Create Order
        $orderResponse = Http::withToken($token)->post("$baseUrl/orders", [
            "order_id" => "ORD-TEST-1001",
            "customer" => [
                "name" => "Test User",
                "phone" => "+966500000000",
                "city" => "Riyadh",
                "address" => "King Fahd Road"
            ],
            "items" => [
                [
                    "sku" => "TEST-001",
                    "quantity" => 2,
                    "price" => 150
                ]
            ],
            "payment_method" => "COD"
        ]);

        if (!$orderResponse->successful()) {
            return [
                "step" => "create_order",
                "error" => $orderResponse->body()
            ];
        }

        // 4️⃣ Get Single Order
        $getOrderResponse = Http::withToken($token)
            ->get("$baseUrl/orders/ORD-TEST-1001");

        // 5️⃣ Get Orders List
        $getOrdersResponse = Http::withToken($token)
            ->get("$baseUrl/orders");

        return [
            "create_product" => $productResponse->json(),
            "update_inventory" => $inventoryResponse->json(),
            "create_order" => $orderResponse->json(),
            "get_order" => $getOrderResponse->json(),
            "get_orders" => $getOrdersResponse->json()
        ];
    }



    public function sendOrder()
    {
        $data = settings::find(1);
        $response = Http::withToken($data->TOKEN)
            ->post('https://api.hangerstation.sa/api/v1/orders', [
                "order_id" => "ORD-1001",
                "customer" => [
                    "name" => "Ahmed Ali",
                    "phone" => "+966500000000",
                    "city" => "Riyadh",
                    "address" => "King Fahd Road"
                ],
                "items" => [
                    [
                        "sku" => 15,
                        "quantity" => 1,
                        "price" => 4
                    ]
                ],
                "payment_method" => "COD"
            ]);

        return $response->json();
    }

    public function getOrders()
    {
        $data = settings::find(1);

        $response = Http::withToken($data->TOKEN)
            ->get('https://api.hangerstation.sa/api/v1/orders');

        if ($response->successful()) {
            return $response->json();
        }

        return response()->json([
            "error" => true,
            "message" => $response->body()
        ], $response->status());
    }














    function uploadImage($folder, $image)
    {
        $extension = strtolower($image->extension());
        $filename = time() . rand(100, 999) . '.' . $extension;
        $image->getClientOriginalName = $filename;
        $image->move($folder, $filename);
        return $filename;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());
        $data = system_setting::find(1);
        return view('system_setting.system_data', compact('data'));          //
    }




    public function onbourding()
    {
        app()->setLocale(LaravelLocalization::getCurrentLocale());
        $data = system_setting::find(1);
        return view('system_setting.onbourding', compact('data'));          //
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        app()->setLocale(LaravelLocalization::getCurrentLocale());
        $data = settings::find(1)->update([

            'invoice_type' => $request->invoicetype,
            'is_production' => $request->typeconnect,
            'otp' => $request->otp,

        ]);


        $setting = settings::find(1);

        //$setting->is_production?'core':'simulation'
        // first csid;
        $response = (new OnBoarding())
            ->setZatcaEnv($setting->is_production ? 'core' : 'simulation')
            ->setZatcaLang('en')
            ->setEmailAddress($setting->email_address)
            ->setCommonName($setting->common_name)
            ->setCountryCode('SA')
            ->setOrganizationUnitName($setting->organization_unit_name)
            ->setOrganizationName($setting->organization_name)
            ->setEgsSerialNumber('1-SDSA|2-FGDS|3-SDFG')
            ->setVatNumber($setting->trn)
            ->setInvoiceType($setting->invoice_type)
            ->setRegisteredAddress($setting->registered_address)
            ->setAuthOtp($setting->otp)
            ->setBusinessCategory($setting->business_category)
            ->getAuthorization();

        // return $response['message'];
        if ($response['success']) {
            $data = $response['data'];
            settings::find(1)->update([
                // from this line value assigned here this mean column name in database
                'cnf' => $data['configData'],
                'private_key' => $data['privateKey'],
                'public_key' => $data['publicKey'],
                'csr_request' => $data['csrKey'],
                'certificate' => $data['complianceCertificate'],
                'secret' => $data['complianceSecret'],
                'csid' => $data['complianceRequestID'],
                'production_certificate' => $data['productionCertificate'],
                'production_secret' => $data['productionCertificateSecret'],
                'production_csid' => $data['productionCertificateRequestID']
            ]);

            session()->flash('RegisterDone', 'تم الربط مع الزكاة بنجاح شكرا     The link with Zakat has been completed successfully Thank you');
            return view('system_setting.onbourding', compact('data'));
        } else {
            $data = system_setting::find(1);
            session()->flash('ERROR', $response['message']);

            return view('system_setting.onbourding', compact('data'));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\system_setting  $system_setting
     * @return \Illuminate\Http\Response
     */
    public function show(system_setting $system_setting)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\system_setting  $system_setting
     * @return \Illuminate\Http\Response
     */
    public function edit(system_setting $system_setting)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\system_setting  $system_setting
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {

        if ($request->has('logo')) {
            $request->validate([
                'logo' => 'required|mimes:png,jpg,jpeg|max:2000',
            ]);
            $the_file_path = $this->uploadImage('assets//img//brand', $request->logo);
        }

        $data = settings::find(1)->update([
            'name' => $request->camName_ar,
            'organization_name' => $request->camName_ar,
            'organization_unit_name' => $request->camName_ar,
            'common_name' => $request->camName_ar,
            'building_number' => $request->buildnumber,
            'street_name' => $request->StreetName,
            'registered_address' => $request->StreetName,
            'region' => $request->region,
            'city' => $request->city,
            'trn' => $request->Tax,
            'crn' => $request->SR,
            'mobile' => $request->TEL,
            'plot_identification' => $request->scander_number,
            'postal_number' => $request->postcode,
            'business_category' => $request->descriptionarbic,
            'email_address' => $request->email ?? 'ebdeasoft@gmail.com',
        ]);



        $data = system_setting::find(1);
        system_setting::find(1)->update([
            'name_ar' => $request->camName_ar ?? $data->name_ar,
            'name_en' => $request->camName_en ?? $data->name_en,
            'SR' => $request->SR ?? $data->SR,
            'Tax' => $request->Tax ?? $data->Tax,
            'logo' => $the_file_path ?? $data->logo,
            'logo' => $the_file_path ?? $data->logo,
            'discount_on_invoice' => $request->discount_on_invoice ?? 100,
            'address_en' => $request->address_en ?? $data->address_en,
            'address_ar' => $request->address_ar,
            'deliveryCost' => $request->deliveryCost ?? $data->deliveryCost,
            'serviceCost' => $request->serviceCost ?? $data->serviceCost,
            'descriptionarbic' => $request->descriptionarbic,
            'descriptionenglish' => $request->descriptionenglish,
            'bank_acount_iban' => $request->bank_acount_iban,
            'bank_acount_number' => $request->bank_acount_number,
            'bankname' => $request->bankname,
        ]);
        app()->setLocale(LaravelLocalization::getCurrentLocale());
        $data = system_setting::find(1);
        return view('system_setting.system_data', compact('data'));
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\system_setting  $system_setting
     * @return \Illuminate\Http\Response
     */
    public function destroy(system_setting $system_setting)
    {
        //
    }
}
