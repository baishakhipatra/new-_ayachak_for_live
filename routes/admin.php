<?php

use App\Http\Controllers\Admin\{AdminUserManagementController, PageController, MenuController,CSRProjectController, TagController,DonationController, ProductController, EventController};

// admin guard
use Illuminate\Support\Facades\Route;
 // product
Route::post('/update', 'Admin\ProductController@update')->name('admin.product.update');

Route::get('/admin', function () {
    if (auth('admin')->check()) {
        return redirect()->route('admin.login');
    }   
});
Route::prefix('admin')->name('admin.')->middleware('prevent-back-history')->group(function () {
    Route::middleware(['guest:admin'])->group(function () {
        Route::view('/login', 'admin.auth.login')->name('login');
        Route::post('/check', 'Admin\AdminController@check')->name('login.check');
    });
    

    Route::middleware(['auth:admin'])->group(function () {
        // dashboard
        Route::get('/', function () {
            return redirect()->route('admin.home');
        });
        Route::get('/home', 'Admin\AdminController@home')->name('home');
        Route::post('/logout', 'Admin\AdminController@logout')->name('logout');

        // Change password
        Route::view('/change/password','admin.auth.change-password')->name('change.password');
        Route::post('/update/password','Admin\AdminController@updatePassword')->name('update.password');

        //admin-user-management
        Route::prefix('admin-user-management')->group(function () {
            Route::get('/', [AdminUserManagementController::class, 'index'])->name('admin-user-management.index');
            Route::get('/create', [AdminUserManagementController::class, 'create'])->name('admin-user-management.create');
            Route::post('/store', [AdminUserManagementController::class, 'store'])->name('admin-user-management.store');
            Route::get('/show/{id}', [AdminUserManagementController::class, 'show'])->name('admin-user-management.show');
            Route::get('/edit/{id}', [AdminUserManagementController::class, 'edit'])->name('admin-user-management.edit');
            Route::post('/update', [AdminUserManagementController::class, 'update'])->name('admin-user-management.update');
            Route::get('/status/{id}', [AdminUserManagementController::class, 'status'])->name('admin-user-management.status');
            Route::post('/delete', [AdminUserManagementController::class, 'delete'])->name('admin-user-management.delete');
            Route::get('/export', [AdminUserManagementController::class, 'export'])->name('admin-user-management.export');
        });

        //CMS module
        Route::prefix('pages')->group(function () {
            Route::get('/', [PageController::class, 'index'])->name('pages.index');
            Route::get('/create', [PageController::class, 'create'])->name('pages.create');
            Route::post('/store', [PageController::class, 'store'])->name('pages.store');
            Route::get('/view/{id}', [PageController::class, 'show'])->name('pages.show');
            Route::get('/edit/{id}', [PageController::class, 'edit'])->name('pages.edit');
            Route::post('/update/{id}', [PageController::class, 'update'])->name('pages.update');
            Route::get('/status/{id}', [PageController::class, 'status'])->name('pages.status');
            Route::post('/delete', [PageController::class, 'delete'])->name('pages.delete');


            Route::get('menus', [MenuController::class, 'index'])->name('menus.index');
            Route::post('menus', [MenuController::class, 'store'])->name('menus.store');
            Route::post('menus/update-order', [MenuController::class, 'updateOrder'])->name('menus.updateOrder');
        });

        Route::prefix('csr-projects')->group(function() {
            Route::get('/', [CSRProjectController::class, 'index'])->name('csr_projects.index');
            Route::get('/create', [CSRProjectController::class, 'create'])->name('csr_projects.create');
            Route::post('/store', [CSRProjectController::class, 'store'])->name('csr_projects.store');
            Route::get('/edit/{id}', [CSRProjectController::class, 'edit'])->name('csr_projects.edit');
            Route::post('/update/{id}', [CSRProjectController::class, 'update'])->name('csr_projects.update');
            Route::post('/delete', [CSRProjectController::class, 'delete'])->name('csr_projects.delete');
        });

        Route::prefix('tags')->group(function() {
            Route::get('/', [TagController::class, 'index'])->name('tags.index');
            Route::get('/create', [TagController::class, 'create'])->name('tags.create');
            Route::post('/store', [TagController::class, 'store'])->name('tags.store');
            Route::get('/edit/{id}', [TagController::class, 'edit'])->name('tags.edit');
            Route::post('/update/{id}', [TagController::class, 'update'])->name('tags.update');
            Route::post('/delete', [TagController::class, 'delete'])->name('tags.delete');
        });

        //donations
        Route::prefix('donations')->group(function() {
            Route::get('/', [DonationController::class, 'index'])->name('donations.index');
            Route::get('/create', [DonationController::class, 'create'])->name('donations.create');
            Route::post('/store', [DonationController::class, 'store'])->name('donations.store');
            Route::get('/show/{id}', [DonationController::class, 'show'])->name('donations.show');
            Route::get('/export', [DonationController::class, 'Export'])->name('donations.export');
        });

        //events
          Route::prefix('events')->group(function() {
            Route::get('/', [EventController::class, 'index'])->name('events.index');
            Route::get('/create', [EventController::class, 'create'])->name('events.create');
            Route::post('/store', [EventController::class, 'store'])->name('events.store');
            Route::get('/status/{id}', [EventController::class, 'status'])->name('events.status');
            Route::get('/edit/{id}', [EventController::class, 'edit'])->name('events.edit');
            Route::post('/update', [EventController::class, 'update'])->name('events.update');
            Route::post('/delete', [EventController::class, 'delete'])->name('events.delete');
        });


        // category
        Route::prefix('category')->name('category.')->group(function () {
            Route::get('/', 'Admin\CategoryController@index')->name('index');
            // Route::get('/active', 'Admin\CategoryController@activeCategory')->name('active');
            // Route::get('/inactive', 'Admin\CategoryController@inactiveCategory')->name('inactive');
            Route::post('/store', 'Admin\CategoryController@store')->name('store');
            Route::get('/{id}/view', 'Admin\CategoryController@show')->name('view');
            Route::post('/{id}/update', 'Admin\CategoryController@update')->name('update');
            Route::get('/{id}/status', 'Admin\CategoryController@status')->name('status');
            Route::get('/{id}/delete', 'Admin\CategoryController@destroy')->name('delete');
            Route::get('/bulkDelete', 'Admin\CategoryController@bulkDestroy')->name('bulkDestroy');
        });

        // sub-category
        Route::prefix('subcategory')->name('subcategory.')->group(function () {
            Route::get('/', 'Admin\SubCategoryController@index')->name('index');
            Route::post('/store', 'Admin\SubCategoryController@store')->name('store');
            Route::get('/{id}/view', 'Admin\SubCategoryController@show')->name('view');
            Route::post('/{id}/update', 'Admin\SubCategoryController@update')->name('update');
            Route::get('/{id}/status', 'Admin\SubCategoryController@status')->name('status');
            Route::get('/{id}/delete', 'Admin\SubCategoryController@destroy')->name('delete');
            Route::get('/bulkDelete', 'Admin\SubCategoryController@bulkDestroy')->name('bulkDestroy');
        });

        // collection
        Route::prefix('collection')->name('collection.')->group(function () {
            Route::get('/', 'Admin\CollectionController@index')->name('index');
            Route::post('/store', 'Admin\CollectionController@store')->name('store');
            Route::get('/{id}/view', 'Admin\CollectionController@show')->name('view');
            Route::post('/{id}/update', 'Admin\CollectionController@update')->name('update');
            Route::get('/{id}/status', 'Admin\CollectionController@status')->name('status');
            Route::get('/{id}/delete', 'Admin\CollectionController@destroy')->name('delete');
            Route::get('/bulkDelete', 'Admin\CollectionController@bulkDestroy')->name('bulkDestroy');
        });

		// color
        Route::prefix('color')->name('color.')->group(function () {
            Route::get('/', 'Admin\ColorController@index')->name('index');
			Route::get('/create', 'Admin\ColorController@create')->name('create');
            Route::post('/store', 'Admin\ColorController@store')->name('store');
            Route::get('/{id}/view', 'Admin\ColorController@show')->name('view');
			Route::get('/{id}/edit', 'Admin\ColorController@edit')->name('edit');
            Route::post('/{id}/update', 'Admin\ColorController@update')->name('update');
            Route::get('/{id}/status', 'Admin\ColorController@status')->name('status');
        });

		// size
        Route::prefix('size')->name('size.')->group(function () {
            Route::get('/', 'Admin\SizeController@index')->name('index');
			Route::get('/create', 'Admin\SizeController@create')->name('create');
            Route::post('/store', 'Admin\SizeController@store')->name('store');
            Route::get('/{id}/view', 'Admin\SizeController@show')->name('view');
			Route::get('/{id}/edit', 'Admin\SizeController@edit')->name('edit');
            Route::post('/{id}/update', 'Admin\SizeController@update')->name('update');
            Route::get('/{id}/status', 'Admin\SizeController@status')->name('status');
        });

        // coupon
        Route::prefix('coupon')->name('coupon.')->group(function () {
            Route::get('/', 'Admin\CouponController@index')->name('index');
            Route::post('/store', 'Admin\CouponController@store')->name('store');
            Route::get('/{id}/view', 'Admin\CouponController@show')->name('view');
            Route::post('/{id}/update', 'Admin\CouponController@update')->name('update');
            Route::get('/{id}/status', 'Admin\CouponController@status')->name('status');
            Route::get('/{id}/delete', 'Admin\CouponController@destroy')->name('delete');
            Route::get('/bulkDelete', 'Admin\CouponController@bulkDestroy')->name('bulkDestroy');
			Route::post('/csv/upload', 'Admin\CouponController@CSVUpload')->name('csv.upload');
        });

        // voucher
        Route::prefix('voucher')->name('voucher.')->group(function () {
            Route::get('/', 'Admin\VoucherController@index')->name('index');
            Route::get('/create', 'Admin\VoucherController@create')->name('create');
            Route::get('/csv/export', 'Admin\VoucherController@csvExport')->name('csv.export');
            Route::get('{slug}/csv/export', 'Admin\VoucherController@csvExportSlug')->name('detail.csv.export');
            Route::post('/store', 'Admin\VoucherController@store')->name('store');
            Route::get('/{id}/view', 'Admin\VoucherController@show')->name('view');
            Route::post('/{id}/update', 'Admin\VoucherController@update')->name('update');
            Route::get('/{id}/status', 'Admin\VoucherController@status')->name('status');
            Route::get('/{id}/delete', 'Admin\VoucherController@destroy')->name('delete');
            Route::get('/bulkDelete', 'Admin\VoucherController@bulkDestroy')->name('bulkDestroy');
        });

        // customer
        Route::prefix('customer')->name('customer.')->group(function () {
            Route::get('/', 'Admin\UserController@index')->name('index');
            Route::post('/store', 'Admin\UserController@store')->name('store');
            Route::get('/{id}/view', 'Admin\UserController@show')->name('view');
            Route::post('/{id}/update', 'Admin\UserController@update')->name('update');
            Route::get('/{id}/status', 'Admin\UserController@status')->name('status');
            Route::get('/{id}/delete', 'Admin\UserController@destroy')->name('delete');
            Route::get('/bulkDelete', 'Admin\UserController@bulkDestroy')->name('bulkDestroy');

            Route::get('/{id}/get-all-orders', 'Admin\UserController@perUserOrder')->name('getPerUserOrder');
        });

        Route::prefix('product')->name('product.')->group(function () {
            Route::get('/list', 'Admin\ProductController@index')->name('index');
            Route::get('/create', 'Admin\ProductController@create')->name('create');
            Route::post('/store', 'Admin\ProductController@store')->name('store');
            Route::get('/{id}/view', 'Admin\ProductController@show')->name('view');
            Route::post('/size', 'Admin\ProductController@size')->name('size');
            Route::get('/{id}/edit', 'Admin\ProductController@edit')->name('edit');
            Route::get('/{id}/status', 'Admin\ProductController@status')->name('status');
            Route::get('/{id}/sale', 'Admin\ProductController@sale')->name('sale');
            Route::get('/{id}/trending', 'Admin\ProductController@trending')->name('trending');
            Route::get('/{id}/hotdeal', 'Admin\ProductController@hotdeal')->name('hotdeal');
            Route::get('/{id}/feature', 'Admin\ProductController@feature')->name('feature');
            Route::get('/{id}/dealoftheday', 'Admin\ProductController@dealoftheday')->name('dealoftheday');
            Route::get('/{id}/delete', 'Admin\ProductController@destroy')->name('delete');
            Route::get('/{id}/image/delete', 'Admin\ProductController@destroySingleImage')->name('image.delete');
            Route::get('/bulkDelete', 'Admin\ProductController@bulkDestroy')->name('bulkDestroy');
            Route::get('/{id}/sync', 'Admin\UnicommerceController@sync')->name('unicommerce.sync');
            Route::get('/{id}/sync/single', 'Admin\UnicommerceController@syncSingle')->name('unicommerce.sync.single');
            Route::get('/export/all', 'Admin\ProductController@exportAll')->name('export.all');

            // variation
            Route::post('/variation/color/add', 'Admin\ProductController@variationColorAdd')->name('variation.color.add');
            Route::post('/variation/color/position', 'Admin\ProductController@variationColorPosition')->name('variation.color.position');
            Route::post('/variation/color/status/toggle', 'Admin\ProductController@variationStatusToggle')->name('variation.color.status.toggle');
            Route::post('/variation/color/edit', 'Admin\ProductController@variationColorEdit')->name('variation.color.edit');
            Route::post('/variation/color/rename', 'Admin\ProductController@variationColorRename')->name('variation.color.rename');
            Route::post('/variation/color/fabric/upload', 'Admin\ProductController@variationFabricUpload')->name('variation.color.fabric.upload');
            Route::get('/variation/{productId}/color/{colorId}/delete', 'Admin\ProductController@variationColorDestroy')->name('variation.color.delete');
            Route::post('/variation/size/add', 'Admin\ProductController@variationSizeUpload')->name('variation.size.add');   
            Route::post('/variation/size/edit', 'Admin\ProductController@variationSizeEdit')->name('variation.size.edit');
            Route::get('/variation/{id}/size/remove', 'Admin\ProductController@variationSizeDestroy')->name('variation.size.delete');
            Route::post('/variation/image/add', 'Admin\ProductController@variationImageUpload')->name('variation.image.add');
            Route::post('/variation/image/remove', 'Admin\ProductController@variationImageDestroy')->name('variation.image.delete');
            Route::post('/csv/variation/upload', 'Admin\ProductController@variationCSVUpload')->name('variation.csv.upload');
            Route::get('/csv/variation/export', 'Admin\ProductController@ProductColorSizeVariationExportCSV')->name('variation.csv.export');
            Route::post('/csv/product/upload', 'Admin\ProductController@productvariationCSVUpload')->name('csv.upload');
            Route::post('/bulk/edit', 'Admin\ProductController@variationBulkEdit')->name('variation.bulk.edit');
            Route::post('/bulk/update', 'Admin\ProductController@variationBulkUpdate')->name('variation.bulk.update');
            // Route::get('/variation/{id}/image/remove', 'Admin\ProductController@variationImageDestroy')->name('variation.image.delete');

            Route::get('/sku-list', 'Admin\ProductController@productSkuList')->name('sku_list');
            Route::get('/edit/{id}', [ProductController::class, 'ProductSkuEdit'])->name('variation.edit');
            Route::post('/update', [ProductController::class, 'ProductSkuUpdate'])->name('variation.update');
            Route::post('upload-images',[ProductController::class, 'uploadImages'])->name('variation.uploadImages');
            Route::get('variation-images/{id}', [ProductController::class, 'getVariationImages'])->name('variation.getImages');
            Route::delete('variation-image/{id}', [ProductController::class, 'deleteVariationImage'])->name('variation.deleteImage');
            Route::get('/status/{id}', [ProductController::class, 'ProductSkuStatus'])->name('variation.status');
            Route::post('/delete', [ProductController::class, 'ProductSkuDelete'])->name('variation.delete');
            Route::post('variation/import', 'Admin\ProductController@productSkuListImport')->name('variation.import');
            Route::get('/sku-list/export', 'Admin\ProductController@productSkuListExport')->name('sku_list.export');
            // Route::get('/sku-list/sync/all', 'Admin\ProductController@productSkuListSyncAll')->name('sku_list.sync.all');
            // Route::get('/sku-list/sync/all/start', 'Admin\UnicommerceController@syncAllStart')->name('sku_list.sync.all.start');
            // Route::get('/sku-list/sync/all/report', 'Admin\UnicommerceController@syncAllreport')->name('sku_list.sync.all.report');
            // Route::get('/sku-list/sync/all/report/{id}', 'Admin\UnicommerceController@syncAllreportDetail')->name('sku_list.sync.all.report.detail');
            // Route::get('/sku-list/sync/all/report/{id}/export', 'Admin\UnicommerceController@syncAllreportDetailExport')->name('sku_list.sync.all.report.detail.export');
        });

       


        // address
        Route::prefix('address')->name('address.')->group(function () {
            Route::get('/', 'Admin\AddressController@index')->name('index');
            Route::post('/store', 'Admin\AddressController@store')->name('store');
            Route::get('/{id}/view', 'Admin\AddressController@show')->name('view');
            Route::post('/{id}/update', 'Admin\AddressController@update')->name('update');
            Route::get('/{id}/status', 'Admin\AddressController@status')->name('status');
            Route::get('/{id}/delete', 'Admin\AddressController@destroy')->name('delete');
            Route::get('/bulkDelete', 'Admin\AddressController@bulkDestroy')->name('bulkDestroy');
        });

        // faq
        Route::prefix('faq')->name('faq.')->group(function () {
            Route::get('/', 'Admin\FaqController@index')->name('index');
            Route::post('/store', 'Admin\FaqController@store')->name('store');
            Route::get('/{id}/view', 'Admin\FaqController@show')->name('view');
            Route::post('/{id}/update', 'Admin\FaqController@update')->name('update');
            Route::get('/{id}/status', 'Admin\FaqController@status')->name('status');
            Route::get('/{id}/delete', 'Admin\FaqController@destroy')->name('delete');
        });

		// banner
        Route::prefix('banner')->name('banner.')->group(function () {
            Route::get('/', 'Admin\BannerController@index')->name('index');
            Route::post('/store', 'Admin\BannerController@store')->name('store');
            Route::get('/{id}/view', 'Admin\BannerController@show')->name('view');
            Route::post('/{id}/update', 'Admin\BannerController@update')->name('update');
            Route::post('/delete', 'Admin\BannerController@destroy')->name('delete');
        });

        // settings
        Route::prefix('settings')->name('settings.')->group(function () {
            Route::get('/', 'Admin\SettingsController@index')->name('index');
            Route::post('/store', 'Admin\SettingsController@store')->name('store');
            Route::get('/{id}/view', 'Admin\SettingsController@show')->name('view');
            Route::post('/{id}/update', 'Admin\SettingsController@update')->name('update');
            Route::get('/{id}/status', 'Admin\SettingsController@status')->name('status');
            Route::get('/{id}/delete', 'Admin\SettingsController@destroy')->name('delete');
            Route::get('/bulkDelete', 'Admin\SettingsController@bulkDestroy')->name('bulkDestroy');
            
        });

        // order
        Route::prefix('order')->name('order.')->group(function () {
            Route::get('/', 'Admin\OrderController@index')->name('index');
            Route::post('/store', 'Admin\OrderController@store')->name('store');
            Route::get('/{id}/view', 'Admin\OrderController@show')->name('view');
            Route::get('/{id}/invoice', 'Admin\OrderController@invoice')->name('invoice');
            Route::post('/{id}/update', 'Admin\OrderController@update')->name('update');
            Route::get('/{id}/status/{status}', 'Admin\OrderController@status')->name('status');
            Route::get('/{id}/type/{type}', 'Admin\OrderController@type')->name('type');
            Route::post('/status', 'Admin\OrderController@statusPost')->name('status');
            Route::post('/product/status', 'Admin\OrderController@orderProductStatus')->name('product.status');
            Route::get('/export/all', 'Admin\OrderController@exportAll')->name('export.all');

            // report
            Route::get('/report', 'Admin\OrderController@report')->name('report');

            // remark
            Route::get('/{id}/remark', 'Admin\OrderRemarkController@fetch')->name('remark.show');
            Route::post('/remark/add', 'Admin\OrderRemarkController@add')->name('remark.add');
			//transaction capture
			Route::get('/{id}/transaction/capture', 'Admin\OrderController@transactionCapture')->name('transaction.capture');
        });

        // transaction
        Route::prefix('transaction')->name('transaction.')->group(function () {
            Route::get('/', 'Admin\TransactionController@index')->name('index');
            Route::get('/{id}/view', 'Admin\TransactionController@show')->name('view');
        });

        // gallery
        Route::prefix('gallery')->name('gallery.')->group(function () {
            Route::get('/', 'Admin\GalleryController@index')->name('index');
            Route::post('/store', 'Admin\GalleryController@store')->name('store');
            Route::get('/{id}/view', 'Admin\GalleryController@show')->name('view');
            Route::post('/{id}/update', 'Admin\GalleryController@update')->name('update');
            Route::get('/{id}/status', 'Admin\GalleryController@status')->name('status');
            Route::get('/{id}/delete', 'Admin\GalleryController@destroy')->name('delete');
        });

        // mail
        Route::prefix('subscription/mail')->name('subscription.mail.')->group(function () {
            Route::get('/', 'Admin\SubscriptionMailController@index')->name('index');
            Route::post('/comment/add', 'Admin\SubscriptionMailController@comment')->name('comment.add');
        });

        // franchise
        Route::prefix('franchise')->name('franchise.')->group(function () {
            Route::get('/', 'Admin\FranchiseController@index')->name('index');
            Route::get('/{id}/details', 'Admin\FranchiseController@details')->name('details');
            Route::post('/comment/add', 'Admin\FranchiseController@comment')->name('comment.add');
        });

        // Promotions
        Route::prefix('promotions')->name('promotion.')->group(function(){
            Route::get('/', 'Admin\PromotionController@index')->name('index');
        });
		
		// product
        Route::prefix('/scanandwin/Qr')->name('scanandwin.')->group(function () {
            Route::get('/', 'Admin\QRcodeController@index')->name('index');
            Route::get('/create', 'Admin\QRcodeController@create')->name('create');
            Route::get('/csv/export', 'Admin\QRcodeController@csvExport')->name('csv.export');
            Route::get('{slug}/csv/export', 'Admin\QRcodeController@csvExportSlug')->name('detail.csv.export');
            Route::post('/store', 'Admin\QRcodeController@store')->name('store');
            Route::get('/{id}/view', 'Admin\QRcodeController@show')->name('view');
		    Route::get('/{id}/detail', 'Admin\QRcodeController@view')->name('show');
			Route::get('/{id}/used/gift', 'Admin\QRcodeController@useqrcode')->name('useqrcode');
			Route::get('/{id}/edit', 'Admin\QRcodeController@edit')->name('edit');
            Route::post('/{id}/update', 'Admin\QRcodeController@update')->name('update');
            Route::get('/{id}/status', 'Admin\QRcodeController@status')->name('status');
            Route::get('/{id}/delete', 'Admin\QRcodeController@destroy')->name('delete');
            Route::get('/bulkDelete', 'Admin\QRcodeController@bulkDestroy')->name('bulkDestroy');

        });
		
		
		Route::prefix('/scanandwin/gift')->name('gift.')->group(function () {
            Route::get('/', 'Admin\GiftController@index')->name('index');
            Route::get('/create', 'Admin\GiftController@create')->name('create');
            Route::get('/csv/export', 'Admin\GiftController@csvExport')->name('csv.export');
            Route::get('{slug}/csv/export', 'Admin\GiftController@csvExportSlug')->name('detail.csv.export');
            Route::post('/store', 'Admin\GiftController@store')->name('store');
            Route::get('/{id}/view', 'Admin\GiftController@show')->name('view');
		    Route::get('/{id}/detail', 'Admin\GiftController@view')->name('show');
			Route::get('/{id}/used/gift', 'Admin\GiftController@useqrcode')->name('useqrcode');
			Route::get('/{id}/edit', 'Admin\GiftController@edit')->name('edit');
            Route::post('/{id}/update', 'Admin\GiftController@update')->name('update');
            Route::get('/{id}/status', 'Admin\GiftController@status')->name('status');
            Route::get('/{id}/delete', 'Admin\GiftController@destroy')->name('delete');
            Route::post('/csv-upload', 'Admin\GiftController@CSVUpload')->name('csvupload');

        });
		
		
		Route::prefix('/scanandwin/customers')->name('customers.')->group(function () {
            Route::get('/', 'Admin\CustomerController@index')->name('index');
            Route::get('/create', 'Admin\CustomerController@create')->name('create');
            Route::get('/csv/export', 'Admin\CustomerController@csvExport')->name('csv.export');
            Route::get('{slug}/csv/export', 'Admin\CustomerController@csvExportSlug')->name('detail.csv.export');
            Route::post('/store', 'Admin\CustomerController@store')->name('store');
            Route::get('/{id}/view', 'Admin\CustomerController@show')->name('view');
		    Route::get('/{id}/detail', 'Admin\CustomerController@view')->name('show');
			Route::get('/{id}/used/gift', 'Admin\CustomerController@useqrcode')->name('useqrcode');
			Route::get('/{id}/edit', 'Admin\CustomerController@edit')->name('edit');
            Route::post('/{id}/update', 'Admin\CustomerController@update')->name('update');
            Route::get('/{id}/status', 'Admin\CustomerController@status')->name('status');
            Route::get('/{id}/delete', 'Admin\CustomerController@destroy')->name('delete');
            Route::get('/bulkDelete', 'Admin\CustomerController@bulkDestroy')->name('bulkDestroy');
			Route::get('/{id}/dispatch', 'Admin\CustomerController@dispatch')->name('dispatch');
			Route::post('/dispatch/store', 'Admin\CustomerController@dispatchStore')->name('dispatch.store');
            
        });
		
		Route::prefix('/scanandwin/cms')->name('cms.')->group(function () {
            Route::get('/', 'Admin\CustomerController@cms')->name('index');
            Route::post('/edit', 'Admin\CustomerController@cmsstore')->name('store');

        });
		
		Route::prefix('/scanandwin/terms')->name('terms.')->group(function () {
            Route::get('/', 'Admin\CustomerController@terms')->name('index');
            Route::post('/edit', 'Admin\CustomerController@termsstore')->name('store');
           
        });
		
		Route::prefix('/luxqr/cms')->name('luxqr.cms.')->group(function () {
            Route::get('/', 'Admin\LuxQRController@qr')->name('qr');
			 Route::get('/index', 'Admin\LuxQRController@index')->name('index');
            Route::post('/edit', 'Admin\LuxQRController@termsstore')->name('store');
           
        });

        Route::get('scanandwin/csv/export/ajax/', 'Admin\CustomerController@indexCSV')->name('index');
		
    });
	
});


         
			

        