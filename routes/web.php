<?php

use App\Http\Controllers\SupllierController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\App;
use App\Http\Controllers\CustomersController;
use App\Http\Controllers\ProductsDamageController;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization as LaravelLocalization;
use  App\Http\Controllers\ProductsController;
use  App\Http\Controllers\AdminController;
use  App\Http\Controllers\InvoicesController;
use App\Http\Controllers\AcountesController;
use App\Http\Controllers\CredittransactionsController;
use App\Http\Controllers\ExpensesController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\BranchsController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use Spatie\Permission\Models\role_has_permissions;
use App\Http\Controllers\SupprocessesController;
use App\Http\Controllers\InvItemcardCategoriesController;
use App\Http\Controllers\AvtController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\ProductMovementAnotherBranchController;
use App\Http\Controllers\TransferMoneyToMainbranchController;
use App\Http\Controllers\DeliveryProductToTheCustomerController;
use App\Http\Controllers\UnitsController;
use App\Http\Controllers\InvItemCardController;
use App\Http\Controllers\SystemSettingController;
use App\Http\Controllers\LoansController;

use  App\Models\system_setting;
use  App\Models\User;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
// Route::get('/{page}', [App\Http\Controllers\AdminController::class,'index']);

//products



$system_setting=system_setting::find(1);
define('PAGINATION_COUNT',20);
define('serviceCost',$system_setting->serviceCost);


define('Namear',$system_setting->name_ar);
define('describtionar',$system_setting->descriptionarbic);
define('STar',' س . ت  :'.$system_setting->SR);
define('Taxar','  الرقم الضريبي : '.$system_setting->Tax);
define('TaxQrCode',$system_setting->Tax);
define('sallerQrCode',$system_setting->name_ar);


define('Nameen',$system_setting->name_en);
define('describtionen',$system_setting->descriptionenglish);
define('STen','  C.R : '.$system_setting->SR);
define('Taxen','VAT Number : '.$system_setting->Tax);
define('addressar',$system_setting->address_ar);
define('addressen',$system_setting->address_en);
define('camplogo',$system_setting->logo);





Route::post('/posttestajax', [BranchsController::class, 'posttestajax']);





    /*      ═══════ ೋღ   start  LINK ZATCA   ღೋ ═══════             */
    Route::get('dwonloadxml/{id}', [InvoicesController::class, 'dwonloadxml']);
    Route::get('sent_to_zatca_return_items/{id}', [InvoicesController::class, 'sent_to_zatca_return_items']);
    Route::get('sent_to_zatca/{id}', [InvoicesController::class, 'sent_to_zatca']);
    Route::get('sendzatca_fromsale/{id}', [InvoicesController::class, 'sendzatca_fromsale']);
    


/*      ═══════ ೋღ  end LINK ZATCA ღೋ ═══════              */




/*      ═══════ ೋღ   start   LoansController    ღೋ ═══════             */
Route::get('/Loans', [LoansController::class, 'index']);
Route::get('/delete_Loans/{id}', [LoansController::class, 'destroy']);
Route::post('/Loans', [LoansController::class, 'store']);

Route::post('/update_Loans', [LoansController::class, 'edit']);




/*      ═══════ ೋღ  end LoansController   ღೋ ═══════              */

    /*      ═══════ ೋღ   start  System setting    ღೋ ═══════             */
Route::get('/systemSetting', [SystemSettingController::class, 'index']);


///    new
Route::post('/connect_start_hunger', [SystemSettingController::class, 'connect_start_hunger']);
Route::get('CONNECT_TO_HUNGER_SETTING',  [SystemSettingController::class, 'CONNECT_TO_HUNGER_SETTING']);
Route::get('hangerStationTestFlow',  [SystemSettingController::class, 'hangerStationTestFlow']);

////      end new

Route::get('/onbourding', [SystemSettingController::class, 'onbourding']);
Route::post('/onbourding', [SystemSettingController::class, 'store']);
Route::post('/updateCamData', [SystemSettingController::class, 'update']);





/*      ═══════ ೋღ  end System setting  ღೋ ═══════              */

/*      ═══════ ೋღ   start  Item Card   ღೋ ═══════             */
Route::get('/itemcard/index', [InvItemCardController::class, 'index'])->name('admin.itemcard.index');
Route::get('/itemcard/create', [InvItemCardController::class, 'create'])->name('admin.itemcard.create');
Route::post('/itemcard/store', [InvItemCardController::class, 'store'])->name('admin.itemcard.store');
Route::get('/itemcard/edit/{id}', [InvItemCardController::class, 'edit'])->name('admin.itemcard.edit');
Route::post('/itemcard/update/{id}', [InvItemCardController::class, 'update'])->name('admin.itemcard.update');
Route::get('/itemcard/delete/{id}', [InvItemCardController::class, 'delete'])->name('admin.itemcard.delete');
Route::post('/itemcard/ajax_search', [InvItemCardController::class, 'ajax_search'])->name('admin.itemcard.ajax_search');
Route::get('/itemcard/show/{id}', [InvItemCardController::class, 'show'])->name('admin.itemcard.show');
Route::post('/itemcard/ajax_search_movements', [InvItemCardController::class, 'ajax_search_movements'])->name('admin.itemcard.ajax_search_movements');
Route::post('/itemcard/ajax_check_barcode', [InvItemCardController::class, 'ajax_check_barcode'])->name('admin.itemcard.ajax_check_barcode');
Route::post('/itemcard/ajax_check_name', [InvItemCardController::class, 'ajax_check_name'])->name('admin.itemcard.ajax_check_name');
Route::get('/itemcard/generate_barcode/{id}', [InvItemCardController::class, 'generate_barcode'])->name('admin.itemcard.generate_barcode');


