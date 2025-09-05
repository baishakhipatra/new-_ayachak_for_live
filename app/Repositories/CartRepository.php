<?php

namespace App\Repositories;

use App\Interfaces\CartInterface;
use App\Models\Cart;
use App\Models\Coupon;
use App\Models\CouponUsage;
use App\Models\ProductColorSize;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CartRepository implements CartInterface 
{
    public function __construct() {
        $this->ip = $_SERVER['REMOTE_ADDR'];
    }

    public function couponCheck($coupon_code)
    {
        $couponData = Coupon::where('coupon_code', $coupon_code)->first();

        if (! $couponData) {
            return response()->json(['resp' => 200, 'type' => 'error', 'message' => 'Invalid code']);
        }

        // usage count (existing logic)...
        if (Auth::guard('web')->check()) {
            $user = Auth::guard('web')->user();
            $couponUsageCount = CouponUsage::where('coupon_code_id', $couponData->id)
                ->where('user_id', $user->id)->count();
        } else {
            $couponUsageCount = CouponUsage::where('coupon_code_id', $couponData->id)
                ->where('ip', $this->ip)->count();
        }

        $is_coupon = ($couponData->is_coupon == 1) ? 'coupon' : 'voucher';

        // expiry / status check
        if ($couponData->end_date < \Carbon\Carbon::now() || $couponData->status == 0) {
            return response()->json(['resp' => 200, 'type' => 'warning', 'message' => $is_coupon.' expired']);
        } elseif ($couponUsageCount >= $couponData->max_time_one_can_use) {
            return response()->json(['resp' => 200, 'type' => 'warning', 'message' => 'You cannot use this '.$is_coupon.' anymore']);
        }

        // compute cart total using same key as your cart pages
        if (Auth::guard('web')->check()) {
            $cartData = Cart::where('user_id', $user->id)->get();
        } else {
            $cartData = Cart::where('ip', $this->ip)->get();
        }

        $totalCartAmount = 0;
        foreach ($cartData as $value) {
            $price = $value->offer_price > 0 ? $value->offer_price : $value->price;
            $totalCartAmount += ($price * $value->qty);
        }

        $couponDiscount = 0;
        if ($couponData->type == 1) {           // 1 = percentage
            $couponDiscount = ($totalCartAmount * $couponData->amount) / 100;
        } elseif ($couponData->type == 2) {     // 2 = flat
            $couponDiscount = $couponData->amount;
        }

        if (Auth::guard('web')->check()) {
            Cart::where('user_id', $user->id)->update(['coupon_code_id' => $couponData->id]);
        } else {
            Cart::where('ip', $this->ip)->update(['coupon_code_id' => $couponData->id]);
        }

        Session::put('couponCodeId', $couponData->id);

        return response()->json([
            'resp'            => 200,
            'type'            => 'success',
            'message'         => $is_coupon.' applied',
            'id'              => $couponData->id,
            'coupon_type'     => $couponData->type,      // keep using ->type
            'coupon_value'    => $couponData->amount,
            'coupon_discount' => round($couponDiscount, 2),
            'is_coupon'       => $is_coupon,
        ]);
    }


    public function couponRemove()
    {
        $cartData = Cart::where('ip', $this->ip)->update(['coupon_code_id' => null]);
        Session::forget('couponCodeId');
        return response()->json(['resp' => 200, 'type' => 'success', 'message' => 'Coupon removed']);
    }

    public function addToCart(array $data) 
    {
        $collectedData = collect($data);

        if (!empty($data['product_variation_id'])) {
            if ($collectedData['user_id'] != 0) {
                $cartExists = Cart::where('product_id', $collectedData['product_id'])->where('product_variation_id', $collectedData['product_variation_id'])->where('user_id', $collectedData['user_id'])->first();
            } else {
                $cartExists = Cart::where('product_id', $collectedData['product_id'])->where('product_variation_id', $collectedData['product_variation_id'])->where('guest_token', $collectedData['token'])->first();
            }

            $variationDetails = ProductColorSize::findOrFail($data['product_variation_id']);
            $productImageDetails = ProductImage::where([['product_id', $variationDetails->product_id], ['color_id', $variationDetails->color]])->first();

            if (!$productImageDetails) {
                $mainImage = Product::select('image')->where('id', $collectedData['product_id'])->first();
                $productImage = $mainImage->image;
            } else {
                $productImage = $productImageDetails->image;
            }
        } else {
            // $cartExists = Cart::where('product_id', $collectedData['product_id'])->where('ip', $this->ip)->first();
            if ($collectedData['user_id'] != 0) {
                $cartExists = Cart::where('product_id', $collectedData['product_id'])->where('product_variation_id', $collectedData['product_variation_id'])->where('user_id', $collectedData['user_id'])->first();
            } else {
                $cartExists = Cart::where('product_id', $collectedData['product_id'])->where('product_variation_id', $collectedData['product_variation_id'])->where('guest_token', $collectedData['token'])->first();
            }

            $productImage = $collectedData['product_image'];
        }

        if ($cartExists) {
            $cartExists->qty = $cartExists->qty + $collectedData['qty'];
            $cartExists->save();
        } else {
            $newEntry = new Cart;
            $newEntry->user_id = $collectedData['user_id'];
            $newEntry->guest_token = $collectedData['token'];
            $newEntry->product_id = $collectedData['product_id'];
            $newEntry->product_name = $collectedData['product_name'];
            $newEntry->product_style_no = $collectedData['product_style_no'];
            $newEntry->product_image = $productImage;
            $newEntry->product_slug = $collectedData['product_slug'];
            $newEntry->product_variation_id = $collectedData['product_variation_id'];

            if (isset($data['product_variation_id'])) {
                $productColorSizeData = ProductColorSize::findOrFail($collectedData['product_variation_id']);
                $newEntry->price = $productColorSizeData->price;
                $newEntry->offer_price = $productColorSizeData->offer_price;

                // dd($productColorSizeData->code);

                // unicommerce inventory check
                /*
                $loginCred = unicommerceLogin();
                $loginResp = json_decode($loginCred);
                $token = $loginResp->access_token;
                $inventory = unicommerceInventory($productColorSizeData->code, $token);
                */

                // dd($inventory);
                /* if ($inventory != true) {
                    return false;
                }
                elseif ($collectedData['qty'] > $inventory) {
                    return false;
                } */
            } else {
                $newEntry->price = $collectedData['price'];
                $newEntry->offer_price = $collectedData['offer_price'];
            }

            $newEntry->qty = $collectedData['qty'];
            $newEntry->ip = $this->ip;

            $newEntry->save();
        }

        if (Auth::guard('web')->check()) {
            $cartData = Cart::where('user_id', Auth::guard('web')->user()->id)->sum('qty');
        } else {
            $cartData = Cart::where('guest_token', $collectedData['token'])->sum('qty');
        }

		return $cartData;
    }

    public function viewByIp()
    {
        $data = Cart::where('ip', $this->ip)->get();

        // coupon check
        if (!empty($data[0]->coupon_code_id)) {
            $coupon_code_id = $data[0]->coupon_code_id;
            $coupon_code_end_date = $data[0]->couponDetails->end_date;
            $coupon_code_status = $data[0]->couponDetails->status;
            $coupon_code_max_usage_for_one = $data[0]->couponDetails->max_time_one_can_use;

            // coupon code validity check
            if ($coupon_code_end_date < \Carbon\Carbon::now() || $coupon_code_status == 0) {
                Cart::where('ip', $this->ip)->update(['coupon_code_id' => null]);
            }

            // coupon code usage check
            if (Auth::guard('web')->user()) {
                $couponUsageCount = CouponUsage::where('coupon_code_id', $coupon_code_id)
                ->where('user_id', Auth::guard('web')->user()->id)
                // ->orWhere('email', Auth::guard('web')->user()->email)
                ->count();
            } else {
                $couponUsageCount = CouponUsage::where('coupon_code_id', $coupon_code_id)->where('ip', $this->ip)->count();
            }

            if ($couponUsageCount == $coupon_code_max_usage_for_one || $couponUsageCount > $coupon_code_max_usage_for_one) {
                Cart::where('ip', $this->ip)->update(['coupon_code_id' => null]);
            }
        }

        return $data;
    }

    public function viewByUserId($id)
    {
        $data = Cart::where('user_id', $id)->get();

        // coupon check
        if (!empty($data[0]->coupon_code_id)) {
            $coupon_code_id = $data[0]->coupon_code_id;
            $coupon_code_end_date = $data[0]->couponDetails->end_date;
            $coupon_code_status = $data[0]->couponDetails->status;
            $coupon_code_max_usage_for_one = $data[0]->couponDetails->max_time_one_can_use;

            // coupon code validity check
            if ($coupon_code_end_date < \Carbon\Carbon::now() || $coupon_code_status == 0) {
                Cart::where('user_id', $id)->update(['coupon_code_id' => null]);
            }

            // coupon code usage check
            if (Auth::guard('web')->user()) {
                $couponUsageCount = CouponUsage::where('coupon_code_id', $coupon_code_id)
                ->where('user_id', Auth::guard('web')->user()->id)
                // ->orWhere('email', Auth::guard('web')->user()->email)
                ->count();
            } else {
                $couponUsageCount = CouponUsage::where('coupon_code_id', $coupon_code_id)->where('user_id', $id)->count();
            }

            if ($couponUsageCount == $coupon_code_max_usage_for_one || $couponUsageCount > $coupon_code_max_usage_for_one) {
                Cart::where('user_id', $id)->update(['coupon_code_id' => null]);
            }
        }

        return $data;
    }

    public function viewBytoken($token)
    {
        $data = Cart::where('guest_token', $token)->get();

        // coupon check
        if (!empty($data[0]->coupon_code_id)) {
            $coupon_code_id = $data[0]->coupon_code_id;
            $coupon_code_end_date = $data[0]->couponDetails->end_date;
            $coupon_code_status = $data[0]->couponDetails->status;
            $coupon_code_max_usage_for_one = $data[0]->couponDetails->max_time_one_can_use;

            // coupon code validity check
            if ($coupon_code_end_date < \Carbon\Carbon::now() || $coupon_code_status == 0) {
                Cart::where('guest_token', $token)->update(['coupon_code_id' => null]);
            }

            // coupon code usage check
            if (Auth::guard('web')->user()) {
                $couponUsageCount = CouponUsage::where('coupon_code_id', $coupon_code_id)
                ->where('user_id', Auth::guard('web')->user()->id)
                // ->orWhere('email', Auth::guard('web')->user()->email)
                ->count();
            } else {
                $couponUsageCount = CouponUsage::where('coupon_code_id', $coupon_code_id)->where('ip', $this->ip)->count();
            }

            if ($couponUsageCount == $coupon_code_max_usage_for_one || $couponUsageCount > $coupon_code_max_usage_for_one) {
                Cart::where('guest_token', $token)->update(['coupon_code_id' => null]);
            }
        }

        return $data;
    }

    public function delete($id)
    {
        $data = Cart::destroy($id);
        return $data;
    }

    public function empty()
    {
        $data = Cart::where('ip', $this->ip)->delete();
        return $data;
    }

    public function qtyUpdate($id, $type) {
        $cartData = Cart::findOrFail($id);
        $qty = $cartData->qty;
        if ($type == 'incr') {
            $updatedQty = $qty+1;
        } else {
            if ($qty == 1) {$resp = 'Minimum quantity is 1';return $resp;}
            $updatedQty = $qty-1;
        }
        $cartData->qty = $updatedQty;
        $cartData->save();
        $resp = 'Cart updated';
        return $resp;
    }
}