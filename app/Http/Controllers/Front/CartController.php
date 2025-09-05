<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Interfaces\CartInterface;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Product;
use App\Models\ProductVariation;
use App\Models\Cart;
use App\Models\Checkout;
use App\Models\CheckoutProduct;
use App\Models\CartOffer;
use App\Models\ProductColorSize;
use App\Models\Coupon;
use Illuminate\Support\Facades\Auth;
use DB;

class CartController extends Controller
{
    public function __construct(CartInterface $cartRepository) 
    {
        $this->cartRepository = $cartRepository;
    }

    public function couponCheck(Request $request)
    {
        $couponData = $this->cartRepository->couponCheck($request->code);
        return $couponData;
    }

    public function couponRemove(Request $request)
    {
        $couponData = $this->cartRepository->couponRemove();
        return $couponData;
    }

    
    public function add(Request $request) 
    {
        $product = Product::findOrFail($request->product_id);

        $hasVariation = ProductVariation::where('product_id', $product->id)->exists();

        $rules = [
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ];

        if ($hasVariation) {
            $rules['variation_id'] = 'required|exists:product_variation,id';
        }

        $request->validate($rules);

        $variation = null;
        if ($hasVariation) {
            $variation = ProductVariation::findOrFail($request->variation_id);
        }

        $existingCart = Cart::where('product_id', $product->id)->where('user_id', auth()->id());
       // dd($existingCart);
        
        if ($variation) {
            $existingCart = $existingCart->where('product_variation_id', $variation->id);
        }

        $existingCart = $existingCart->first();

        
        if ($existingCart) {
            return back()->with('warning', 'Product already in cart!');
        }
        Cart::create([
            'user_id' => auth()->id(),
            'product_id' => $product->id,
            'product_name' => $product->name,
            'product_style_no' => $product->style_no,
            'product_image' => $product->image,
            'product_slug' => $product->slug,
            'product_variation_id' => $variation?->id,
            'price' => $variation?->price ?? $product->price,
            'offer_price' => $variation?->offer_price ?? $product->offer_price,
            'qty' => $request->quantity,
        ]);

        return back()->with('success', 'Product added to cart!');
    }

    public function index(Request $request){
        $userId = auth()->id(); 
        $cartItems = Cart::where('user_id', $userId)->with(['productDetails','variation'])->get();

        $categories = $cartItems->pluck('productDetails.category.name')->unique()->toArray();
        $Books = in_array('Book', $categories);
        $Medicines = in_array('Medicine', $categories);
        $Waters = in_array('water', $categories);

        $checkoutRestricted = false;
        if ($Books && ($Medicines || $Waters)) {
            $checkoutRestricted = true;
        }

        return view('front.cartList', compact('cartItems','checkoutRestricted'));
    }

    private function checkRestrictedCategories()
    {
        $cartItems = Cart::where('user_id', auth()->id())
            ->with(['productDetails.category'])
            ->get();

        $categories = $cartItems->pluck('productDetails.category.name')->unique()->toArray();
        $restrictedCategories = ['Book', 'Medicine', 'water'];
        $restrictedInCart = array_intersect($categories, $restrictedCategories);

        return count($restrictedInCart) > 1; 
    }

    public function updateQuantity(Request $request)
    {
        $cart = Cart::find($request->cart_id);

        if ($cart) {
            if ($request->type == 'increment') {
                if ($cart->qty < 10) {
                    $cart->qty += 1;
                }
            } elseif ($request->type == 'decrement') {
                if ($cart->qty > 1) {
                    $cart->qty -= 1;
                }
            }

            $cart->save();

            return response()->json([
                'success' => true,
                'updated_qty' => $cart->qty,
            ]);
        }

        return response()->json(['success' => false], 404);
    }


    public function removeQuantity(Request $request)
    {
        $cart = Cart::find($request->cart_id);

        if (!$cart) {
            return response()->json(['success' => false, 'message' => 'Cart item not found.']);
        }

        $cart->delete();

        return response()->json(['success' => true, 'checkout_restricted' => $this->checkRestrictedCategories()]);
    }


