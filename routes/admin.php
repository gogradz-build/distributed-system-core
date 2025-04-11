<?php

use App\Http\Controllers\Admin\AdminAdjustmentController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ExcelExportController;
use App\Http\Controllers\Admin\AdminRefController;
use App\Http\Controllers\Admin\AdminSaleController;
use App\Http\Controllers\Admin\AdminShopController;
use App\Http\Controllers\Admin\AdminBrandController;
use App\Http\Controllers\Admin\AdminProductController;
use App\Http\Controllers\Admin\AdminCategoryController;
use App\Http\Controllers\Admin\AdminPaymentsController;
use App\Http\Controllers\Admin\AdminPurchaseController;
use App\Http\Controllers\Admin\AdminSupplierController;
use App\Http\Controllers\Admin\AdminVariationController;
use App\Http\Controllers\Admin\AdminWarehouseController;
use App\Http\Controllers\Admin\AdminSaleReturnController;
use App\Http\Controllers\Admin\AdminVariationTypeController;
use App\Http\Controllers\Admin\AdminGenerateReportController;
use App\Http\Controllers\Admin\AdminPurchaseReturnController;
use App\Http\Controllers\Admin\AdminWarehouseStockController;
use App\Http\Controllers\AdminController;

Route::post('/admin', [AdminAuthController::class, 'signIn'])->name('admin.login');


