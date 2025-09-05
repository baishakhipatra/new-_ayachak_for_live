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

            $subtotal = 0.0;
            $taxTotal = 0.0;

            foreach ($cartItems as $item) {
                $product = $item->productDetails;
                $variation = $item->variation;
                if (!$product) continue;

                $qty = (int) $item->qty;
                //$unitPrice = $product->offer_price > 0 ? (float) $product->offer_price : (float) $product->price;
                $unitPrice = $variation->offer_price
                ?? $variation->price
                ?? $product->offer_price
                ?? $product->price
                ?? 0;

                $lineSubtotal = $unitPrice * $qty;
                $gstPercent  = (float) ($product->gst ?? 0);
                $lineTax     = ($lineSubtotal * $gstPercent) / 100.0;

                $subtotal += $lineSubtotal;
                $taxTotal += $lineTax;
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

            $finalAmount = max(0, ($subtotal + $taxTotal + $shippingCharges) - $discount);

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

            $shipping = json_decode($data['shipping_address'], true);
            $data['shipping_address'] = $shipping['address'] ?? null;
            $data['shipping_city']    = $shipping['city'] ?? null;
            $data['shipping_state']   = $shipping['state'] ?? null;
            $data['shipping_country'] = $shipping['country'] ?? null;
            $data['shipping_pin']     = $shipping['pin'] ?? null;

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
                'billing_landmark' => null,
                'billing_country' => $data['billing_country'] ?? null,
                'billing_state' => $data['billing_state'] ?? null,
                'billing_city' => $data['billing_city'] ?? null,
                'billing_pin' => $data['billing_pin'] ?? null,

                'shippingSameAsBilling' => $sameAddress ? 1 : 0,

                'shipping_address_id' => 0,
                'shipping_address' => $sameAddress ? ($data['billing_address'] ?? null) : ($data['shipping_address'] ?? null),
                'shipping_landmark' => null,
                'shipping_country' => $sameAddress ? ($data['billing_country'] ?? null) : ($data['shipping_country'] ?? null),
                'shipping_state' => $sameAddress ? ($data['billing_state'] ?? null) : ($data['shipping_state'] ?? null),
                'shipping_city' => $sameAddress ? ($data['billing_city'] ?? null) : ($data['shipping_city'] ?? null),
                'shipping_pin' => $sameAddress ? ($data['billing_pin'] ?? null) : ($data['shipping_pin'] ?? null),

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
                'payment_method' => 'cash_on_delivery',
                'is_paid' => 0,
                'txn_id' => 0,
                'status' => 1,
                'is_live_order' => 1,
                'orderCancelledBy' => 0,
                'orderCancelledReason' => null,
            ]);
            //dd($order);

            $orderNo = 'ORD-' . str_pad($order->id, 6, '0', STR_PAD_LEFT); 
            $order->update(['order_no' => $orderNo]);

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

                $lineSubtotal = $unitPrice * $qty;
                $gstPercent  = (float) ($product->gst ?? 0);
                $lineTax     = ($lineSubtotal * $gstPercent) / 100.0;
                $lineTotal   = $lineSubtotal + $lineTax;

                OrderProduct::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'product_name' => $product->name ?? null,
                    'product_image' => $product->image ?? null,
                    'product_slug' => $product->slug ?? null,
                    'product_variation_id' => $variation->id ?? null,
                    'colour_name' => $variation->color_name ?? null,
                    'size_name' => $variation->size_name ?? null,
                    'sku_code' => $variation->sku ?? null,
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
            dd($e->getMessage());
            \Log::error('Order Creation Failed: '.$e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return false;
        }
    }


    public function NewCreate(array $data){
        $collectedData = collect($data);
        DB::beginTransaction();
        try {
            $order_id = $this->genAutoIncreNoYearWise();
            $usre_id = Auth::guard('web')->user()->id;
            $user_address = Address::where('user_id', $usre_id)->where('address', $collectedData['billing_address'])->where('pin', $collectedData['billing_pin'])->first();
            if(!isset($user_address)){
                $Address = new Address;
                $Address->address = $collectedData['billing_address'];
                $Address->landmark = $collectedData['billing_landmark'];
                $Address->country = $collectedData['billing_country'];
                $Address->state = $collectedData['billing_state'];
                $Address->city = $collectedData['billing_city'];
                $Address->pin = $collectedData['billing_pin'];
                $Address->user_id = $usre_id;
                $Address->save();
            }
            $order = new Order;
            $order->user_id = $usre_id;
            $order->order_no = $order_id;
            $order->ip = $this->ip;
            $order->fname = $collectedData['fname'];
            $order->lname = $collectedData['lname'];
            $order->email = $collectedData['email'];
            $order->mobile = $collectedData['mobile'];
            $order->address_type = "Home";
            $order->billing_address = $collectedData['billing_address'];
            $order->billing_landmark = $collectedData['billing_landmark'];
            $order->billing_country = $collectedData['billing_country'];
            $order->billing_state = $collectedData['billing_state'];
            $order->billing_city = $collectedData['billing_city'];
            $order->billing_pin = $collectedData['billing_pin'];
            $order->shippingSameAsBilling = $collectedData['shippingSameAsBilling'];
            if($collectedData['shippingSameAsBilling']==1){
                $order->shipping_address = $collectedData['billing_address'];
                $order->shipping_landmark = $collectedData['billing_landmark'];
                $order->shipping_country = $collectedData['billing_country'];
                $order->shipping_state = $collectedData['billing_state'];
                $order->shipping_city = $collectedData['billing_city'];
                $order->shipping_pin = $collectedData['billing_pin'];
            }else{
                $order->shipping_address = $collectedData['shipping_address'];
                $order->shipping_landmark = $collectedData['shipping_landmark'];
                $order->shipping_country = $collectedData['shipping_country'];
                $order->shipping_state = $collectedData['shipping_state'];
                $order->shipping_city = $collectedData['shipping_state'];
                $order->shipping_pin = $collectedData['shipping_pin'];
            }
            $order->shipping_charges = 0;
            $order->shipping_method = $collectedData['shipping_method'];
            $order->coupon_code_type = 'Coupon';
            $order->coupon_code_discount_type = "Flat";
            $order->amount = $collectedData['amount'];
            $order->discount_amount = $collectedData['discount_amount'];
            $order->tax_amount =  $collectedData['tax_amount'];
            $order->final_amount = $collectedData['final_amount'];
            $order->payment_method = $collectedData['payment_method']?$collectedData['payment_method']:"cash_on_delivery";
            $order->is_paid = 0;
            $order->save();
            if($order){
                $user_checkout_products = DB::table('checkout_products')->where('checkout_id', $collectedData['checkout_id'])->where('user_id', $usre_id)->get();
                if(count($user_checkout_products)>0){
                    foreach($user_checkout_products as $key =>$item){
                        $OrderProduct =new OrderProduct;
                        $OrderProduct->order_id =$order->id;
                        $OrderProduct->product_id = $item->product_id;
                        $OrderProduct->product_name = $item->product_name;
                        $OrderProduct->product_image =$item->product_image;
                        $OrderProduct->product_slug =$item->product_slug;
                        $OrderProduct->product_variation_id =$item->product_variation_id;
                        $OrderProduct->colour_name =$item->colour_name;
                        $OrderProduct->coupon_code =$item->coupon_code;
                        $OrderProduct->size_name =$item->size_name;
                        $OrderProduct->sku_code =$item->sku_code;
                        $OrderProduct->price=$item->price;
                        $OrderProduct->offer_price = $item->offer_price;
                        $OrderProduct->qty =$item->qty;
                        $OrderProduct->gst =$item->gst;
                        $OrderProduct->save();
                        if($item->coupon_code){
                            $coupon = Coupon::where('coupon_code', $item->coupon_code)->first();
                            if($coupon){
                                $coupon->status = 2;
                                $coupon->save();
                            }
                        }
                        DB::table('checkout_products')->where('id', $item->id)->delete();
                    }
                    DB::table('checkout')->where('id', $item->checkout_id)->delete();
                }
                $emptyCart = Cart::where('user_id', $usre_id)->delete();
                // Transaction
                $txnData = new Transaction();
                $txnData->user_id = $usre_id;
                $txnData->order_id = $order->id;
                $txnData->transaction = 'TXN_'.strtoupper(Str::random(20));
                $txnData->online_payment_id = $collectedData['razorpay_payment_id'];
                $txnData->amount = $collectedData['final_amount'];
                $txnData->currency = "INR";
                $txnData->method = "";
                $txnData->description = "";
                $txnData->bank = "";
                $txnData->upi = "";
                $txnData->status = 0;
                $txnData->save();
            }
            DB::commit();
            return $txnData;

        } catch (\Throwable $th) {
            throw $th;
            DB::rollback();
            return false;
        }
    }
    public function genAutoIncreNoYearWise(){
        $val = 1;  
        $length = 6;
        $year = date('Y');
        $month = date('m');
        $data = DB::table('orders')->whereRaw("DATE_FORMAT(created_at, '%Y') = '".$year."'")->count();
    
        if(!empty($data)){
            $val = ($data + 1);
        }
        $number = str_pad($val,$length,"0",STR_PAD_LEFT);
        
        return 'COZI'.$year.''.$month.''.$number;
    }


    //payment

    public function paymentCreate($order_id,array $data)
    {
        $collectedData = collect($data);
        DB::beginTransaction();
        try {
            $settings = Settings::all();

            // shipping charge fetch
            $shippingChargeJSON = null;
            $minOrderAmount = 0;
            $shippingCharge = 0;

            if (isset($settings[22])) {
                $shippingChargeJSON = json_decode($settings[22]->content);
                $minOrderAmount = $shippingChargeJSON->min_order ?? 0;
                $shippingCharge = $shippingChargeJSON->shipping_charge ?? 0;
            }


            $newEntry = Order::findOrFail($order_id);
            if (isset($data['payment_method'])) {
                $newEntry->payment_method = $collectedData['payment_method'];
            } else {
                $newEntry->payment_method = "cash_on_delivery";
            }
            $newEntry->save();
			
            $order_no=$newEntry->order_no;
            $orderPro=OrderProduct::where('order_id',$order_id)->get();
            $orderProducts = [];
            foreach($orderPro as $cartValue) {
                $orderProducts[] = [
                    'order_id' => $order_id,
                    'product_id' => $cartValue->product_id,
                    'product_name' => $cartValue->product_name,
                    'product_image' => $cartValue->product_image,
                    'product_slug' => $cartValue->product_slug,
                    'product_variation_id' => $cartValue->product_variation_id,
                    'colour_name' => ProductColorSize::find($cartValue->product_variation_id)->color_name ?? '',
                    'size_name' => ProductColorSize::find($cartValue->product_variation_id)->size_name ?? '',
                    'sku_code' => ProductColorSize::find($cartValue->product_variation_id)->code ?? '',
                    'price' => $cartValue->price,
                    'offer_price' => $cartValue->offer_price,
                    'qty' => $cartValue->qty,
                ];
            }
			
            // payment method
            
			
            // 3 send product details mail
            $email_data = [
                'name' => $newEntry->fname.' '.$newEntry->lname,
                'subject' => 'Onn - New Order',
                'email' => $newEntry->email,
                'orderId' => $newEntry->id,
                'orderNo' => $order_no,
                'orderAmount' => $newEntry->final_amount,
                'orderProducts' => $orderProducts,
                'blade_file' => 'front/mail/order-confirm',
            ];
            if ($settings[23]->content == "1") SendMail($email_data);

            // send invoice mail starts
            $invoice_email_data = [
                'name' => $newEntry->fname.' '.$newEntry->lname,
                'subject' => 'Onn - Order Invoice',
                'email' => $newEntry->email,
                'orderId' => $newEntry->id,
                'payment_method' => $newEntry->payment_method,
                'orderNo' => $order_no,
                'orderAmount' => $newEntry->final_amount,
                //'orderProducts' => $orderProducts,
                'blade_file' => 'front/mail/invoice',
            ];
            if ($settings[23]->content == "1") SendMail($invoice_email_data);

			
           
			//dd($collectedData['razorpay_payment_id']);
            // 5 online payment
            if (!empty($collectedData['razorpay_payment_id'])) {
				//dd('hi');
                // fetch order details
                $ordDetails = Order::findOrFail($order_id);
                 

                // Razorpay auto capture code
                $amm = $ordDetails->final_amount*100;
                $pay_id = $collectedData['razorpay_payment_id'];

                $url = 'https://api.razorpay.com/v1/payments/'.$pay_id.'/capture';

                $data_string = 'amount='.$amm;
                $razorpay_key_id = $settings[20]->content;
                $razorpay_key_secret = $settings[21]->content;
                
                $headers = array(
                    'Content-Type: application/x-www-form-urlencoded',
                    'Authorization: Basic '. base64_encode("$razorpay_key_id:$razorpay_key_secret")
                );

                // Open connection
                $ch = curl_init();
                // Set the url, number of POST vars, POST data
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST"); 
                curl_setopt($ch, CURLOPT_POST, true);                                                                  
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                // Disabling SSL Certificate support temporarly
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                //curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
                // Execute post
                $result = curl_exec($ch);
                 //dd($result);
                //echo $result;
                //pr($result);
                curl_close($ch);


                //$payment = 'https://api.razorpay.com/v1/payments/'.$pay_id;
               // dd($payment);
                // save in transaction
                $txnData = new Transaction();
                $txnData->user_id = Auth::guard('web')->user()->id ?? 0;
                $txnData->order_id = $ordDetails->id;
                $txnData->transaction = 'TXN_'.strtoupper(Str::random(20));
                $txnData->online_payment_id = $collectedData['razorpay_payment_id'];
                // $txnData->amount = $total;razorpay_amount
                $txnData->amount = $ordDetails->final_amount;
             
                $txnData->currency = "INR";
                $txnData->method = "";
                $txnData->description = "";
                $txnData->bank = "";
                $txnData->upi = "";
                $txnData->save();
            }
            // Shiprocket
            if ($settings[23]->content == "1") $this->shiprocket($newEntry, $orderPro);

			// Unicommerce
            if ($settings[23]->content == "1") $this->feedUnicommerce($newEntry, $orderPro);

            DB::commit();
            // dd($order_no);
            return $order_no;
        } catch (\Throwable $th) {
            throw $th;
            //dd($th);
            DB::rollback();
            return false;
        }
    }

    public function shiprocket($booking,$items){

        $logindetails = $this->shiprocketlogin();
        $logindetails = json_decode($logindetails);

        $dt = date('Y-m-d H:i:s');

        $pushdata = array();

        foreach($items as $n){
            $sku_code = ProductColorSize::findOrFail($n->product_variation_id);

            ($sku_code->code == "" || $sku_code->code == null) ? $SKU_code = "ONN_272_BLK_S_1PC" : $SKU_code = $sku_code->code;

            $data['name'] = $n->product_name;
            // $data['sku'] = $n->product_style_no;
            $data['sku'] = $SKU_code;
            $data['units'] = $n->qty;
            $data['selling_price'] = $n->offer_price;

            array_push($pushdata, $data);
        }

        //$name =  $this->split_name($booking->name);
        $jsondata['order_id'] = $booking->order_no;
        $jsondata['order_date'] = date('Y-m-d H:i:s');
        $jsondata['pickup_location'] = 'Lux Industries Limited';
        $jsondata['channel_id'] = '2865152';
        // $jsondata['pickup_location'] = 'Lux Dankuni';
        $jsondata['billing_customer_name'] = $booking->fname;
        $jsondata['billing_last_name'] = $booking->lname;
        $jsondata['billing_address'] = $booking->billing_address;
        $jsondata['billing_address_2'] = $booking->billing_landmark;
        $jsondata['billing_city'] = $booking->billing_city;
        $jsondata['billing_pincode'] = $booking->billing_pin;
        $jsondata['billing_state'] = $booking->billing_state;
        $jsondata['billing_country'] = $booking->billing_country;
        $jsondata['billing_email'] = $booking->email;
        $jsondata['billing_phone'] = $booking->mobile;
        $jsondata['shipping_is_billing'] = true;
        $jsondata['shipping_customer_name'] = '';
        $jsondata['shipping_last_name'] = '';
        $jsondata['shipping_address'] = '';
        $jsondata['shipping_address_2'] = '';
        $jsondata['shipping_city'] = '';
        $jsondata['shipping_pincode'] = '';
        $jsondata['shipping_country'] = '';
        $jsondata['shipping_state'] = '';
        $jsondata['shipping_email'] = '';
        $jsondata['shipping_phone'] = '';
        $jsondata['order_items'] = $pushdata;

        $payment_method = '';
        if($booking->payment_method=='online_payment') {
            $payment_method = "Prepaid";
        } else{
			$payment_method = "Cod";
		}

        $jsondata['payment_method'] = $payment_method;
        $jsondata['shipping_charges'] = $booking->shipping_charge;
        $jsondata['total_discount'] = $booking->discount_amount;
        $jsondata['sub_total'] = $booking->final_amount;
        $jsondata['length'] = 22;
        $jsondata['breadth'] = 22;
        $jsondata['height'] = 5;
        $jsondata['weight'] = 0.5;

        $token = $logindetails->token;

        // echo json_encode($jsondata);

        // die();

        //echo $token;

        $url = 'https://apiv2.shiprocket.in/v1/external/orders/create/adhoc';

        $headers = array(
            "Content-Type: application/json",
            "Authorization: Bearer ". $token
        );
        //  echo '<pre>';
        //  print_r($headers);
        //  die();
        // Open connection
        $ch = curl_init();
        // Set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_HTTP_VERSION, 'CURL_HTTP_VERSION_1_1');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // Disabling SSL Certificate support temporarly
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($jsondata));
        // Execute post
        $result = curl_exec($ch);

        //echo "result : ".$result;
        //die();

        curl_close($ch);

		$payload = DB::table('third_party_payloads')->insert([
            "type" => "shiprocket",
            "status" => "success",
            "order_id" => $booking->order_no,
            "request_body" => json_encode($jsondata),
            "payload" => $result
        ]);

        return $result;

        /*return response()
            ->json(["jsondata"=>$jsondata]);*/
    }

    public function shiprocketlogin(){
        $headers = array(
            'Content-Type: application/json'
        );

        $jsondata['email'] = "suvajit.bardhan@onenesstechs.in";
        $jsondata['password'] = "Welcome#2022";

        $url = 'https://apiv2.shiprocket.in/v1/external/auth/login';

        $ch = curl_init();
        // Set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // Disabling SSL Certificate support temporarly
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($jsondata));
        // Execute post
        $result = curl_exec($ch);

        curl_close($ch);
        return $result;
    }

    //unicommerce
    public function feedUnicommerce($orderDetails, $cartData)
    {
        $loginCred = $this->unicommerceLogin();
        $loginResp = json_decode($loginCred);
		
        // if (!isset($loginResp->successful)) {
        if (!isset($loginResp->successful) || $loginResp->successful == "true") {
            $url = 'https://cozyworld.unicommerce.com/services/rest/v1/oms/saleOrder/create';
            $headers = array(
                'Authorization: Bearer '.$loginResp->access_token,
                'Content-Type: application/json'
            );
            if($orderDetails->payment_method == 'online_payment') {
                $cashOnDelivery = false;
            } else{
                $cashOnDelivery = true;
            }
            $billing_refId = mt_rand();
            $shipping_refId = mt_rand();

            if ($orderDetails->shippingSameAsBilling != 0) {
                $shippingAddressLine1 = $orderDetails->shipping_address;
                $shippingAddressLine2 = '';
                $shippingAddressPincode = $orderDetails->shipping_pin;
                $shippingAddressCity = $orderDetails->shipping_city;
                $shippingAddressState = $orderDetails->shipping_state;
                $shippingAddressCountry = $orderDetails->shipping_country;
            } else {
                $shippingAddressLine1 = $orderDetails->billing_address;
                $shippingAddressLine2 = '';
                $shippingAddressPincode = $orderDetails->billing_pin;
                $shippingAddressCity = $orderDetails->billing_city;
                $shippingAddressState = $orderDetails->billing_state;
                $shippingAddressCountry = $orderDetails->billing_country;
            }

            $productsData = array();

            foreach($cartData as $cartProduct) {
                $sku_code = ProductColorSize::findOrFail($cartProduct->product_variation_id);

                ($sku_code->code == "" || $sku_code->code == null) ? $SKU_code = "ONN_272_BLK_S_1PC" : $SKU_code = $sku_code->code;

                $total_price = $cartProduct->offer_price * $cartProduct->qty;

                $productsData[] = [
                    "itemSku" => $SKU_code,
                    // "code" => $cartProduct->product_style_no,
                    "code" => $SKU_code,
                    "itemName" => $cartProduct->product_name,
                    "totalPrice" => $total_price,
                    "sellingPrice" => $cartProduct->offer_price,
                    "prepaidAmount" => 0,
                    "onHold" => false,
                    "shippingAddress" => "$shipping_refId",
                    // "shippingMethodCharges" => 0,
                    "shippingMethodCode" => "STD",
                    "channelTransferPrice" => 0
                ];
            }

            // dd($productsData, json_encode($productsData));

            $body2['saleOrder'] = [];
            $body2['saleOrder']['code'] = $orderDetails->order_no;
            $body2['saleOrder']['channel'] = "ONNINTERNATIONAL";
            // $body2['saleOrder']['displayOrderCode'] = $orderDetails->order_no;
            $body2['saleOrder']['cashOnDelivery'] = true;
            $body2['saleOrder']['addresses'] = [
                [
                    "id" => "$billing_refId",
                    "addressLine1" => $orderDetails->billing_address,
                    "addressLine2" => "",
                    "name" => $orderDetails->fname.' '.$orderDetails->lname,
                    "pincode" => $orderDetails->billing_pin,
                    "phone" => $orderDetails->mobile,
                    "email" => $orderDetails->email,
                    "city" => $orderDetails->billing_city,
                    "state" => $orderDetails->billing_state,
                    "country" => $orderDetails->billing_country
                ],
                [
                    "id" => "$shipping_refId",
                    "addressLine1" => $shippingAddressLine1,
                    "addressLine2" => $shippingAddressLine2,
                    "name" => $orderDetails->fname.' '.$orderDetails->lname,
                    "pincode" => $shippingAddressPincode,
                    "phone" => $orderDetails->mobile,
                    "email" => $orderDetails->email,
                    "city" => $shippingAddressCity,
                    "state" => $shippingAddressState,
                    "country" => $shippingAddressCountry
                ]
            ];
            $body2['saleOrder']['billingAddress']['referenceId'] = "$billing_refId";
            $body2['saleOrder']['shippingAddress']['referenceId'] = "$shipping_refId";
            $body2['saleOrder']['saleOrderItems'] = $productsData;
            // $body2['saleOrder']['totalCashOnDeliveryCharges'] = 0;
            // $body2['saleOrder']['totalDiscount'] = 0;
            // $body2['saleOrder']['totalGiftWrapCharges'] = 0;
            // $body2['saleOrder']['totalPrepaidAmount'] = 0;
            // $body2['saleOrder']['totalShippingCharges'] = 0;
            // $body2['saleOrder']['totalStoreCredit'] = 0;

            // dd(json_encode($body2));

            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => json_encode($body2),
                CURLOPT_HTTPHEADER => $headers,
            ));

            $response = curl_exec($curl);

            curl_close($curl);
            $decoded_response = json_decode($response);

            if ($decoded_response->successful == true) {
                $payload = DB::table('third_party_payloads')->insert([
                    "type" => "unicommerce",
                    "status" => "success",
                    "order_id" => $orderDetails->order_no,
                    "request_body" => json_encode($body2),
                    "payload" => $response
                ]);
            } else {
                $payload = DB::table('third_party_payloads')->insert([
                    "type" => "unicommerce",
                    "status" => "failure",
                    "order_id" => $orderDetails->order_no,
                    "request_body" => json_encode($body2),
                    "payload" => $response
                ]);
            }
        } else {
            $payload = DB::table('third_party_payloads')->insert([
                "type" => "unicommerce",
                "status" => "failure",
                "order_id" => $orderDetails->order_no,
                "request_body" => json_encode($body2),
                "payload" => json_encode($loginResp)
            ]);
        }
    }

    public function unicommerceLogin()
    {
        $username = 'rohit@onenesstechs.in';
        $password = 'q%23393KHVqRBPDTE';

        $url = 'https://cozyworld.unicommerce.com/oauth/token?grant_type=password&client_id=my-trusted-client&username='.$username.'&password='.$password;

        $headers = array(
            'Content-Type: application/json'
        );

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => $headers,
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return $response;
    }

    public function split_name($name) {
		$name = trim($name);
		$last_name = (strpos($name, ' ') === false) ? '' : preg_replace('#.*\s([\w-]*)$#', '$1', $name);
		$first_name = trim( preg_replace('#'.$last_name.'#', '', $name ) );
		return array($first_name, $last_name);
    }
}