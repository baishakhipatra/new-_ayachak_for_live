<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/dev-clear', function()
{
    \Artisan::call('optimize:clear');
    echo 'cache cleared';
});

Route::get('/home', 'HomeController@index')->name('home');

// website
Route::name('front.')->group(function () {
    // home
    Route::get('/', 'Front\FrontController@index')->name('home');
    Route::post('/subscribe', 'Front\FrontController@mailSubscribe')->name('subscription');

    // category detail
    Route::name('category.')->group(function () {
        Route::get('/category/{slug}', 'Front\CategoryController@detail')->name('detail');
        Route::post('/category/filter', 'Front\CategoryController@filter')->name('filter');
    });

    // sale
    Route::name('sale.')->group(function () {
        Route::get('/sale', 'Front\SaleController@index')->name('index');
    });

    // collection detail
    Route::name('collection.')->group(function () {
        Route::get('/collection/{slug}', 'Front\CollectionController@detail')->name('detail');
        Route::post('/collection/filter', 'Front\CollectionController@filter')->name('filter');
    });

    // product detail
    Route::name('product.')->group(function () {
        Route::get('/product/{slug}', 'Front\ProductController@detail')->name('detail');
        Route::post('/size', 'Front\ProductController@size')->name('size');
    });

    // wishlist
    Route::prefix('wishlist')->name('wishlist.')->group(function () {
        // Route::get('/', 'Front\WishlistController@viewByIp')->name('index');
        Route::post('/add', 'Front\WishlistController@add')->name('add');
        Route::post('/remove', 'Front\WishlistController@remove')->name('remove');
        Route::get('/delete/{id}', 'Front\WishlistController@delete')->name('delete');
    });

    // cart
    Route::prefix('cart')->name('cart.')->group(function () {
        Route::get('/', 'Front\CartController@viewByIp')->name('index');
        Route::post('/coupon/check', 'Front\CartController@couponCheck')->name('coupon.check');
        Route::post('/coupon/remove', 'Front\CartController@couponRemove')->name('coupon.remove');
        Route::post('/add', 'Front\CartController@add')->name('add');
        Route::get('/delete/{id}', 'Front\CartController@delete')->name('delete');
        Route::get('/quantity/{id}/{type}', 'Front\CartController@qtyUpdate')->name('quantity');
    });

    // checkout
    Route::prefix('checkout')->name('checkout.')->group(function () {
        Route::get('/', 'Front\CheckoutController@index')->name('index');
        // Route::post('/coupon/check', 'Front\CheckoutController@coupon')->name('coupon.check');
        Route::post('/store', 'Front\CheckoutController@store')->name('store');
        Route::view('/complete', 'front.checkout.complete')->name('complete');
    });

    // faq
    Route::prefix('faq')->name('faq.')->group(function () {
        Route::get('/', 'Front\FaqController@index')->name('index');
    });

    // offer
    Route::prefix('offer')->name('offer.')->group(function () {
        Route::get('/', 'Front\OfferController@index')->name('index');
    });

    // search
    Route::prefix('search')->name('search.')->group(function () {
        Route::get('/', 'Front\SearchController@index')->name('index');
    });

	// franchise
	Route::prefix('franchise')->name('franchise.')->group(function () {
        Route::get('/', 'Front\FranchiseController@index')->name('index');
        Route::post('/mail', 'Front\FranchiseController@mail')->name('mail');
        Route::post('/partner', 'Front\FranchiseController@partner')->name('partner');
    });

    // settings contents
    Route::name('content.')->group(function () {
        Route::get('/terms-and-conditions', 'Front\ContentController@termDetails')->name('terms');
        Route::get('/privacy-statement', 'Front\ContentController@privacyDetails')->name('privacy');
        Route::get('/security', 'Front\ContentController@securityDetails')->name('security');
        Route::get('/disclaimer', 'Front\ContentController@disclaimerDetails')->name('disclaimer');
        Route::get('/shipping-and-delivery', 'Front\ContentController@shippingDetails')->name('shipping');
        Route::get('/payment-voucher-promotion', 'Front\ContentController@paymentDetails')->name('payment');
        Route::get('/return-policy', 'Front\ContentController@returnDetails')->name('return');
        Route::get('/refund-policy', 'Front\ContentController@refundDetails')->name('refund');
        Route::get('/service-and-contact', 'Front\ContentController@serviceDetails')->name('service');

        Route::get('/blog', 'Front\ContentController@blog')->name('blog');
        Route::get('/blog/{slug}', 'Front\ContentController@blogDetail')->name('blog.detail');
        Route::get('/about', 'Front\ContentController@about')->name('about');
        Route::get('/contact', 'Front\ContentController@contact')->name('contact');

        Route::get('/corporate', 'Front\ContentController@corporate')->name('corporate');
        Route::get('/news', 'Front\ContentController@news')->name('news');
        Route::get('/news/{slug}', 'Front\ContentController@newsDetail')->name('news.detail');
        Route::get('/career', 'Front\ContentController@career')->name('career');
        Route::get('/global', 'Front\ContentController@global')->name('global');
    });

    // user login & registration - guard
    Route::middleware(['guest:web'])->group(function () {
        Route::prefix('user/')->name('user.')->group(function () {
            Route::get('/register', 'Front\UserController@register')->name('register');
            Route::post('/create', 'Front\UserController@create')->name('create');
            Route::get('/login', 'Front\UserController@login')->name('login');
            Route::post('/check', 'Front\UserController@check')->name('check');
        });
    });

    // profile login & registration - guard
    Route::middleware(['auth:web'])->group(function () {
        Route::prefix('user/')->name('user.')->group(function () {
            Route::view('profile', 'front.profile.index')->name('profile');
            Route::view('manage', 'front.profile.edit')->name('manage');
            Route::post('manage/update', 'Front\UserController@updateProfile')->name('manage.update');
            Route::post('password/update', 'Front\UserController@updatePassword')->name('password.update');
            Route::get('order', 'Front\UserController@order')->name('order');
            Route::get('order/{id}/invoice', 'Front\UserController@invoice')->name('invoice');
            Route::get('coupon', 'Front\UserController@coupon')->name('coupon');
            Route::get('address', 'Front\UserController@address')->name('address');
            Route::view('address/add', 'front.profile.address-add')->name('address.add');
            Route::post('address/add', 'Front\UserController@addressCreate')->name('address.create');
            Route::get('wishlist', 'Front\UserController@wishlist')->name('wishlist');
        });
    });

    // mail template test
    Route::view('/mail/1', 'front.mail.register');
    Route::view('/mail/2', 'front.mail.order-confirm');
});

Auth::routes();

Route::get('login', 'Front\UserController@login')->name('login');

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