Route::middleware(['auth'])->group(function () {
    // role and permission
    Route::get('/user/role', [AdminController::class, 'getUserRole']);
    Route::resource('permissions', PermissionController::class);
    Route::resource('roles', RoleController::class);
    Route::get('roles/{roleId}/delete', [RoleController::class, 'destroy'])->name('roles.destroy');
    Route::put('roles/{roleId}/give-permission', [RoleController::class, 'givePermissionToRole']);
    Route::get('/roles/{roleId}/permissions', [RoleController::class, 'getRolePermissions']);

    //category
    Route::resource('/categories', AdminCategoryController::class);
    Route::get('/admin/category', [AdminCategoryController::class, 'showCategory'])->name('admin.category');

    // brands
    Route::resource('/brands', AdminBrandController::class);
    Route::get('/admin/brands', [AdminBrandController::class, 'showBrands'])->name('admin.brands');

    //warehouse
    Route::resource('/warehouses', AdminWarehouseController::class);
    Route::get('/admin/warehouse', [AdminWarehouseController::class, 'showWarehouse'])->name('admin.warehouse');

    //supplier
    Route::resource('/suppliers', AdminSupplierController::class);
    Route::get('/admin/supplier', [AdminSupplierController::class, 'showSupplier'])->name('admin.showSupplier');
    Route::get('/admin/create-supplier', [AdminSupplierController::class, 'showCreateSupplier'])->name('admin.create.supplier');

    //ref
    Route::resource('/refs', AdminRefController::class);
    Route::get('/admin/ref', [AdminRefController::class, 'showRef'])->name('admin.show.ref');
    Route::get('/admin/create-ref', [AdminRefController::class, 'showCreateRef'])->name('admin.create.ref');

    //shop
    Route::resource('/shops', AdminShopController::class);
    Route::get('/admin/shop', [AdminShopController::class, 'showShop'])->name('admin.show.shop');
    Route::get('/admin/create-shop', [AdminShopController::class, 'showCreateShop'])->name('admin.create.shop');

    //variation type
    Route::resource('/variationTypes', AdminVariationTypeController::class);
    Route::get('/admin/variation-type', [AdminVariationTypeController::class, 'showVariationType'])->name('admin.variation.type');
    Route::get('/admin/variation/{variation_id}/type', [AdminVariationTypeController::class, 'getVariationTypeData'])->name('get.variation.type.data');
    Route::get('/admin/variation/{variation_id}/type-value', [AdminVariationTypeController::class, 'getVariationTypeDataValue'])->name('get.variation.type.data.value');
    Route::get('/admin/variation/{variation_id}/type-data', [AdminVariationTypeController::class, 'getVariationTypeValueData'])->name('get.variation.type.value.data');

    //variations
    Route::resource('/variations', AdminVariationController::class);
    Route::get('/admin/variation', [AdminVariationController::class, 'showVariation'])->name('admin.variation');
    Route::get('/admin/variation/data', [AdminVariationController::class, 'getVariationData'])->name('get.variation.data');

    //products
    Route::resource('/products', AdminProductController::class);
    Route::get('/admin/product', [AdminProductController::class, 'showProducts'])->name('admin.products');
    Route::get('/admin/create-product', [AdminProductController::class, 'showCreateProducts'])->name('admin.create.products');

    // returnPurchases
    Route::resource('/returnPurchases', AdminPurchaseReturnController::class);

    // adjustments
    Route::resource('/adjustments', AdminAdjustmentController::class);
    Route::get('/admin/adjustment', [AdminAdjustmentController::class, 'showAdjustment'])->name('admin.adjustment');

    //perches
    Route::resource('/purchases', AdminPurchaseController::class);
    Route::get('/admin/perches', [AdminPurchaseController::class, 'showPerches'])->name('admin.perches');
    Route::get('/admin/create-perches', [AdminPurchaseController::class, 'showCreatePerches'])->name('admin.create.perches');
    Route::get('/admin/perches/return', [AdminPurchaseController::class, 'showPerchesReturn'])->name('admin.perches.return');

    //perches return
    Route::get('/admin/perches/{id}/return', [AdminPurchaseReturnController::class, 'edit'])->name('admin.create.perches.return');

    //sales return
    Route::get('/admin/sale/{id}/return', [AdminSaleReturnController::class, 'edit'])->name('admin.create.sales.return');
    Route::get('/admin/sale/modal-return/{id}', [AdminSaleReturnController::class, 'modalSalesEdit'])->name('admin.create.sales.modal.return');

    //sales
    Route::resource('/sales', AdminSaleController::class);
    Route::get('/admin/sales', [AdminSaleController::class, 'showSales'])->name('admin.Sales');
    Route::get('/admin/create-sales', [AdminSaleController::class, 'showCreateSales'])->name('admin.create.Sales');
    Route::get('/admin/search/shop/{searchKey}', [AdminSaleController::class, 'shopSearch'])->name('admin.search');

    //model Sale status update
    Route::post('/admin/sales/{id}', [AdminSaleController::class, 'saleStatusUpdate'])->name('admin.sale.status.update');

    // saleReturns
    Route::resource('/saleReturns', AdminSaleReturnController::class);
    Route::get('/sale-returns-details', [AdminSaleReturnController::class, 'showSaleReturns'])->name('admin.sale.return');

    //search
    Route::get('/admin/search/{searchKey}', [AdminPurchaseController::class, 'search'])->name('admin.search');
    Route::get('/admin/search/{searchKey}/{warehouse}', [AdminPurchaseController::class, 'searchByWarehouse'])->name('admin.search.warehouse');

    //print
    Route::get('/sale/print', [AdminSaleController::class, 'print'])->name('sale.print');

    // stock
    Route::get('/stock', [AdminWarehouseStockController::class, 'getStockData'])->name('admin.stock.data');
    Route::get('/monthly/sales', [AdminSaleController::class, 'getMonthlySalesData'])->name('admin.monthly.sales.data');

    // report
    Route::get('/admin/stock', [AdminGenerateReportController::class, 'showStockReport'])->name('admin.stock');
    Route::get('/admin/monthly/sales', [AdminGenerateReportController::class, 'showMonthlySalesReport'])->name('admin.monthly.sales');
    Route::get('/admin/outstanding', [AdminGenerateReportController::class, 'index'])->name('admin.outstanding');
    Route::get('/admin/collection', [AdminGenerateReportController::class, 'showCollection'])->name('admin.collection');
    Route::get('/admin/product/sales', [AdminGenerateReportController::class, 'showProductSales'])->name('admin.product.sales');

    // payments
    Route::get('/admin/payment', [AdminPaymentsController::class, 'index'])->name('admin.payments');
    Route::get('/admin/payment/sales', [AdminPaymentsController::class, 'salesPayments'])->name('admin.payment.sales');
    Route::get('/admin/payment/purchase', [AdminPaymentsController::class, 'purchasePayments'])->name('admin.payment.purchase');
    Route::put('/shop/make/sale/{id}/payment', [AdminPaymentsController::class, 'addNewShopPayment'])->name('shop.payment.products');
    Route::put('/shop/make/purchase/{id}/payment', [AdminPaymentsController::class, 'addNewPurchasePayment'])->name('purchase.payment.products');
    Route::get('/shop/make/shops/{id}/payment', [AdminPaymentsController::class, 'getShopsPayment'])->name('get.shop.payment.data');
    Route::get('/shop/make/purchase/{id}/payment', [AdminPaymentsController::class, 'purchasePaymentsData'])->name('make.purchase.payment');
    Route::get('/admin/payment/shops-data', [AdminPaymentsController::class, 'shopsPaymentsData'])->name('get.sales.payment.data');

    Route::get('/admin/shops/report-data', [AdminPaymentsController::class, 'getSalesReport'])->name('get.sales.payment.by.ref');
    Route::get('/admin/ref/collection/report-data', [AdminPaymentsController::class, 'getCollectionReport'])->name('get.sales.collection.by.ref');
    Route::get('/admin/product/report-data', [AdminProductController::class, 'getProductReport'])->name('admin.product.report');

    // excel report
    Route::get('/export/stock', [ExcelExportController::class, 'getStockReport'])->name('admin.stock.report');
    Route::get('/admin/sales/report/by/ref', [ExcelExportController::class, 'getSalesReport'])->name('admin.sales.report.by.ref');
    Route::get('/admin/collection/report/by/ref', [ExcelExportController::class, 'getCollectionReport'])->name('admin.collection.report.by.ref');
    Route::get('/admin/product/report', [ExcelExportController::class, 'getProductReport'])->name('admin.sales.product.report');

    Route::get('/export/monthly/sales', [ExcelExportController::class, 'getMonthlySalesReport'])->name('admin.monthly.sales.report');
});
