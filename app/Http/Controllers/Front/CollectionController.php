<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Interfaces\CollectionInterface;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Collection;
use App\Models\Product;
use DB;
class CollectionController extends Controller
{
    public function __construct(CollectionInterface $collectionRepository)
    {
        $this->collectionRepository = $collectionRepository;
    }

    public function detail(Request $request, $slug)
    {
        // dd($request->all());
        $data = $this->collectionRepository->getCollectionBySlug($slug);
        $sizes = $this->collectionRepository->getAllSizes();
        $colors = $this->collectionRepository->getAllColors();
		$range = Product::selectRaw('MIN(offer_price) AS min, MAX(offer_price) AS max')->first();
        $sizeData=DB::select("SELECT s.id AS id, s.name AS name FROM `sizes` s
        INNER JOIN product_color_sizes pc ON pc.size = s.id
        INNER JOIN products p ON p.id = pc.product_id
        INNER JOIN collections c ON c.id = p.collection_id
        WHERE c.slug = '$data->slug' GROUP BY s.name ORDER BY s.id;");

        $colorData=DB::select("SELECT co.id AS id, co.name AS name,co.code AS code FROM `colors` co
        INNER JOIN product_color_sizes pc ON pc.color = co.id
        INNER JOIN products p ON p.id = pc.product_id
        INNER JOIN collections c ON c.id = p.collection_id
        WHERE c.slug = '$data->slug' GROUP BY co.name ORDER BY co.id;");

        $styleNo=Product::select('style_no')->where('collection_id',$data->id)->where('status',1)->get();
        if ($data) {
            return view('front.collection.detail', compact('data', 'sizes', 'colors','range','sizeData','colorData','styleNo'));
        } else {
            return view('front.404');
        }
    }

    public function filter(Request $request)
    {
        $data = $this->collectionRepository->productsByCollection($request->collectionId, $request->except('_token'));

        if ($data) {
            return response()->json(['status' => 200, 'message' => 'Products found', 'data' => $data], 200);
        } else {
            return response()->json(['status' => 400, 'message' => 'No products found'], 400);
        }
    }
}