/*      ═══════ ೋღ  end Item Card  ღೋ ═══════              */


Route::get('/itemcards/search', [InvItemCardController::class, 'search'])
     ->name('itemcards.search');
 
Route::get('/', function () {

    return view('auth.login');
});
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {


    //role


    Route::group(['middleware' => ['auth']], function () {

        Route::resource('roles', RoleController::class);

        Route::resource('users', UserController::class);
    });


    //end role
Route::post('save_invoice_sale',  [InvoicesController::class, 'save_invoice_sale']);


  Route::get('searchChooseProductpaginatenewSale_new/{searchtext}/{branch_id}/{currentrow}', [ProductsController::class, 'searchChooseProductpaginatenewSale_new']);
  Route::get('ChooseProductpaginatenewSale_new/{branch_id}/{currentrow}', [ProductsController::class, 'ChooseProductpaginatenewSale_new']);
  Route::get('/getByCodenew/{barcode}',[ ProductsController::class,'getByCodenew'])->name('getByCodenew');


    //unit

    Route::get('units', [UnitsController::class, 'index'])->name('units');
    Route::get('update/{id}', [UnitsController::class, 'update'])->name('unit.go.update');
    Route::get('createunit', [UnitsController::class, 'create'])->name('unit.create');
    Route::post('storeUnit', [UnitsController::class, 'store'])->name('unit.store');
    Route::post('editeUnit/{id}', [UnitsController::class, 'edit'])->name('unit.update');
    Route::post('unit.ajax_search', [UnitsController::class, 'ajax_search'])->name('unit.ajax_search');
    Route::get('delete/{id}', [UnitsController::class, 'delete'])->name('unit.delete');

    Route::post('/storeemcard_categories',[ InvItemcardCategoriesController::class,'store'])->name('inv_itemcard_categories.store');
    Route::resource('/inv_itemcard_categories', InvItemcardCategoriesController::class);

    //end unit


    //Confirm product delivery

    Route::get('confirm_delivery', [DeliveryProductToTheCustomerController::class, 'index']);
    Route::get('confirm_sales', [DeliveryProductToTheCustomerController::class, 'confirm_sales']);
    Route::post('search_confirm_delievery', [DeliveryProductToTheCustomerController::class, 'store']);
    Route::post('confirm_delivery_all', [DeliveryProductToTheCustomerController::class, 'edit']);
    Route::post('search_confirm_sales', [DeliveryProductToTheCustomerController::class, 'search_confirm_sales']);
    Route::post('confirm_sales_delivery_all', [DeliveryProductToTheCustomerController::class, 'confirm_sales_delivery_all']);

    
    //end confirm_delivery
    //ProductsDamage

    Route::get('ProductsDamageReport', [ProductsDamageController::class, 'index']);
    Route::post('ProductsDamageReport', [ProductsDamageController::class, 'show']);



    //end ProductsDamage
//product_movement_another_branch

Route::get('sendProduct', [ProductMovementAnotherBranchController::class, 'index']);
Route::get('reciveProduct', [ProductMovementAnotherBranchController::class, 'show']);
Route::get('deleteproduct/{id}', [ProductMovementAnotherBranchController::class, 'destroy']);
Route::get('findinvoiceMovmevt/{id}', [ProductMovementAnotherBranchController::class, 'findinvoiceMovmevt']);
Route::post('create_sendProduct', [ProductMovementAnotherBranchController::class, 'create']);
Route::post('create_reciveProduct', [ProductMovementAnotherBranchController::class, 'store']);
Route::post('print_Transfer_items', [ProductMovementAnotherBranchController::class, 'print_Transfer_items']);
Route::post('print_Recive_items', [ProductMovementAnotherBranchController::class, 'print_Recive_items']);





//end product_movement_another_branch
    //products
    Route::get('goToSale', [ProductsController::class, 'goToSale']);
    Route::get('goToSaleBypage', [ProductsController::class, 'goToSaleByPage']);
    Route::get('searchaboutproduct/{searchtext}', [ProductsController::class, 'searchaboutproduct']);


    Route::get('showAllproductpaginate', [ProductsController::class, 'showAllproductpaginate']);
    Route::get('searchAllproductpaginate/{searchtext}', [ProductsController::class, 'searchAllproductpaginate']);
    Route::get('searchAllproductpaginatenew/{searchtext}', [ProductsController::class, 'searchAllproductpaginatenew']);
    Route::get('searchAllInvoicespaginatenew/{date}', [ProductsController::class, 'searchAllInvoicespaginatenew']);
    Route::get('searchAllInvoicespaginatenewpurchase/{date}', [ProductsController::class, 'searchAllInvoicespaginatenewpurchase']);
    Route::get('searchaboutinvoiceByIdfunction/{date}', [ProductsController::class, 'searchaboutinvoiceByIdfunction']);
    Route::get('searchaboutinvoiceByIdfunctionpurchases/{date}', [ProductsController::class, 'searchaboutinvoiceByIdfunctionpurchases']);
    Route::get('searchaboutReciptByIdfunction/{date}', [ProductsController::class, 'searchaboutReciptByIdfunction']);
    Route::get('searchAllRecieptspaginatenew/{date}', [ProductsController::class, 'searchAllRecieptspaginatenew']);
    Route::get('Allproductpaginatenew', [ProductsController::class, 'Allproductpaginatenew']);
    Route::get('getAllinvicesajax', [ProductsController::class, 'getAllinvicesajax']);
    Route::get('getAllinvicesapurchasesjax', [ProductsController::class, 'getAllinvicesapurchasesjax']);
    Route::get('getAllRecieptsjax', [ProductsController::class, 'getAllRecieptsjax']);
    Route::get('searchChooseProductpaginatenew/{searchtext}/{branch_id}', [ProductsController::class, 'searchChooseProductpaginatenew']);
    Route::get('ChooseProductpaginatenew/{branch_id}', [ProductsController::class, 'ChooseProductpaginatenew']);
   
    Route::get('searchChooseProductpaginatenewSale/{searchtext}/{branch_id}', [ProductsController::class, 'searchChooseProductpaginatenewSale']);
    Route::get('ChooseProductpaginatenewSale/{branch_id}', [ProductsController::class, 'ChooseProductpaginatenewSale']);



    
    Route::get('showAllproductpaginatepurchase/{branchId}', [ProductsController::class, 'showAllproductpaginatepurchase']);
    Route::get('searchAllproductpaginatepurchase/{branchId}/{searchtext}', [ProductsController::class, 'searchAllproductpaginatepurchase']);


    Route::get('searchaboutproductwithBranchId/{searchtext}/{branchId}', [ProductsController::class, 'searchaboutproductwithBranchId']);
    
    Route::get('printReturnpurchases/{id}', [ProductsController::class, 'printReturnpurchases']);

    Route::get('ShowAllNotifications', [ProductsController::class, 'ShowAllNotifications']);
    Route::get('profile', [ProductsController::class, 'profile']);
    Route::get('showAllProducts', [ProductsController::class, 'showAllProducts']);
    Route::get('previousPurchasesInvoices', [ProductsController::class, 'previousPurchasesInvoices']);
    Route::get('previousSalesInvoices', [ProductsController::class, 'previousSalesInvoices']);
    Route::get('previousRecieptInvoices', [ProductsController::class, 'previousRecieptInvoices']);
    Route::get('printOrderPriceFromSupplier/{id}', [ProductsController::class, 'printOrderPriceFromSupplier']);
    Route::post('printOrderPriceFromSupplier', [ProductsController::class, 'printOrderPriceFromSupplierBypost']);
    Route::get('getproductsquntitytocustomer', [ProductsController::class, 'index']);
    Route::get('getproductspricetocustomer', [ProductsController::class, 'showProductsPrice']);
    Route::get('getproductsprice', [ProductsController::class, 'getProductsPriceFromSupplier']);
    Route::get('getproduct/{id}', [ProductsController::class, 'show']);
    Route::get('savepurchase/{id}/{payment}/{supplier}', [ProductsController::class, 'savepurchase']);
    Route::get('getProductdJsonDecode/{id}', [ProductsController::class, 'getProductdJsonDecode']);
    Route::get('Purchase_returns', [ProductsController::class, 'Purchase_returns']);
    Route::get('purchases', [ProductsController::class, 'purchases']);
    Route::post('printavaliableproduct', [ProductsController::class, 'create']);
    Route::get('printavaliableproductprice', [ProductsController::class, 'printProductPriceToCustomer']);
    Route::post('printproductprice', [ProductsController::class, 'printProductPrice']);
    Route::post('print_all_products_price', [ProductsController::class, 'print_all_products_price']);
    Route::post('AddproducttoSupllier', [ProductsController::class, 'AddproducttoSupllier']);
    Route::post('Addproducttopurchases', [ProductsController::class, 'Addproducttopurchases']);
    Route::post('purchaseproduct_update', [ProductsController::class, 'update']);
    Route::post('purchaseproduct_delete', [ProductsController::class, 'destroy']);
    Route::get('goToReceipt', [ProductsController::class, 'goToReceipt']);
    Route::post('order_price_from_suppliers', [ProductsController::class, 'order_price_from_suppliers']);
    Route::post('report_offer_price_customer', [ProductsController::class, 'AddproductPriceToCustomer']);
    Route::post('AddproductPriceToCustomer', [ProductsController::class, 'AddproductPriceToCustomer']);
    Route::post('print_order_perice_to_customer', [ProductsController::class, 'print_order_perice_to_customerByPost']);
    Route::get('report_offer_price_customer', [ProductsController::class, 'print_order_perice_to_customer']);
    Route::post('updatePurchase', [ProductsController::class, 'updatePurchase']);
    Route::post('updatePurchaseOrder', [ProductsController::class, 'updatePurchaseOrder']);
    Route::post('updatePurchaseOrderToIncrease', [ProductsController::class, 'updatePurchaseOrderToIncrease']);
    Route::get('/makeTotalDiscontpurchases/{idInvoice}/{discountvalue}', [ProductsController::class, 'makeTotalDiscontpurchases']);
    Route::get('/cancelInvoiceDiscontpurcgases/{idInvoice}', [ProductsController::class, 'cancelInvoiceDiscontpurcgases']);

    Route::post('increasePurchase', [ProductsController::class, 'increasePurchase']);
    Route::post('updateproductalldatapurchases', [ProductsController::class, 'updateproductalldatapurchases']);

    Route::get('get_all_products_in_orderto_supplier/{order_id}', [ProductsController::class, 'get_all_products_in_orderto_supplier']);
    Route::get('changePaymethodPurchase/{id}/{paymendMethod}', [ProductsController::class, 'changePaymethodIPurchases']);




    //end product

//start transfer money to main branch

Route::post('Transfertomainbranch', [TransferMoneyToMainbranchController::class, 'store']);
Route::post('updateTransfertomainbranch', [TransferMoneyToMainbranchController::class, 'updateTransfertomainbranch']);
Route::post('updateTransfertomainbranchnotconfirm', [TransferMoneyToMainbranchController::class, 'updateTransfertomainbranchnotconfirm']);
Route::get('confirmTransfarToMainBranch/{id}', [TransferMoneyToMainbranchController::class, 'show']);
Route::get('rejectTransfarToMainBranch/{id}', [TransferMoneyToMainbranchController::class, 'rejectTransfarToMainBranch']);
Route::get('pendingtransfers', [TransferMoneyToMainbranchController::class, 'pendingtransfers']);
Route::post('print_Transfer_Main_Branch', [TransferMoneyToMainbranchController::class, 'print_Transfer_Main_Branch']);




//end 


    //++++++++++++++++++
    //accountes

    Route::get('voncher', [AcountesController::class, 'voncher']);
    Route::get('convertcashboxToBank', [AcountesController::class, 'convertcashboxToBank']);
    Route::get('Transfertomainbranch', [AcountesController::class, 'transferMainBranch']);
    Route::get('confirmTransfertomainbranch', [AcountesController::class, 'confirmTransfertomainbranch']);
    Route::get('cashEcprnse', [AcountesController::class, 'cashEcprnse']);
    Route::get('income', [AcountesController::class, 'income']);
    Route::get('go_to_bank', [AcountesController::class, 'go_to_bank']);
    Route::post('Add_blance_from_bank', [AcountesController::class, 'Add_blance_from_bank']);
    Route::post('updateAdd_blance_from_bank', [AcountesController::class, 'updateAdd_blance_from_bank']);
    Route::post('convertcashboxToBank', [AcountesController::class, 'SearchconvertcashboxToBank']);
    Route::post('printconvertcashboxToBank', [AcountesController::class, 'printconvertcashboxToBank']);
    
    Route::get('reciept_decoument', [AcountesController::class, 'reciept_decoument']);



    Route::get('Transfer_cash_to_next_day', [AcountesController::class, 'Transfer_cash_to_next_day']);
    Route::post('/Transfercashto_the_next_day', [AcountesController::class, 'Transfercashto_the_next_day']);
    Route::post('/updatedecoumentcashNextDay', [AcountesController::class, 'updatedecoumentcashNextDay']);



    Route::post('/print_reciept', [AcountesController::class, 'print_voucher']);
    Route::post('/print_reciept_ducoument', [AcountesController::class, 'print_reciept_ducoument']);
    Route::post('/print_expansedecoument', [AcountesController::class, 'print_expansedecoument']);




    //end accountes






    //++++++++++++++++++++
    //Credittransactions


    Route::post('Credittransactions', [CredittransactionsController::class, 'create']);
    Route::post('updateVoncher', [CredittransactionsController::class, 'updateVoncher']);
    Route::post('reciepttransactions', [CredittransactionsController::class, 'store']);

    Route::post('updaterecieptdecoument', [CredittransactionsController::class, 'updaterecieptdecoument']);



    //endCredittransactions




    //+++++++++++++++++++
    //Expenses

    Route::post('Expenses', [ExpensesController::class, 'store']);
    Route::post('updateExpenses', [ExpensesController::class, 'updateExpenses']);
    Route::post('ExpensesOwner', [ExpensesController::class, 'ExpensesOwner']);



    //end Expenses




    //+++++++++++++++++++
    //invoices
    Route::post('AddInvoices', [InvoicesController::class, 'store']);
    Route::post('Receipt', [InvoicesController::class, 'Receipt']);
    Route::post('EditInvoices', [InvoicesController::class, 'edit']);
    Route::post('updateproductallDataInvoices', [InvoicesController::class, 'updateproductallDataInvoices']);
    Route::post('editRecipt', [InvoicesController::class, 'editRecipt']);
    Route::post('returnAll', [InvoicesController::class, 'returnAll']);

    Route::post('increaseProduct', [InvoicesController::class, 'increaseProduct']);
    Route::get('/makeTotalDiscont/{idInvoice}/{discountvalue}', [InvoicesController::class, 'makeTotalDiscont']);
    Route::get('/confirmpaymentconfirmpayment/{inviceId}/{cashamount}/{bankamount}/{creaditamount}/{Bank_transfer}/{payment}', [InvoicesController::class, 'confirmpaymentconfirmpayment']);
    Route::get('/updatepaymentconfirmpayment/{inviceId}/{cashamount}/{bankamount}/{creaditamount}/{Bank_transfer}/{payment}', [InvoicesController::class, 'updatepaymentconfirmpayment']);
    Route::get('/updatepaymentconfirmpaymentpurchases/{inviceId}/{cashamount}/{bankamount}/{creaditamount}/{Bank_transfer}/{payment}', [InvoicesController::class, 'updatepaymentconfirmpaymentpurchases']);
    Route::get('/updatepaymentconfirmpaymentReciept/{inviceId}/{cashamount}/{bankamount}/{creaditamount}/{Bank_transfer}/{payment}', [InvoicesController::class, 'updatepaymentconfirmpaymentReciept']);
    Route::get('/cancelInvoiceDiscont/{idInvoice}', [InvoicesController::class, 'cancelInvoiceDiscont']);
    Route::get('/getproductbyCode/{code}', [InvoicesController::class, 'getproductbyCode']);
    Route::get('/getproductbyCodeandbranch/{branch}/{code}', [InvoicesController::class, 'getproductbyCodeandbranch']);
    Route::post('/getByCode', [InvoicesController::class, 'getByCode']);
    
    Route::get('printInvoice/{id}', [InvoicesController::class, 'printInvoice']);
    Route::get('saveInvoice/{id}', [InvoicesController::class, 'saveInvoice']);
    Route::get('printreturnInvoice/{id}', [InvoicesController::class, 'printreturnInvoice']);
    Route::get('showInvoice/{id}', [InvoicesController::class, 'showInvoice']);
    Route::get('showInvoiceRecent/{id}', [InvoicesController::class, 'showInvoiceRecent']);
    Route::get('showRecieptRecent/{id}', [InvoicesController::class, 'showRecieptRecent']);
    
    Route::post('printInvoice', [InvoicesController::class, 'printInvoice']);
    Route::post('return_sale', [InvoicesController::class, 'return_sale']);
    Route::get('return_sale', [InvoicesController::class, 'index']);
    Route::post('update_return_sale', [InvoicesController::class, 'update_return_Sale']);
    Route::post('printReceiptToStorehouse', [InvoicesController::class, 'printReceiptToStorehouse']);
    Route::get('changePaymethodIninvoice/{id}/{paymendMethod}', [InvoicesController::class, 'changePaymethodIninvoice']);
    Route::get('changechustomer/{id}/{paymendMethod}', [InvoicesController::class, 'changechustomerInInvoice']);
    Route::post('updatecustomerDataInvoice', [InvoicesController::class, 'updatecustomerDataInvoice']);
    Route::post('updatecustomerDataInvoicepurchases', [InvoicesController::class, 'updatecustomerDataInvoicepurchases']);
    Route::post('updatecustomerDataRecipt', [InvoicesController::class, 'updatecustomerDataRecipt']);





    //endinvoices





    //++++++++++++++++++++++++++++
    //supllier


    Route::get('Purchase_order_of_resources', [SupllierController::class, 'index']);
    Route::get('getsupllier/{id}', [SupllierController::class, 'show']);
    Route::get('purchasesShow/{id}', [SupllierController::class, 'purchasesShow']);
    Route::post('printProductToSupllier', [SupllierController::class, 'edit']);
    Route::post('printProductToSupllierOrder', [SupllierController::class, 'printProductToSupllierOrder']);

    Route::get('printProductToSupllier/{id}', [SupllierController::class, 'prindorderToSupplier']);
    Route::post('Purchase_returns_Data', [ProductsController::class, 'Purchase_returns_Data']);





    //end supplier
    //+++++++++++++++++++++++++++
    //users

    Route::get('getallusers', [AdminController::class, 'show']);
    Route::get('updateuser/{id}', [AdminController::class, 'edit']);
    Route::get('deleteuser/{id}', [AdminController::class, 'destroy']);


    //end user





    //+++++++++++++++++++++++++++
    //customer


    Route::get('/getcustomer/{id}', [CustomersController::class, 'show']);




    //endcustomer



    //+++++++++++++++
    //Reports
    Route::get('/Customer_account_statement', [ReportController::class, 'Customer_account_statement']);
    Route::post('/Customer_account_statement', [ReportController::class, 'search_Customer_account_statement']);
    Route::get('/TransFerCashTothenNextDay', [ReportController::class, 'TransFerCashTothenNextDay']);
    Route::post('/TransFerCashTothenNextDay', [ReportController::class, 'search_TransFerCashTothenNextDay']);
    
    Route::get('/Expired_Products', [ReportController::class, 'Expired_Products']);


    Route::get('/Bank_Statement', [ReportController::class, 'Bank_Statement']);



    Route::post('/bankDecument', [ReportController::class, 'searchbankDecument']);

    Route::get('/ConvertBoxtobankReport', [ReportController::class, 'ConvertBoxtobankReport']);
    Route::post('/ConvertBoxtobankReport', [ReportController::class, 'searchConvertBoxtobankReport']);
    Route::get('/transactionsToMasterBranch', [ReportController::class, 'transactionsToMasterBranch']);
    Route::post('/searchtransactionsToMasterBranch', [ReportController::class, 'searchtransactionsToMasterBranch']);
    Route::get('/Bank_Transfer', [ReportController::class, 'Bank_Transfer']);
    Route::post('/Bank_Transfer', [ReportController::class, 'search_Bank_Transfer']);
    Route::get('/print_Bank_Transfer/{branch}/{start}/{end}', [ReportController::class, 'print_Bank_Transfer']);
    Route::get('/print_products_Transfer/{branchfrom}/{branchto}/{start}/{end}', [ReportController::class, 'print_products_Transfer']);
    Route::get('/Transfer_products', [ReportController::class, 'products_Transfer']);
    Route::post('/search_Transfer_products', [ReportController::class, 'search_products_Transfer']);
    Route::get('/print_Transfer_products/{invoiceId}', [ReportController::class, 'print_Transfer_products']);
    
    Route::post('/stockquantity', [ReportController::class, 'search_stockquantity']);
    Route::get('/search_stockquantityPagination/{searchtext}/{branchId}', [ReportController::class, 'search_stockquantityPagination']);
    Route::get('/stockquantityPagination/{branchId}/{chooseOperation}/{quantity}', [ReportController::class, 'stockquantityPagination']);
    
    Route::get('/employeeـsales', [ReportController::class, 'employeeـsales']);
    Route::get('/showallBranchs', [ReportController::class, 'showallBranchs']);
    Route::get('/salesـprofits', [ReportController::class, 'salesـprofits']);
    Route::post('/salesReport', [ReportController::class, 'salesReportsearch']);
    Route::post('/salesـprofits', [ReportController::class, 'salesـprofitssearch']);
    Route::get('/salesReport', [ReportController::class, 'salesReport']);
    Route::get('/Show_return_Sales_Details/{invoiceId}', [ReportController::class, 'Show_return_Sales_Details']);
    
    Route::post('/search_Requestـoffersـfromـsuppliers', [ReportController::class, 'search_Requestـoffersـfromـsuppliers']);
    Route::post('/employeeSalesSearch', [ReportController::class, 'employeeSalesSearch']);
    Route::get('/printInvoicesReport/{branch}/{paymethod}/{startat}/{endat}', [ReportController::class, 'printInvoicesReport']);
    Route::get('/report_returns_sale', [ReportController::class, 'report_returns_sale']);
    Route::post('/search_report_returns_sale', [ReportController::class, 'search_report_returns_sale']);
    Route::get('/printreturnInvoicesReport/{branch}/{startat}/{endat}', [ReportController::class, 'print_return_Report']);
    Route::get('/printReportProductSales/{branch}/{productId}/{startat}/{endat}', [ReportController::class, 'printReportProductSales']);
    Route::get('/printReportemployeeSales/{userId}/{startat}/{endat}', [ReportController::class, 'printReportemployeeSales']);
    Route::get('/printReportProfitSales/{branch_id}/{userId}/{startat}/{endat}', [ReportController::class, 'printReportProfitSales']);
    Route::get('/print_report_order_from_supplier/{SupplierId}/{startat}/{endat}', [ReportController::class, 'print_report_order_from_supplier']);
    Route::get('/printReportoffer_price_customer/{SupplierId}/{startat}/{endat}', [ReportController::class, 'printReportoffer_price_customer']);
    Route::get('/Requestـoffersـfromـsuppliers', [ReportController::class, 'Requestـoffersـfromـsuppliers']);
    Route::get('/product_sales', [ReportController::class, 'product_sales']);
    Route::post('/product_sales', [ReportController::class, 'search_product_sales']);
    Route::get('/report_offer_price_customer', [ReportController::class, 'report_offer_price_customer']);
    Route::post('/show_offer_price_customer', [ReportController::class, 'show_offer_price_customer']);

    Route::get('/Delivery_notes', [ReportController::class, 'Delivery_notes']);
    Route::post('/Delivery_notes', [ReportController::class, 'search_Delivery_notes']);
    Route::get('/printDelivery_notes/{orderId}/{startat}/{endat}', [ReportController::class, 'printDelivery_notes']);


    Route::get('/Requestـaـquoteـfromـtheـsupplier', [ReportController::class, 'Requestـaـquoteـfromـtheـsupplier']);
    Route::post('/Requestـaـquoteـfromـtheـsupplier', [ReportController::class, 'search_Requestـaـquoteـfromـtheـsupplier']);
    Route::get('/print_Requestـaـquoteـfromـtheـsupplier/{branchId}/{supplier}/{startat}/{endat}', [ReportController::class, 'print_Requestـaـquoteـfromـtheـsupplier']);

    Route::get('/Purchasesـfromـsuppliers', [ReportController::class, 'Purchasesـfromـsuppliers']);
    Route::post('/Purchasesـfromـsuppliers', [ReportController::class, 'search_Purchasesـfromـsuppliers']);


    


    Route::get('/print_Purchasesـfromـsuppliers/{branch}/{pay}/{supplierId}/{startat}/{endat}', [ReportController::class, 'print_Purchasesـfromـsuppliers']);
    Route::get('Refundـofـresourceـpurchases', [ReportController::class, 'Refundـofـresourceـpurchases']);

    Route::post('/Refundـofـresourceـpurchases', [ReportController::class, 'search_Refundـofـresourceـpurchases']);
    Route::get('/print_Refundـofـresourceـpurchases/{branch_id}/{startat}/{endat}', [ReportController::class, 'print_Refundـofـresourceـpurchases']);


    Route::get('/purchasereports', [ReportController::class, 'purchasereports']);
    Route::post('/purchasereports', [ReportController::class, 'search_purchasereports']);
    Route::get('/print_purchasereports/{productId}/{startat}/{endat}', [ReportController::class, 'print_purchasereports']);

    Route::get('/customerـpurchases', [ReportController::class, 'customerـpurchases']);
    Route::post('/customerـpurchases', [ReportController::class, 'search_customerـpurchases']);
    Route::get('/print_customerـpurchases/{branchId}/{customerId}/{startat}/{endat}', [ReportController::class, 'print_customerـpurchases']);

    Route::get('/credit_collection', [ReportController::class, 'credit_collection']);
    Route::post('/credit_collection', [ReportController::class, 'search_credit_collection']);
    Route::get('/print_credit_collection/{userId}/{startat}/{endat}', [ReportController::class, 'print_credit_collection']);



    Route::get('/supplierlist', [ReportController::class, 'supplierList']);
    Route::get('/Customerlist', [ReportController::class, 'Customerlist']);
    Route::get('/print_supplierlist/{userId}/{startat}/{endat}', [ReportController::class, 'print_supplierlist']);
    Route::get('/print_customeList', [ReportController::class, 'print_customeList']);
    Route::get('/Stocktaking', [ReportController::class, 'Stocktaking']);
    Route::get('/Stocktakingpdf', [ReportController::class, 'Stocktakingpdf']);

    Route::get('/print_SupplierList', [ReportController::class, 'print_supplierList']);



    Route::get('/Supplier_credit_payment', [ReportController::class, 'Supplier_credit_payment']);
    Route::post('/Supplier_credit_payment', [ReportController::class, 'search_Supplier_credit_payment']);
    Route::get('/print_Supplier_credit_payment/{supplierId}/{startat}/{endat}', [ReportController::class, 'print_Supplier_credit_payment']);

    Route::get('/shift_detailes', [ReportController::class, 'shift_detailes']);
    Route::post('/shift_detailes', [ReportController::class, 'search_shift_detailes']);
    Route::get('/print_shift_detailes/{branch_id}/{paumethod}/{startat}/{endat}', [ReportController::class, 'print_shift_detailes']);


    Route::get('/Expensesreport', [ReportController::class, 'Expenses']);
    Route::post('/Expensesreport', [ReportController::class, 'search_Expenses']);
    Route::get('/printExpensesReport/{branch_id}/{reson}/{startat}/{endat}', [ReportController::class, 'printExpensesReportlast']);

    Route::get('/stockquantity', [ReportController::class, 'stockquantity']);
    Route::get('/printstockquantity/{branch_id}/{display}/{quantity}', [ReportController::class, 'printstockquantity']);


    Route::get('/Best_selling_products', [ReportController::class, 'Best_selling_products']);
    Route::post('/Best_selling_products', [ReportController::class, 'search_Best_selling_products']);
    Route::get('/printBest_selling_products/{branch_id}/{startat}/{endat}', [ReportController::class, 'printBest_selling_products']);


    Route::get('/VAT', [ReportController::class, 'VAT']);
    Route::post('/VAT', [ReportController::class, 'search_VAT']);
    Route::get('/print_VAT/{branch_id}/{startat}/{endat}', [ReportController::class, 'print_VAT']);




    Route::get('/Customersـexceededـgraceـperiod', [ReportController::class, 'Customersـexceededـgraceـperiod']);

    Route::get('/budgetsheet', [ReportController::class, 'budgetsheet']);
    Route::post('/budgetsheet', [ReportController::class, 'search_budgetsheet']);


    //endReport

    //supProcesses


    Route::get('/addnewProduct', [SupprocessesController::class, 'index']);
    Route::post('/addnewProduct', [SupprocessesController::class, 'create_addnewProduct']);
    Route::post('/addnewProductajax', [SupprocessesController::class, 'addnewProductajax']);
    Route::post('/createnewcustomerajax', [SupprocessesController::class, 'createnewcustomerajax']);



    Route::get('/addnewcustomer', [SupprocessesController::class, 'addnewcustomer']);
    Route::post('/addnewcustomer', [SupprocessesController::class, 'create_addnewcustomer']);

    Route::get('/updatecustomer', [SupprocessesController::class, 'Goupdatecustomer']);
    Route::post('/updatecustomer', [SupprocessesController::class, 'updatecustomer']);
    Route::get('getcustomer/{id}', [SupprocessesController::class, 'getcustomerdata']);


    Route::get('/addnewsupplier', [SupprocessesController::class, 'addnewsupplier']);
    Route::post('/addnewsupplier', [SupprocessesController::class, 'create_addnewsupplier']);
    Route::post('/create_addnewsupplierajax', [SupprocessesController::class, 'create_addnewsupplierajax']);


    Route::get('/updatesupplier', [SupprocessesController::class, 'Goupdatesupplier']);
    Route::post('/updatesupplier', [SupprocessesController::class, 'updatesupplier']);
    Route::get('getsupplier/{id}', [SupprocessesController::class, 'getsupplierdata']);


    Route::get('/expenses_reason', [SupprocessesController::class, 'expenses_reason']);
    Route::post('/expenses_reason', [SupprocessesController::class, 'create_expenses_reason']);


    Route::get('/stockAdjastment', [SupprocessesController::class, 'stockAdjastment']);
    Route::post('/stockAdjastment', [SupprocessesController::class, 'stock_update']);


    Route::get('/product_movement', [SupprocessesController::class, 'product_movement']);
    Route::get('/product_damage', [SupprocessesController::class, 'product_damage']);
    Route::post('/product_damage_add', [SupprocessesController::class, 'product_damage_add']);
    Route::post('/product_movement', [SupprocessesController::class, 'update_product_movement']);


    //end Supprocesses


    //usersAndBranch

    Route::get('/addbranch', [BranchsController::class, 'index']);
    Route::post('/addbranch', [BranchsController::class, 'create']);











    //endUsersAndBranch


    //show branches

    Route::get('/showbranches', [BranchsController::class, 'show']);
    Route::post('/updatebranch', [BranchsController::class, 'updatebranch']);



    //end branches

    //AVT
    Route::get('/avt', [AvtController::class, 'index']);
    Route::post('/update_vat', [AvtController::class, 'update']);
    Route::post('/New_avt', [AvtController::class, 'store']);
    Route::post('/destory_avt', [AvtController::class, 'destroy']);





    //END avt

    //Hr

    Route::get('/createNewEmployee', [EmployeeController::class, 'index']);
    Route::post('/createNewEmployee', [EmployeeController::class, 'create']);
    Route::get('/allEmployees', [EmployeeController::class, 'show']);


    Route::get('/addnewDepartment', [EmployeeController::class, 'addnewDepartment']);
    Route::post('/addnewDepartment', [EmployeeController::class, 'store']);

    Route::get('/updateEmployee/{id}', [EmployeeController::class, 'updateEmployee']);
    Route::post('/updateEmployee', [EmployeeController::class, 'update']);

    Route::get('/Increaseـor_deduction', [EmployeeController::class, 'Increaseـor_deduction']);

    Route::post('/Increaseـor_deduction', [EmployeeController::class, 'Increaseـor_deduction_add']);

    Route::get('/salarydecoument', [EmployeeController::class, 'salarydecoument']);

    Route::post('/print_decument_salary', [EmployeeController::class, 'print_decument_salary']);




    //end Hr







    // Home Screen
    Route::get('/dashboard', function () {
        //   return resource_purchases::whereDate('created_at','>=',(date("Y").'-02'.'-01'))->whereDate('created_at','<',(date("Y").'-03'.'-01'))->get();
        app()->setLocale(LaravelLocalization::getCurrentLocale());

        return view('index');
    })->name('dashboard');
    Route::get('/{page}', function ($page) {

        if (view()->exists($page)) {
            return view($page);
        } else {
            return view('404');
        }
    });
});
