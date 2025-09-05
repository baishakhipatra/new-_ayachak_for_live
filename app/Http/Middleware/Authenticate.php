<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    // protected function redirectTo(Request $request): ?string
    // {
    //     return $request->expectsJson() ? null : route('login');
    // }

    protected function redirectTo($request)
    {
        if (!$request->expectsJson()) {
            if ($request->is('admin') || $request->is('admin/*')) {
                return route('admin.login'); // your admin login route
            }

             // User routes (auth:web)
            if ($request->is('profile') || $request->is('wishlist/*') || $request->is('checkout') || $request->is('cart/*')) {
                return route('front.login'); // your normal user login route
            }

            // Fallback to default login page
            return route('front.login');
        }
    }
}
