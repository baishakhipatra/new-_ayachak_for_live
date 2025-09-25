<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Interfaces\ProductInterface;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Product;
use App\Models\Cart;
use App\Models\ProductColorSize;
use App\Models\Category;
use App\Models\ProductImage;
use App\Models\ProductVariation;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function __construct(ProductInterface $productRepository) 
    {
        $this->productRepository = $productRepository;
    }
    
    public function shop(Request $request)
    {
        $categories = Category::where('status', 1)->orderBy('name', 'ASC')->get();
        $query = Product::where('status', 1);

        // If category filter is present
        $selected_category = null;
        if ($request->filled('category')) {
            $category = Category::where('slug', $request->category)->first();

            if (!$category) {
                $category = Category::whereRaw('LOWER(REPLACE(name," ","-")) = ?', [$request->category])->first();
            }

            if ($category) {
                $query->where('cat_id', $category->id);
                $selected_category = $category->id;
            }
        }

        $data = $query->orderBy('id', 'DESC')->paginate(12);

        $weights = ProductVariation::whereNotNull('weight')
            ->select('weight')
            ->distinct()
            ->pluck('weight');

        $categoryIds = $categories->pluck('id')->toArray();
        return view('front.hotDealList', compact('data', 'categories', 'weights', 'categoryIds','selected_category'));
    }


   public function ajaxFilter(Request $request)
    {
        $query = Product::where('status', 1);

        if ($request->has('categories') && !empty($request->categories)) {
            $query->whereIn('cat_id', $request->categories);
        }

        if ($request->has('weights') && !empty($request->weights)) {
            $query->whereHas('variations', function ($q) use ($request) {
                $q->whereIn('weight', $request->weights);
            });
        }

        $data = $query->orderBy('id', 'DESC')->paginate(12);

        $html = view('front.partials.filtered_products', compact('data'))->render();

        return response()->json(['html' => $html]);
    }

    public function getVariationImages(Request $request)
    {
        $variationId = $request->variation_id;

        $variation = ProductVariation::with('images')->find($variationId);

        if ($variation) {
            $images = $variation->images->map(function ($img) {
                return asset($img->image_path);
            });

            return response()->json([
                'status' => true,
                'images' => $images
            ]);
        }

        return response()->json(['status' => false, 'images' => []]);
    }


    public function detail(Request $request, $slug)
    {
        $data = $this->productRepository->listBySlug($slug);

        if ($data) {
            $images = $this->productRepository->listImagesById($data->id);
            //dd($data->id);
            $productVariations = $this->productRepository->listVariationById($data->id);
            $relatedProducts = $this->productRepository->relatedProducts($data->id); 
            $hasStock = $productVariations->where('stock', '>', 0)->count() > 0;   
            return view('front.productDetails', compact('data', 'images', 'productVariations', 'relatedProducts','hasStock'));
        } else {
            return view('front.404');
        }
    }

    // public function AddToCart(Request $request)
    // {
    //     $maxQuantity = 5;
    //     $userId = Auth::guard('web')->id();
    //     $guestToken = !$userId ? getGuestToken() : null;
    //     //dd(session('guest_token'));

    //     $cartQuery = Cart::query()
    //         ->when($userId, fn($q) => $q->where('user_id', $userId))
    //         ->when(!$userId, fn($q) => $q->where('guest_token', $guestToken));

    //     $QuantityExistsInCart = $cartQuery
    //         ->where('product_id', $request->productId)
    //         ->where('product_variation_id', $request->variationId)
    //         ->sum('qty');

    //     $remainingQuantity = $maxQuantity - $QuantityExistsInCart;

    //     if ($remainingQuantity == 0) {
    //         return redirect()->back()->with('warning', 'You already added 5 quantities for this product variation.');
    //     }

    //     $quantityToAdd = min($request->quantity, $remainingQuantity);

    //     $request->validate([
    //         'quantity' => 'required|max:5|min:1',
    //     ]);

    //     $image = '';
    //     if ($colorId) {
    //         $productImage = ProductImage::where('color_id', $colorId->color)
    //             ->where('product_id', $request->productId)
    //             ->first();
    //         $image = $productImage->image ?? '';
    //     }

    //     for ($i = 0; $i < $quantityToAdd; $i++) {
    //         $cart = new Cart();
    //         $cart->user_id = $userId;
    //         $cart->guest_token = $guestToken;
    //         $cart->product_id = $request->productId;
    //         $cart->product_name = $request->productName;
    //         $cart->product_style_no = $request->productStyleNo;
    //         $cart->product_slug = $request->productSlug;
    //         $cart->product_variation_id = $request->variationId;
    //         $cart->price = $request->price;
    //         $cart->offer_price = $request->offer_price;
    //         $cart->qty = 1;
    //         $cart->product_image = $image;
    //         $cart->save();
    //     }

    //     return redirect()->back()->with('success', "$quantityToAdd items successfully added to your cart.");
    // }


    public function details(Request $request, $slug)
    {
        $data = $this->productRepository->getProductDetailsBySlug($slug);
       
        $categoryWiseProducts = Product::inRandomOrder()->take(4)->where('status',1)->get();
        $productVariations = $this->productRepository->listVariationById($data->id);
        $hasStock = $productVariations->where('stock', '>', 0)->count() > 0; 
        
        return view('front.productDetails', compact('data','categoryWiseProducts','hasStock','productVariations'));
    }
}
