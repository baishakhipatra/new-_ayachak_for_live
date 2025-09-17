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

        // Count total usage of this coupon
        $totalUsageCount = CouponUsage::where('coupon_code_id', $couponData->id)->count();
        //dd($totalUsageCount);

        if (Auth::guard('web')->check()) {
            $user = Auth::guard('web')->user();
            $couponUsageCount = CouponUsage::where('coupon_code_id', $couponData->id)
                ->where('user_id', $user->id)->count();
        } else {
            $couponUsageCount = CouponUsage::where('coupon_code_id', $couponData->id)
                ->where('ip', $this->ip)->count();
        }

        $is_coupon = ($couponData->is_coupon == 1) ? 'coupon' : 'voucher';
        //dd($is_coupon);

        if ($couponData->end_date < \Carbon\Carbon::now() || $couponData->status == 0) {
            return response()->json(['resp' => 200, 'type' => 'warning', 'message' => $is_coupon.' expired']);
        } 
        // elseif ($couponUsageCount >= $couponData->max_time_one_can_use) {
        //     return response()->json(['resp' => 200, 'type' => 'warning', 'message' => 'You cannot use this '.$is_coupon.' anymore']);
        // }
        
        if (!empty($couponData->max_time_of_use) && $couponData->max_time_of_use > 0 && $totalUsageCount >= $couponData->max_time_of_use) {
            
            return response()->json(['resp' => 200, 'type' => 'warning', 'message' => "This $is_coupon has reached its maximum usage limit"]);
        }
       
        if (!empty($couponData->max_time_one_can_use) && $couponData->max_time_one_can_use > 0 && $couponUsageCount >= $couponData->max_time_one_can_use) {
           
            return response()->json(['resp' => 200, 'type' => 'warning', 'message' => "You cannot use this $is_coupon anymore"]);
        }

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

}