    public function add_to_checkoout(Request $request)
    {
        $userId = Auth::guard('web')->id();

        DB::beginTransaction();

        try {
            $cartItems = Cart::with(['productDetails', 'variation'])
                ->where('user_id', $userId)
                ->get();

            if ($cartItems->isEmpty()) {
                return back()->with('error', 'Your cart is empty.');
            }

           
            $subTotal = 0;
            $totalDiscount = 0;
            $totalGst = 0;
            $finalTotal = 0;
            $couponId = null;

            
            foreach ($cartItems as $item) {
                $variation = $item->variation;
                $product = $item->productDetails;
                $price = $variation->offer_price
                    ?? $variation->price
                    ?? $product->offer_price
                    ?? $product->price
                    ?? 0;

                $subTotal += $price * $item->qty;
            }

            
            $coupon = null;
            if ($request->coupon_id) {
                $coupon = Coupon::find($request->coupon_id);
            }

            if ($coupon) {
                $couponId = $coupon->id;
                if ($coupon->type == 2) {
                    // Flat discount
                    $totalDiscount = $coupon->amount;
                    $couponDiscountType = '2';
                } elseif ($coupon->type == 1) {
                    // Percentage discount
                    $totalDiscount = ($subTotal * $coupon->amount) / 100;
                    $couponDiscountType = '1';
                }
            }

        $checkout = Checkout::firstOrCreate(
            ['user_id' => $userId],
            [
                'sub_total_amount' => 0,
                'discount_amount'  => 0,
                'gst_amount'       => 0,
                'final_amount'     => 0,
                'coupon_id'        => $couponId,
            ]
        );

        $checkoutId = Checkout::where('user_id', $userId)->first();
        //dd($checkoutId->id);
        CheckoutProduct::where('checkout_id',$checkoutId->id)->delete();
           // dd($checkout);

            foreach ($cartItems as $item) {
                $variation = $item->variation;
                $product = $item->productDetails;

                $price = $variation->offer_price
                    ?? $variation->price
                    ?? $product->offer_price
                    ?? $product->price
                    ?? 0;

                $gstPercent = $product->gst ?? 0;
                $gstAmount  = ($price * $gstPercent) / 100;

                $finalPrice = $price + $gstAmount;

                $totalGst   += $gstAmount * $item->qty;
                $finalTotal += $finalPrice * $item->qty;

                CheckoutProduct::create([
                    'checkout_id'          => $checkout->id,
                    'product_id'           => $variation->product_id ?? $product->id,
                    'user_id'              => $userId,
                    'product_name'         => $product->name ?? '',
                    'product_image'        => $product->image ?? null,
                    'product_slug'         => $product->slug ?? '',
                    'product_variation_id' => $variation->id ?? null,
                    'colour_name'          => $variation->color_name ?? null,
                    'size_name'            => $variation->size_name ?? null,
                    'sku_code'             => $variation->code ?? null,
                    'coupon_code'          => $coupon ? $coupon->coupon_code : null,
                    'price'                => $variation->price ?? $product->price ?? 0,
                    'offer_price'          => $variation->offer_price ?? $product->offer_price ?? 0,
                    'gst'                  => $gstAmount,
                    'qty'                  => $item->qty,
                ]);
            }

            //dd($cartItems);

            // 🔹 Final total after discount
            $finalTotal = ($subTotal + $totalGst) - $totalDiscount;
            //dd($finalTotal);

            // 🔹 Update checkout totals
            $checkout->update([
                'sub_total_amount' => $subTotal,
                'discount_amount'  => $totalDiscount,
                'gst_amount'       => $totalGst,
                'final_amount'     => $finalTotal,
                'coupon_id'        => $couponId,
                'coupon_type'      => $coupon ? $coupon->type : null, 
                'coupon_value'     => $coupon ? $coupon->amount : null,
            ]);

            DB::commit();

            return redirect()->route('front.checkout.index')
                ->with('success', 'Items successfully added.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }


    public function delete(Request $request, $id)
    {
        $data = $this->cartRepository->delete($id);

        if ($data) {
            return redirect()->route('front.cart.index')->with('success', 'Product removed from cart');
        } else {
            return redirect()->route('front.cart.index')->with('failure', 'Something happened wrong');
        }
    }

    public function qtyUpdate(Request $request, $id, $type)
    {
        $data = $this->cartRepository->qtyUpdate($id, $type);

        if ($data) {
            return redirect()->route('front.cart.index')->with('success', $data);
        } else {
            return redirect()->route('front.cart.index')->with('failure', 'Something happened');
        }
    }
}
