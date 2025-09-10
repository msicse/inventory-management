<?php

use App\Mail\TestMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\InventoryController;
use App\Http\Controllers\Admin\QrCodeController;

Route::get('/', function () {
    return view('login-home');
});

Route::get("/info", function(){
	return phpinfo();

});

Route::get('/login', function () {
    return view('login-home');
})->name('login');

Route::post("/custom-login", [LoginController::class, "postLogin"])->name("custom.login");
Route::post("/logout", [LoginController::class, "logout"])->name("logout");

// Auth::routes();


Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::group(['middleware' => ['auth']], function () {

    Route::get("/dashboard", [DashboardController::class, "index"])->name("dashboard");
    Route::resource('roles', RoleController::class);

    //ProductType Route
    Route::get('product-types', [App\Http\Controllers\Admin\ProductTypeController::class, 'index'])->name('product-types.index');
    Route::post('product-types', [App\Http\Controllers\Admin\ProductTypeController::class, 'store'])->name('product-types.store');
    Route::delete('product-types/{id}', [App\Http\Controllers\Admin\ProductTypeController::class, 'destroy'])->name('product-types.destroy');
    Route::get('product-types/{id}', [App\Http\Controllers\Admin\ProductTypeController::class, 'edit'])->name('product-types.edit');
    Route::put('product-types/{id}', [App\Http\Controllers\Admin\ProductTypeController::class, 'update'])->name('product-types.update');
    Route::delete('product-types/{id}', [App\Http\Controllers\Admin\ProductTypeController::class, 'destroy'])->name('product-types.destroy');

    //Products Route
    Route::get('products', [App\Http\Controllers\Admin\ProductController::class, 'index'])->name('products.index');
    Route::post('products', [App\Http\Controllers\Admin\ProductController::class, 'store'])->name('products.store');
    Route::get('products/{id}', [App\Http\Controllers\Admin\ProductController::class, 'edit'])->name('products.edit');
    Route::PUT('products/{id}', [App\Http\Controllers\Admin\ProductController::class, 'update'])->name('products.update');
    Route::delete('products/{id}', [App\Http\Controllers\Admin\ProductController::class, 'destroy'])->name('products.destroy');

    //Status Route
    Route::get('statuses', [App\Http\Controllers\Admin\StatusController::class, 'index'])->name('statuses.index');
    Route::post('statuses', [App\Http\Controllers\Admin\StatusController::class, 'store'])->name('statuses.store');
    Route::get('statuses/{id}', [App\Http\Controllers\Admin\StatusController::class, 'edit'])->name('statuses.edit');
    Route::PUT('statuses/{id}', [App\Http\Controllers\Admin\StatusController::class, 'update'])->name('statuses.update');
    Route::delete('statuses/{id}', [App\Http\Controllers\Admin\StatusController::class, 'destroy'])->name('statuses.destroy');

    //Store Route
    Route::get('stores', [App\Http\Controllers\Admin\StoreController::class, 'index'])->name('stores.index');
    Route::post('stores', [App\Http\Controllers\Admin\StoreController::class, 'store'])->name('stores.store');
    Route::get('stores/{id}', [App\Http\Controllers\Admin\StoreController::class, 'edit'])->name('stores.edit');
    Route::PUT('stores/{id}', [App\Http\Controllers\Admin\StoreController::class, 'update'])->name('stores.update');
    Route::delete('stores/{id}', [App\Http\Controllers\Admin\StoreController::class, 'destroy'])->name('stores.destroy');

    //Suppliers Route
    Route::get('suppliers', [App\Http\Controllers\Admin\SupplierController::class, 'index'])->name('suppliers.index');
    Route::post('suppliers', [App\Http\Controllers\Admin\SupplierController::class, 'store'])->name('suppliers.store');
    Route::get('suppliers/{id}', [App\Http\Controllers\Admin\SupplierController::class, 'edit'])->name('suppliers.edit');
    Route::PUT('suppliers/{id}', [App\Http\Controllers\Admin\SupplierController::class, 'update'])->name('suppliers.update');
    Route::delete('suppliers/{id}', [App\Http\Controllers\Admin\SupplierController::class, 'destroy'])->name('suppliers.destroy');

    //Departments Route
    Route::get('departments', [App\Http\Controllers\Admin\DepartmentController::class, 'index'])->name('departments.index');
    Route::post('departments', [App\Http\Controllers\Admin\DepartmentController::class, 'store'])->name('departments.store');
    Route::get('departments/{id}', [App\Http\Controllers\Admin\DepartmentController::class, 'edit'])->name('departments.edit');
    Route::PUT('departments/{id}', [App\Http\Controllers\Admin\DepartmentController::class, 'update'])->name('departments.update');
    Route::delete('departments/{id}', [App\Http\Controllers\Admin\DepartmentController::class, 'destroy'])->name('departments.destroy');


    //Employees Route
    Route::get('employees', [App\Http\Controllers\Admin\EmployeeController::class, 'index'])->name('employees.index');
    Route::get('employees/create', [App\Http\Controllers\Admin\EmployeeController::class, 'create'])->name('employees.create');
    Route::post('employees', [App\Http\Controllers\Admin\EmployeeController::class, 'store'])->name('employees.store');
    Route::get('employees/{id}', [App\Http\Controllers\Admin\EmployeeController::class, 'show'])->name('employees.show');
    Route::get('employees/{id}/edit', [App\Http\Controllers\Admin\EmployeeController::class, 'edit'])->name('employees.edit');
    Route::PUT('employees/{id}', [App\Http\Controllers\Admin\EmployeeController::class, 'update'])->name('employees.update');
    Route::post('employees/status/{id}', [App\Http\Controllers\Admin\EmployeeController::class, 'updateStatus'])->name('employees.status');
    Route::delete('employees/{id}', [App\Http\Controllers\Admin\EmployeeController::class, 'destroy'])->name('employees.destroy');


    //Requisitions Route
    Route::get('requisitions', [App\Http\Controllers\Admin\RequisitionController::class, 'index'])->name('requisitions.index');
    Route::get('requisitions/create', [App\Http\Controllers\Admin\RequisitionController::class, 'create'])->name('requisitions.create');
    Route::post('requisitions', [App\Http\Controllers\Admin\RequisitionController::class, 'store'])->name('requisitions.store');
    Route::get('requisitions/{id}', [App\Http\Controllers\Admin\RequisitionController::class, 'show'])->name('requisitions.show');



    //Purchases Route
    Route::get('purchases', [App\Http\Controllers\Admin\PurchaseController::class, 'index'])->name('purchases.index');
    Route::get('purchases/create', [App\Http\Controllers\Admin\PurchaseController::class, 'create'])->name('purchases.create');
    Route::post('purchases', [App\Http\Controllers\Admin\PurchaseController::class, 'store'])->name('purchases.store');
    Route::get('purchases/{id}', [App\Http\Controllers\Admin\PurchaseController::class, 'show'])->name('purchases.show');
    Route::get('purchases/{id}/edit', [App\Http\Controllers\Admin\PurchaseController::class, 'edit'])->name('purchases.edit');
    Route::PUT('purchases/{id}', [App\Http\Controllers\Admin\PurchaseController::class, 'update'])->name('purchases.update');
    Route::delete('purchases/{id}', [App\Http\Controllers\Admin\PurchaseController::class, 'destroy'])->name('purchases.destroy');
    Route::post('purchases/inventory/{id}', [App\Http\Controllers\Admin\PurchaseController::class, 'addInventory'])->name('purchases.inventory');
    Route::get('purchases/typed/{id}', [App\Http\Controllers\Admin\PurchaseController::class, 'typedProducts'])->name('purchases.typed.product');
    Route::get('purchases/product/{id}', [App\Http\Controllers\Admin\PurchaseController::class, 'product'])->name('purchases.product');
    Route::get('purchased-products', [App\Http\Controllers\Admin\PurchaseController::class, 'purchasedProducts'])->name('purchased.products');
    Route::get('purchased-products/{id}', [App\Http\Controllers\Admin\PurchaseController::class, 'purchasedProductShow'])->name('purchased.products.show');



    Route::get('update-asset', [App\Http\Controllers\Admin\PurchaseController::class, 'updateAssetTag']);


    Route::get('invoice', [App\Http\Controllers\Admin\PurchaseController::class, 'invoice'])->name('purchases.invoice');
    Route::get('grn/{id}', [App\Http\Controllers\Admin\PurchaseController::class, 'grn'])->name('purchases.grn');

    // Barcode Routes
    Route::get('stock/{id}/barcode', [App\Http\Controllers\Admin\PurchaseController::class, 'generateBarcode'])->name('stock.barcode');
    Route::get('stock/{id}/print-barcode', [App\Http\Controllers\Admin\PurchaseController::class, 'printBarcode'])->name('stock.print.barcode');
    Route::post('stock/print-multiple-barcodes', [App\Http\Controllers\Admin\PurchaseController::class, 'printMultipleBarcodes'])->name('stock.print.multiple.barcodes');

    // Purchase QR Code Routes
    Route::get('purchase/{id}/print-qrcodes', [App\Http\Controllers\Admin\PurchaseController::class, 'printPurchaseQrCodes'])->name('purchase.print.qrcodes');
    Route::get('purchase/{id}/print-qrcode-labels', [App\Http\Controllers\Admin\PurchaseController::class, 'printPurchaseQrCodeLabels'])->name('purchase.print.qrcode.labels');
    Route::get('purchase/{id}/print-qrcode-barcode-combo-labels', [App\Http\Controllers\Admin\PurchaseController::class, 'printPurchaseQrBarcodeComboLabels'])->name('purchase.print.qrcode.barcode.combo.labels');
    Route::get('purchase/{id}/debug-qrcodes', [App\Http\Controllers\Admin\PurchaseController::class, 'debugPurchaseQrCodes'])->name('purchase.debug.qrcodes');

    Route::post('stock/print-multiple-qrcodes', [QrCodeController::class, 'printMultipleQrCodes'])->name('stock.print.multiple.qrcodes');
    Route::get('test-simple-barcode/{id}', [App\Http\Controllers\Admin\PurchaseController::class, 'testSimpleBarcode'])->name('test.simple.barcode');

    // Stock QR Code Routes
    Route::get('stock/{id}/qrcode', [QrCodeController::class, 'generateStockQrCode'])->name('stock.qrcode');
    Route::get('stock/{id}/print-qrcode', [QrCodeController::class, 'printStockQrCode'])->name('stock.print.qrcode');
    Route::get('stock/{id}/print-qr-barcode-combo', [QrCodeController::class, 'printStockQrBarcodeCombo'])->name('stock.print.qr.barcode.combo');

    // QR Code routes
    Route::get('qr-codes', [QrCodeController::class, 'index'])->name('admin.qrcodes.index');
    Route::get('qr-generator', [QrCodeController::class, 'index'])->name('qrcode.generator');
    Route::get('qr-codes/generate/{stockId}', [QrCodeController::class, 'generateStockQrCode'])->name('admin.qrcodes.generate');
    Route::get('qr-codes/print/{stockId}', [QrCodeController::class, 'printStockQrCode'])->name('admin.qrcodes.print');
    Route::post('qr-codes/print-multiple', [QrCodeController::class, 'printMultipleQrCodes'])->name('admin.qrcodes.print.multiple');
    Route::post('qr-codes/generate-custom', [QrCodeController::class, 'generateCustomQrCode'])->name('admin.qrcodes.custom');
    Route::post('qr-codes/generate-url', [QrCodeController::class, 'generateUrlQrCode'])->name('admin.qrcodes.url');

    //Inventories Route
    Route::get('inventories', [App\Http\Controllers\Admin\InventoryController::class, 'index'])->name('inventories.index');
    Route::get('pending-tag-updates', [App\Http\Controllers\Admin\InventoryController::class, 'pending_asset_tag'])->name('inventories.pending');
    Route::post('update-asset-tag/{id}', [App\Http\Controllers\Admin\InventoryController::class, 'update_tag'])->name('inventories.update.tag');
    Route::post('inventories/upload-bulk', [App\Http\Controllers\Admin\InventoryController::class, 'uploadBulk'])->name('inventories.upload.bluck');
    Route::get('inventories/{id}', [App\Http\Controllers\Admin\InventoryController::class, 'show'])->name('inventories.show');
    Route::get('inventories/create', [App\Http\Controllers\Admin\InventoryController::class, 'create'])->name('inventories.create');
    Route::post('inventories', [App\Http\Controllers\Admin\InventoryController::class, 'store'])->name('inventories.store');
    Route::put('inventories/{id}', [App\Http\Controllers\Admin\InventoryController::class, 'update'])->name('inventories.update');

    //Transection Route
    Route::get('transections', [App\Http\Controllers\Admin\TransectionController::class, 'index'])->name('transections.index');
    Route::get('transections/create', [App\Http\Controllers\Admin\TransectionController::class, 'create'])->name('transections.create');
    Route::post('transections', [App\Http\Controllers\Admin\TransectionController::class, 'store'])->name('transections.store');
    Route::get('transections/{id}', [App\Http\Controllers\Admin\TransectionController::class, 'show'])->name('transections.show');
    Route::put('transections/{id}', [App\Http\Controllers\Admin\TransectionController::class, 'update'])->name('transections.update');
    Route::get('typed-products/{id}', [App\Http\Controllers\Admin\TransectionController::class, 'typedProducts'])->name('transections.typed.products');
    Route::get('single-stock/{id}', [App\Http\Controllers\Admin\TransectionController::class, 'singleStock'])->name('transections.stock');
    Route::get('transections/ack/{id}', [App\Http\Controllers\Admin\TransectionController::class, 'ack'])->name('transections.ack');

    //Users Route
    Route::get('users', [App\Http\Controllers\Admin\UserController::class, 'index'])->name('users.index');
    Route::post('users', [App\Http\Controllers\Admin\UserController::class, 'store'])->name('users.store');
    Route::get('users/create', [App\Http\Controllers\Admin\UserController::class, 'create'])->name('users.create');
    Route::get('users/{id}', [App\Http\Controllers\Admin\UserController::class, 'show'])->name('users.show');
    Route::get('users/{id}/edit', [App\Http\Controllers\Admin\UserController::class, 'edit'])->name('users.edit');
    Route::put('users/{id}', [App\Http\Controllers\Admin\UserController::class, 'update'])->name('users.update');
    Route::delete('users/{id}', [App\Http\Controllers\Admin\UserController::class, 'destroy'])->name('users.destroy');

    //Reports Route
    Route::get('reports/employees', [App\Http\Controllers\Admin\ReportController::class, 'index'])->name('reports.index');
    Route::get('reports/get-report', [App\Http\Controllers\Admin\ReportController::class, 'getReport'])->name('reports.get');
    Route::get('reports/employees/{id}', [App\Http\Controllers\Admin\ReportController::class, 'show'])->name('reports.show');
    Route::get('reports/transections', [App\Http\Controllers\Admin\ReportController::class, 'transections'])->name('reports.transections');
    Route::get('reports/stocks', [App\Http\Controllers\Admin\ReportController::class, 'stocks'])->name('reports.stocks');
    Route::get('reports/detailed-inventory', [App\Http\Controllers\Admin\ReportController::class, 'inventory'])->name('reports.inventory');
    Route::get('reports/detailed-inventory-search', [App\Http\Controllers\Admin\ReportController::class, 'inventorySearch'])->name('reports.inventory.search');
    Route::get('reports/stocks/{id}', [App\Http\Controllers\Admin\ReportController::class, 'stockDetails'])->name('reports.stocks.details');
    Route::get('reports/user-logs', [App\Http\Controllers\Admin\ReportController::class, 'userLogs'])->name('reports.userlog');
    Route::get('reports/user-logs/search', [App\Http\Controllers\Admin\ReportController::class, 'userLogsSearch'])->name('reports.userlog.search');

    // Management Routes
    Route::get('management/employees', [App\Http\Controllers\Admin\ManagementController::class, 'employees'])->name('management.employees');
    Route::get('management/employees/{id}', [App\Http\Controllers\Admin\ManagementController::class, 'editEmployee'])->name('management.employees.edit');
    Route::post('management/employees/{id}', [App\Http\Controllers\Admin\ManagementController::class, 'updateEmployee'])->name('management.employees.update');
    Route::get('management/products', [App\Http\Controllers\Admin\ManagementController::class, 'products'])->name('management.products');
    Route::post('management/products/{id}', [App\Http\Controllers\Admin\ManagementController::class, 'updateProducts'])->name('management.products.update');

    //Print multi ACK
    Route::post('transections/multi-ack', [App\Http\Controllers\Admin\TransectionController::class, 'multiAck'])->name('transections.multi.ack');

    //Print Onboarding ACK
    Route::get('onboarding', [App\Http\Controllers\OnboardinController::class, 'onboarding'])->name('onboardings');
    Route::get('onboarding-submit', [App\Http\Controllers\OnboardinController::class, 'postOnboarding'])->name('onboardings.submit');
    Route::get('onboarding/{id}/print', [App\Http\Controllers\OnboardinController::class, 'print'])->name('onboardings.print');

    //Print return Form
    Route::get('transections/return/{id}', [App\Http\Controllers\Admin\TransectionController::class, 'return'])->name('transections.return');

    Route::get('xyz', [App\Http\Controllers\ReportController::class, 'index'])->name('xyz.index');
    Route::match(array('GET', 'POST'), 'settings/password', [App\Http\Controllers\SettingController::class, 'password'])->name('settings.password');
    Route::match(array('GET', 'POST'), 'settings/profile', [App\Http\Controllers\SettingController::class, 'profile'])->name('settings.profile');

    Route::get('policy-print/{id}', [App\Http\Controllers\SettingController::class, 'policy'])->name('settings.policy');


    //Test
    Route::get('test-inventory', [InventoryController::class, 'updateStatus']);


    //Import route
    Route::get('imports', [App\Http\Controllers\ImportController::class, 'index'])->name('imports.index');
    Route::post('imports/store', [App\Http\Controllers\ImportController::class, 'store'])->name('imports.store');
});



Route::get('/send-mail', function () {
    $details = [
        'title' => 'Hello from Laravel 12',
        'body' => 'This is a test email using new Mailable structure.'
    ];

    Mail::to('masud@rsc-bd.org')->send(new TestMail($details));
    Mail::to('sumon.bd969@gmail.com')->send(new TestMail($details));

    return 'Email Sent!';
});
