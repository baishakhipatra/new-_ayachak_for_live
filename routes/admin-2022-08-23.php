<?php

// admin guard
Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware(['guest:admin'])->group(function () {
        Route::view('/login', 'admin.auth.login')->name('login');
        Route::post('/check', 'Admin\AdminController@check')->name('login.check');
    });

    Route::middleware(['auth:admin'])->group(function () {
        // dashboard
        Route::get('/home', 'Admin\AdminController@home')->name('home');
        Route::post('/logout', 'Admin\AdminController@logout')->name('logout');

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

        // coupon
        Route::prefix('coupon')->name('coupon.')->group(function () {
            Route::get('/', 'Admin\CouponController@index')->name('index');
            Route::post('/store', 'Admin\CouponController@store')->name('store');
            Route::get('/{id}/view', 'Admin\CouponController@show')->name('view');
            Route::post('/{id}/update', 'Admin\CouponController@update')->name('update');
            Route::get('/{id}/status', 'Admin\CouponController@status')->name('status');
            Route::get('/{id}/delete', 'Admin\CouponController@destroy')->name('delete');
            Route::get('/bulkDelete', 'Admin\CouponController@bulkDestroy')->name('bulkDestroy');
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
        });

        // product
        Route::prefix('product')->name('product.')->group(function () {
            Route::get('/list', 'Admin\ProductController@index')->name('index');
            Route::get('/create', 'Admin\ProductController@create')->name('create');
            Route::post('/store', 'Admin\ProductController@store')->name('store');
            Route::get('/{id}/view', 'Admin\ProductController@show')->name('view');
            Route::post('/size', 'Admin\ProductController@size')->name('size');
            Route::get('/{id}/edit', 'Admin\ProductController@edit')->name('edit');
            Route::post('/update', 'Admin\ProductController@update')->name('update');
            Route::get('/{id}/status', 'Admin\ProductController@status')->name('status');
            Route::get('/{id}/sale', 'Admin\ProductController@sale')->name('sale');
            Route::get('/{id}/trending', 'Admin\ProductController@trending')->name('trending');
            Route::get('/{id}/delete', 'Admin\ProductController@destroy')->name('delete');
            Route::get('/{id}/image/delete', 'Admin\ProductController@destroySingleImage')->name('image.delete');
            Route::get('/bulkDelete', 'Admin\ProductController@bulkDestroy')->name('bulkDestroy');

            // variation
            // Route::post('/variation/color/add', 'Admin\ProductController@variationColorAdd')->name('variation.color.add');
            // Route::get('/variation/{productId}/color/{colorId}/delete', 'Admin\ProductController@variationColorDestroy')->name('variation.color.delete');
            Route::post('/variation/color/edit', 'Admin\ProductController@variationColorEdit')->name('variation.color.edit');
			Route::post('/variation/color/rename', 'Admin\ProductController@variationColorRename')->name('variation.color.rename');
            // Route::post('/variation/color/position', 'Admin\ProductController@variationColorPosition')->name('variation.color.position');
            // Route::post('/variation/color/status/toggle', 'Admin\ProductController@variationStatusToggle')->name('variation.color.status.toggle');
            // Route::post('/variation/size/add', 'Admin\ProductController@variationSizeUpload')->name('variation.size.add');
            Route::post('/variation/size/edit', 'Admin\ProductController@variationSizeEdit')->name('variation.size.edit');
            // Route::get('/variation/{id}/size/remove', 'Admin\ProductController@variationSizeDestroy')->name('variation.size.delete');
            // Route::post('/variation/image/add', 'Admin\ProductController@variationImageUpload')->name('variation.image.add');
            // Route::post('/variation/image/remove', 'Admin\ProductController@variationImageDestroy')->name('variation.image.delete');

            // variation
            Route::post('/variation/color/add', 'Admin\ProductController@variationColorAdd')->name('variation.color.add');
            Route::get('/variation/{productId}/color/{colorId}/delete', 'Admin\ProductController@variationColorDestroy')->name('variation.color.delete');
            Route::post('/variation/color/position', 'Admin\ProductController@variationColorPosition')->name('variation.color.position');
            Route::post('/variation/color/status/toggle', 'Admin\ProductController@variationStatusToggle')->name('variation.color.status.toggle');
            Route::post('/variation/size/add', 'Admin\ProductController@variationSizeUpload')->name('variation.size.add');
            Route::get('/variation/{id}/size/remove', 'Admin\ProductController@variationSizeDestroy')->name('variation.size.delete');
            Route::post('/variation/image/add', 'Admin\ProductController@variationImageUpload')->name('variation.image.add');
            Route::post('/variation/image/remove', 'Admin\ProductController@variationImageDestroy')->name('variation.image.delete');
            // Route::get('/variation/{id}/image/remove', 'Admin\ProductController@variationImageDestroy')->name('variation.image.delete');
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
    });
});