<?php

namespace App\Repositories;

use App\Interfaces\CheckoutInterface;
use App\Models\Cart;
use App\User;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\Address;
use App\Models\Coupon;
use App\Models\Settings;
use App\Models\Collection;
use App\Models\Transaction;
use App\Models\CouponUsage;
use App\Models\ProductColorSize;
use App\Models\ThirdPartyPayload;
use App\Models\CartOffer;
use App\Models\OrderOffer;
use App\Models\Checkout;
use App\Models\ProductVariation;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;

class CheckoutRepository implements CheckoutInterface
{
    public function __construct() {
        $this->ip = $_SERVER['REMOTE_ADDR'];
    }

    public function viewCart()
    {
        if (Auth::guard()->check()) {
            $data = Cart::where('user_id', Auth::guard()->user()->id)->get();
        } else {
            if (!empty($_COOKIE['cartToken'])) {
                $data = Cart::where('guest_token', $_COOKIE['cartToken'])->get();
            } else {
                $data = [];
            }
        }
        
        // $data = Cart::where('ip', $this->ip)->get();

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
                // $couponUsageCount = CouponUsage::where('user_id', Auth::guard('web')->user()->id)
                // ->orWhere('email', Auth::guard('web')->user()->email)
                // ->count();

                $couponUsageCount = CouponUsage::where('coupon_code_id', $coupon_code_id)
                ->where('user_id', Auth::guard('web')->user()->id)
                // ->orWhere('email', Auth::guard('web')->user()->email)
                ->count();
            } else {
                $couponUsageCount = CouponUsage::where('coupon_code_id', $coupon_code_id)->where('ip', $this->ip)->count();
                // $couponUsageCount = CouponUsage::where('ip', $this->ip)->count();
            }

            // dd($couponUsageCount);

