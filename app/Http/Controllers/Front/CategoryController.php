<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Interfaces\CategoryInterface;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Category;
use App\Models\Product;
use DB;
class CategoryController extends Controller
{
    public function __construct(CategoryInterface $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    public function detail(Request $request, $slug)
    {
        $data = $this->categoryRepository->getCategoryBySlug($slug);
        $sizes = $this->categoryRepository->getAllSizes();
        $colors = $this->categoryRepository->getAllColors();
		$range = Product::selectRaw('MIN(offer_price) AS min, MAX(offer_price) AS max')->first();
        $sizeData=DB::select("SELECT s.id AS id, s.name AS name FROM `sizes` s
        INNER JOIN product_color_sizes pc ON pc.size = s.id
        INNER JOIN products p ON p.id = pc.product_id
        INNER JOIN categories c ON c.id = p.cat_id
        WHERE c.slug = '$data->slug' GROUP BY s.name ORDER BY s.id;");

        $colorData=DB::select("SELECT co.id AS id, co.name AS name,co.code AS code FROM `colors` co
        INNER JOIN product_color_sizes pc ON pc.color = co.id
        INNER JOIN products p ON p.id = pc.product_id
        INNER JOIN categories c ON c.id = p.cat_id
        WHERE c.slug = '$data->slug' GROUP BY co.name ORDER BY co.id;");

        $styleNo=Product::select('style_no')->where('cat_id',$data->id)->get();
        if ($data) {
            return view('front.category.detail', compact('data', 'sizes', 'colors','range','sizeData','colorData','styleNo'));
        } else {
            return view('front.404');
        }
    }

    public function filter(Request $request)
    {
        $data = $this->categoryRepository->productsByCategory($request->categoryId, $request->except('_token'));

        if ($data) {
            return response()->json(['status' => 200, 'message' => 'Products found', 'data' => $data], 200);
        } else {
            return response()->json(['status' => 400, 'message' => 'No products found'], 400);
        }
    }
}