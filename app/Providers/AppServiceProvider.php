<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Auth;
use App\Models\Category;
use App\Models\CategoryParent;
use App\Models\Collection;
use App\Models\Settings;
use App\Models\Cart;
use App\Models\Wishlist;
use App\Http\View\Composers\CategoryComposer;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        View::composer('*', CategoryComposer::class);
        view::composer('*', function($view) {
            //count cart total
            $cartCount = auth()->check() ? Cart::where('user_id', auth()->id())->count() : 0;
            $view->with('cartCount', $cartCount);





            $ip = $_SERVER['REMOTE_ADDR'];

            // categories
            $categoryExists = Schema::hasTable('categories');
            if ($categoryExists) {
                $categories = Category::with('parentCatDetails')->orderBy('position', 'asc')->where('status', 1)->get();
                // $categories = Category::with('parentCatDetails')->orderBy('parent', 'asc')->orderBy('position', 'asc')->where('status', 1)->get();

                // dd($categories);

                $categoryNavList = [];

                foreach ($categories as $catKey => $catValue) {
                    if (in_array_r($catValue->parentCatDetails ? $catValue->parentCatDetails->name : '', $categoryNavList)) continue;

                    $childCategories = Category::select('slug', 'name', 'sketch_icon', 'image_path')->where('parent', $catValue->parent)->orderBy('position', 'asc')->where('status', 1)->get()->toArray();

                    $categoryNavList[] = [
                        'parent' => $catValue->parentCatDetails ? $catValue->parentCatDetails->name : '',
                        // 'parent' => $catValue->parent,
                        // 'parent' => $categoryParent->name,
                        'child' => $childCategories
                    ];
                }
            }

            // dd($categoryNavList);

            // collections
            $collectionExists = Schema::hasTable('collections');
            if ($collectionExists) {
                $collections = Collection::orderBy('position', 'asc')->orderBy('id', 'desc')->where('status', 1)->get();
            }

            // settings
            $settingsExists = Schema::hasTable('settings');
            if ($settingsExists) {
                $settings = Settings::where('status', 1)->get();
            }
            // Base URL
            $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "http://" : "http://";
    
            // Get the host
            $host = $_SERVER['HTTP_HOST'];
            
            // Get the current script directory
            $scriptDir = dirname($_SERVER['SCRIPT_NAME']);
            
            // Ensure there is a trailing slash
            $scriptDir = rtrim($scriptDir, '/\\') . '/';
            
            // Construct the base URL
            $base_url = $protocol . $host . $scriptDir;

            // cart count
            $cartExists = Schema::hasTable('carts');
            if ($cartExists) {
                if (Auth::guard('web')->check()) {
                    $cartCount = Cart::where('user_id', Auth::guard('web')->user()->id)->get();
                } else {
                    if (!empty($_COOKIE['cartToken'])) {
                        $cartCount = Cart::where('guest_token', $_COOKIE['cartToken'])->get();
                    } else {
                        $cartCount = [];
                    }
                }
                $totalCartProducts = 0;
                if(count($cartCount) > 0) {
                    foreach($cartCount as $cartKey => $cartVal) {
                        $totalCartProducts += $cartVal->qty;
                    }
                }

                // $cartCount = Cart::where('ip', $ip)->get();

                // $totalCartProducts = 0;
                // foreach($cartCount as $cartKey => $cartVal) {
                //     $totalCartProducts += $cartVal->qty;
                // }
            }

            // wishlist count
            $wishlistExists = Schema::hasTable('wishlists');
            if ($wishlistExists) {
                if (Auth::guard('web')->check()) {
                    $user_id = Auth::guard('web')->user()->id;
                    $wishlistCount = Wishlist::where('user_id', $user_id)->count();
                } else {
                    $wishlistCount = 0;
                }
            }

            view()->share('categories', $categories);
            view()->share('categoryNavList', $categoryNavList);
            view()->share('collections', $collections);
            view()->share('settings', $settings);
            view()->share('cartCount', $totalCartProducts);
            view()->share('wishlistCount', $wishlistCount);
            view()->share('base_url', $base_url);
        });


        Paginator::useBootstrap();
    }
}