            if ($couponUsageCount == $coupon_code_max_usage_for_one || $couponUsageCount > $coupon_code_max_usage_for_one) {
                Cart::where('ip', $this->ip)->update(['coupon_code_id' => null]);
            }
        }

        return $data;
    }

    public function addressData()
    {
        return Address::where('user_id', Auth::guard('web')->user()->id)->get();
    }

    public function create(array $data)
    {
        DB::beginTransaction();
        try {
            $userId = auth()->id();
            $cartItems = Cart::with('productDetails')->where('user_id', $userId)->get();
            if ($cartItems->isEmpty()) {
                return false;
            }

   
            $sameAddress = isset($data['address_option']) && $data['address_option'] === 'same';

           
            $billing = json_decode($data['billing_address'], true);
            $shipping = json_decode($data['shipping_address'], true);

          
            $billingAddress = Address::create([
                'user_id' => $userId,
                'address' => $billing['address'] ?? null,
                'landmark' => $billing['billing_landmark'] ?? null,
                'state' => $billing['state'] ?? null,
                'city' => $billing['city'] ?? null,
                'pin' => $billing['pin'] ?? null,
                'country' => $billing['country'] ?? null,
                'type' => 1, 
                'billing' => 1, 
                'status' => 1,
            ]);

            $subtotal = 0.0;   
            $taxTotal = 0.0;   

            foreach ($cartItems as $item) {
                $product = $item->productDetails;
                $variation = $item->variation;
                if (!$product) continue;

                $qty = (int) $item->qty;
                $unitPrice = $variation->offer_price
                    ?? $variation->price
                    ?? $product->offer_price
                    ?? $product->price
                    ?? 0;

                $gstPercent = (float) ($product->gst ?? 0);

         
                $lineTotal = $unitPrice * $qty;
                $subtotal += $lineTotal;

              
                if ($gstPercent > 0) {
                    $gstPortion = $lineTotal * ($gstPercent / (100 + $gstPercent));
                    $taxTotal += $gstPortion;
                }
            }

            $discount = 0.0;
            $couponId = 0;
            $couponType = null;
            $couponValue = 0;
            $couponDiscountType = null;

            $latestCheckout = Checkout::where('user_id', $userId)->latest()->first();

            if ($latestCheckout && $latestCheckout->coupon_id) {
                $coupon = Coupon::find($latestCheckout->coupon_id);
                if ($coupon) {
                    $couponId = (int) $coupon->id;
                    $couponType = $coupon->type;
                    $couponValue = (float) ($coupon->amount ?? 0);

                    if ($couponType == 1) {
                       
                        $discount = ($subtotal * $couponValue) / 100;
                        $couponDiscountType = '1';
                    } elseif ($couponType == 2) {
                       
                        $discount = $couponValue;
                        $couponDiscountType = '2';
                    }
                }
            }

 
            $shippingCharges = 0.00;
            $finalAmount = max(0, ($subtotal + $shippingCharges) - $discount);


            $ipAddr  = request()->ip() ?? '0.0.0.0';


            $billing = json_decode($data['billing_address'], true);
            $data['first_name'] = $billing['first_name'] ?? null;
            $data['last_name'] = $billing['last_name'] ?? null;
            $data['email'] = $billing['email'] ?? null;
            $data['mobile'] = $billing['mobile'] ?? null;
            $data['billing_address'] = $billing['address'] ?? null;
            $data['billing_city']    = $billing['city'] ?? null;
            $data['billing_state']   = $billing['state'] ?? null;
            $data['billing_country'] = $billing['country'] ?? null;
            $data['billing_pin']     = $billing['pin'] ?? null;
            $data['billing_landmark'] = $billing['billing_landmark'] ?? null;

            $shipping = json_decode($data['shipping_address'], true);
            $data['shipping_address'] = $shipping['address'] ?? null;
            $data['shipping_city']    = $shipping['city'] ?? null;
            $data['shipping_state']   = $shipping['state'] ?? null;
            $data['shipping_country'] = $shipping['country'] ?? null;
            $data['shipping_pin']     = $shipping['pin'] ?? null;
            $data['shipping_landmark'] = $sameAddress
                ? ($billing['billing_landmark'] ?? null)
                : ($shipping['shipping_landmark'] ?? null);

            $data['alt_mobile'] = $shipping['alt_mobile'] ?? null;

            $order = Order::create([
                'order_sequence_int' => 0,                      
                'order_no' => null,
                'ip' => $ipAddr,
                'user_id' => $userId,

                'fname' => $data['first_name'] ?? null,
                'lname' => $data['last_name'] ?? null,
                'email' => $data['email'] ?? null,
                'mobile' => $data['mobile'] ?? null,
                'alt_mobile' => null,

                'billing_address_id' => 0,
                'address_type' => null,
                'billing_address' => $data['billing_address'] ?? null,
                'billing_landmark' => $data['billing_landmark'] ?? null,
                'billing_country' => $data['billing_country'] ?? null,
                'billing_state' => $data['billing_state'] ?? null,
                'billing_city' => $data['billing_city'] ?? null,
                'billing_landmark' => $data['billing_landmark'] ?? null,
                'billing_pin' => $data['billing_pin'] ?? null,
                

                'shippingSameAsBilling' => $sameAddress ? 1 : 0,

                'shipping_address_id' => 0,
                'shipping_address' => $sameAddress ? ($data['billing_address'] ?? null) : ($data['shipping_address'] ?? null),
                'shipping_country' => $sameAddress ? ($data['billing_country'] ?? null) : ($data['shipping_country'] ?? null),
                'shipping_state' => $sameAddress ? ($data['billing_state'] ?? null) : ($data['shipping_state'] ?? null),
                'shipping_city' => $sameAddress ? ($data['billing_city'] ?? null) : ($data['shipping_city'] ?? null),
                'shipping_landmark' => $sameAddress ? ($data['billing_landmark'] ?? null) : ($data['shipping_landmark'] ?? null),
                'shipping_pin' => $sameAddress ? ($data['billing_pin'] ?? null) : ($data['shipping_pin'] ?? null),
                'alt_mobile' => $sameAddress ? ($data['mobile'] ?? null) : ($data['alt_mobile'] ?? null),


                'shipping_charges' => $shippingCharges,
                'shipping_method' => $data['shipping_method'] ?? 'standard',

                'coupon_code_id' => $couponId,
                'coupon_code_type' => $couponType,                 
                'coupon_code_discount_type' => $couponDiscountType,
                'coupon_value' => $couponValue, 
                'amount' => $subtotal,
                'discount_amount' => $discount,
                'tax_amount' => $taxTotal,
                'final_amount' => $finalAmount,
                'payment_method' => 'Cash On Delivery',
                'is_paid' => 0,
                'txn_id' => 0,
                'status' => 1,
                'is_live_order' => 1,
                'orderCancelledBy' => 0,
                'orderCancelledReason' => null,
            ]);
            //dd($order);
            do {
                $orderNo = 'ORD-' . str_pad($order->id, 6, '0', STR_PAD_LEFT);
                
                if (Order::where('order_no', $orderNo)->exists()) {
                    $orderNo = 'ORD-' . str_pad($order->id, 6, '0', STR_PAD_LEFT) . strtoupper(Str::random(1));
                }
            } while (Order::where('order_no', $orderNo)->exists());

            $order->update(['order_no' => $orderNo]);


            if ($couponId > 0) {
                $coupon = Coupon::find($couponId);

                CouponUsage::create([
                    'coupon_code_id'        => $coupon->id,
                    'coupon_code'           => $coupon->coupon_code,
                    'discount'              => $discount,
                    'total_checkout_amount' => $subtotal + $taxTotal + $shippingCharges,
                    'final_amount'          => $finalAmount,
                    'user_id'               => $userId ?? 0,
                    'email'                 => $data['email'] ?? '',
                    'ip'                    => $ipAddr,
                    'order_id'              => $order->id,
                    'usage_time'            => now()->toDateTimeString(),
                ]);
            }

            foreach ($cartItems as $item) {
                $product = $item->productDetails;
                $variation = $item->variation;
                if (!$product) continue;

                $qty = (int) $item->qty;
                $unitPrice = $variation->offer_price
                ?? $variation->price
                ?? $product->offer_price
                ?? $product->price
                ?? 0;

                $gstPercent = (float) ($product->gst ?? 0);

                $priceExclGST = $gstPercent > 0 
                    ? $unitPrice / (1 + ($gstPercent / 100)) 
                    : $unitPrice;

                $gstAmountPerUnit = $unitPrice - $priceExclGST;

                $lineSubtotal = $priceExclGST * $qty;
                $lineTax = $gstAmountPerUnit * $qty;

               
                $subtotal += $lineSubtotal;
                $taxTotal += $lineTax;

                OrderProduct::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'product_name' => $product->name ?? null,
                    'product_image' => $product->image ?? null,
                    'product_slug' => $product->slug ?? null,
                    'product_variation_id' => $variation->id ?? null,
                    'colour_name' => $variation->color_name ?? null,
                    'size_name' => $variation->size_name ?? null,
                    'sku_code' => $variation->code ?? null,
                    'qty' => $qty,
                    'gst' => $gstPercent,
                    'price' => $variation->price ?? $product->price ?? 0,
                    'offer_price' => $variation->offer_price ?? $product->offer_price ?? 0,
                    'gst_amount' => $lineTax,
                    'total' => $lineSubtotal + $lineTax,
                ]);

                $productQuantity = ProductVariation::where('id',$variation->id)->first();
                //dd($productQuantity->id);
                if($productQuantity->stock > 0){
                    $stockQuantity = $productQuantity->stock - $qty;
                }else{
                    $stockQuantity = 0;
                }
                ProductVariation::where('id',$variation->id)->update(['stock' => $stockQuantity]);
            }

            Cart::where('user_id', $userId)->delete();
            Checkout::where('user_id',$userId)->delete();

            DB::commit();
            return $order->id;

        } catch (\Throwable $e) {
            DB::rollBack();
            //dd($e->getMessage());
            \Log::error('Order Creation Failed: '.$e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return false;
        }
    }

}