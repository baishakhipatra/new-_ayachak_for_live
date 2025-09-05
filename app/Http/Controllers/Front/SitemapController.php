<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use App\Models\Product;

class SitemapController extends Controller
{
    public function product(Request $request)
    {
        $data = Product::where('status', 1)->get();
		// return view('front.sitemap.product', compact('data'))->header('Content-Type', 'application/xml');
        return response()->view('front.sitemap.product', compact('data'))->header('Content-Type', 'text/xml');
    }
}
