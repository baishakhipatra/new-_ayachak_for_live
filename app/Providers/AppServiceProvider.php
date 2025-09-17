<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Auth;
use App\Models\Category;
use App\Models\Collection;
use App\Models\Settings;
use App\Models\Cart;
use App\Models\Wishlist;
use App\Http\View\Composers\CategoryComposer;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('*', CategoryComposer::class);

        View::composer('*', function ($view) {
            $categories      = collect();
            $categoryNavList = [];
            $collections     = collect();
            $settings        = collect();
            $cartCount = 0;
            $wishlistCount     = 0;

            $ip = request()->ip();
            $systemIp = getHostByName(getHostName());     // server/system IP

            /** -------------------------------
             * Categories
             * ----------------------------- */
            if (Schema::hasTable('categories')) {
                $categories = Category::with('parentCatDetails')
                    ->where('status', 1)
                    ->orderBy('position', 'asc')
                    ->get();

                foreach ($categories as $catValue) {
                    if (in_array_r($catValue->parentCatDetails?->name, $categoryNavList)) {
                        continue;
                    }

                    $childCategories = Category::select('slug', 'name', 'sketch_icon', 'image_path')
                        ->where('parent', $catValue->parent)
                        ->where('status', 1)
                        ->orderBy('position', 'asc')
                        ->get()
                        ->toArray();

                    $categoryNavList[] = [
                        'parent' => $catValue->parentCatDetails?->name ?? '',
                        'child'  => $childCategories,
                    ];
                }
            }

            /** -------------------------------
             * Collections
             * ----------------------------- */
            if (Schema::hasTable('collections')) {
                $collections = Collection::where('status', 1)
                    ->orderBy('position', 'asc')
                    ->orderBy('id', 'desc')
                    ->get();
            }

            /** -------------------------------
             * Settings
             * ----------------------------- */
            if (Schema::hasTable('settings')) {
                $settings = Settings::where('status', 1)->get();
            }

            /** -------------------------------
             * Cart Count
             * ----------------------------- */
           if (Schema::hasTable('carts')) {

                if (Auth::check()) {
                    $userId = Auth::id();

                    // Merge guest cart (same IP + systemIp) into user cart
                    Cart::where('ip', $ip)
                        ->where('guest_token', $systemIp)
                        ->whereNull('user_id')
                        ->update([
                            'user_id' => $userId,
                            'ip' => null,
                            'guest_token' => null,
                        ]);

                    $carts = Cart::where('user_id', $userId)->get();
                } else {
                    $carts = Cart::where('ip', $ip)
                        ->where('guest_token', $systemIp)
                        ->get();
                }

                foreach ($carts as $cartItem) {
                    $cartCount++;
                }
            }


            /** -------------------------------
             * Wishlist Count
             * ----------------------------- */
            if (Schema::hasTable('wishlists')) {
                if (Auth::check()) {
                    $wishlistCount = Wishlist::where('user_id', Auth::id())->count();
                }
            }

            /** -------------------------------
             * Base URL
             * ----------------------------- */
            $protocol = request()->secure() ? "https://" : "http://";
            $host     = request()->getHost();
            $scriptDir = rtrim(dirname(request()->getScriptName()), '/\\') . '/';
            $base_url  = $protocol . $host . $scriptDir;

            /** -------------------------------
             * Share with all views
             * ----------------------------- */
            view()->share(compact(
                'categories',
                'categoryNavList',
                'collections',
                'settings',
                'cartCount',
                'wishlistCount',
                'base_url'
            ));
        });

        Paginator::useBootstrap();
    }
}
