<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Interfaces\CheckoutInterface;
use App\Interfaces\CartInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\CartOffer;
use App\Models\Order;
use App\Models\Cart;
use App\Models\Coupon; 
use App\Models\Checkout;
use App\Models\Address;
use Illuminate\Support\Facades\Validator;
use DB;
use Razorpay\Api\Api;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use App\Mail\OrderConfirmationMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Http;

class CheckoutController extends Controller
{
    public function __construct(CheckoutInterface $checkoutRepository, CartInterface $cartRepository) 
    {
        $this->checkoutRepository = $checkoutRepository;
        $this->cartRepository = $cartRepository;
    }

    public function index(Request $request)
    {
        $userId = auth()->id();

        $cartItems = Cart::with(['productDetails', 'variation', 'productDetails.category'])
            ->where('user_id', $userId)
            ->get();
        $user_address = Address::where('user_id', $userId)->first();

        if ($cartItems->isEmpty()) {
            return redirect()->route('front.cart.index')->with('warning', 'Your cart is empty.');
        }

        foreach ($cartItems as $item) {
            if (!$item->variation || $item->variation->stock < $item->qty) {
                return redirect()->route('front.cart.index')->with('error',
                    "Sorry, '{$item->productDetails->name}' is out of stock or quantity is insufficient."
                );
            }
        }

  
        $subtotal = 0; 
        $tax = 0;     
        foreach ($cartItems as $item) {
            $unitPrice = $item->offer_price > 0 ? $item->offer_price : $item->price;
            $subtotal += $unitPrice * $item->qty;
            $item->calc = [
                'unitPrice' => $unitPrice,
                'lineTotal' => $unitPrice * $item->qty,
            ];
        }

    
        $latestCheckout = Checkout::where('user_id', $userId)->latest()->first();
        //dd($latestCheckout->id);
        $checkoutId = $latestCheckout->id;

        $discount = 0;
        $coupon = null;

        if ($latestCheckout && $latestCheckout->coupon_id) {
            $coupon = Coupon::find($latestCheckout->coupon_id);
            if ($coupon) {
                
                $couponType = $coupon->coupon_type ?? $coupon->type ?? null;

                if ($couponType == 2) {
                    // Fixed amount coupon
                    $discount = floatval($coupon->amount);
                } elseif ($couponType == 1) {
                    $discount = ($subtotal * floatval($coupon->amount)) / 100;
                }
            }
        }

        $total = $subtotal - $discount;

        $subtotal = round($subtotal, 2);
        $discount = round($discount, 2);
        $total = round($total, 2);

        return view('front.checkout.index', compact(
            'cartItems',
            'subtotal',
            'tax',
            'discount',
            'total',
            'coupon',
            'checkoutId',
            'user_address'
        ));
    }

    // public function coupon(Request $request)
    // {
    //     $couponData = $this->checkoutRepository->couponCheck($request->code);
    //     return $couponData;
    // }

    public function store(Request $request)
    {
    try {
        $checkoutId = $request->checkout_id;
        // dd($checkoutId);
        
        $rules = [
            'email' => 'required|email|max:255',
            'mobile' => 'required|digits:10',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'billing_country' => 'required|string|max:255',
            'billing_address' => 'required|string|max:1000',
            'billing_city' => 'required|string|max:255',
            'billing_state' => 'required|string|max:255',
            'billing_pin' => 'required|string|max:6',
            'billing_landmark' => 'nullable|string|max:255',

            'address_option' => 'required|in:same,different',
            'shipping_country' => 'nullable|string|max:255',
            'shipping_first_name' => 'nullable|string|max:255',
            'shipping_last_name' => 'nullable|string|max:255',
            'shipping_address' => 'nullable|string|max:500',
            'shipping_city' => 'nullable|string|max:255',
            'shipping_state' => 'nullable|string|max:255',
            'shipping_pin' => 'nullable|string|max:6',
            'shipping_landmark' => 'nullable|string|max:255',
            'alt_mobile' => 'nullable|digits:10',
        ];

        $messages = [
            'mobile.*' => 'Please enter a valid 10 digit mobile number',
            'billing_pin.*' => 'Please enter a valid 6 digit pin',
            'shipping_pin.*' => 'Please enter a valid 6 digit pin',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $userId = auth()->id();
        $hasCart = Cart::where('user_id', $userId)->exists();
        if (!$hasCart) {
            return response()->json(['error' => 'Cart is empty'], 422);
        }

        $billingAddress = [
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'mobile' => $request->mobile,
            'country' => $request->billing_country,
            'address' => $request->billing_address,
            'city' => $request->billing_city,
            'state' => $request->billing_state,
            'billing_landmark' => $request->billing_landmark,
            'pin' => $request->billing_pin,
            'address_option' => $request->address_option,
        ];

 
        if ($request->address_option == 'same') {
            $shippingAddress = $billingAddress; // Same as billing
        } else {
            $shippingAddress = [
                'first_name' => $request->shipping_first_name,
                'last_name' => $request->shipping_last_name,
                'mobile' => $request->shipping_mobile,
                'country' => $request->shipping_country,
                'address' => $request->shipping_address,
                'city' => $request->shipping_city,
                'state' => $request->shipping_state,
                'shipping_landmark' => $request->shipping_landmark,
                'pin' => $request->shipping_pin,
                'alt_mobile' => $request->alt_mobile,
            ];
        }

        $checkoutData = [
            'user_id' => $userId,
            'billing_address' => json_encode($billingAddress),
            'shipping_address' => json_encode($shippingAddress),
        ];

        //dd($checkoutData);
        $checkout = Checkout::where('id', $checkoutId)->first();
        // dd($checkout);
       

        if ($checkoutId) {
            Checkout::where('id', $checkoutId)->update($checkoutData);
        } else {
            
            $checkoutId = Checkout::create($checkoutData)->id;
        }

        return $this->initiatePaymentMethod($checkout->final_amount, $request->email, $request->mobile);

        return response()->json([
            'success' => true,
            'redirect_url' => route('front.checkout.payment', [
                'checkoutId' => $checkoutId,
            ])
        ]);
        } catch (\Throwable $e) {
            // Log the error for debugging
            \Log::error('ICICI Payment Initiation Failed: ' . $e->getMessage());
            dd($e->getMessage());
            // Return error response or redirect
            return back()->with('error', 'Something went wrong while initiating payment. Please try again later.');
        }
    }


    // protected function initiatePaymentMethod($amount,$email,$mobile){
    //     do {
    //         $order_id = uniqid('ORD'); // generate something like ORD671FA5C8F12A9
    //     } while (Checkout::where('transaction_id', $order_id)->exists());
    //    // dd($order_id);
    //     $merchantId = env('ICICI_MERCHANT_ID');
    //     $aggregatorID = env('ICICI_AGGREGATOR_ID');
    //     $secretKey = env('ICICI_MERCHANT_SECRET_KEY');

    //     // $data = [
    //     //     "merchantId"=> env('ICICI_MERCHANT_ID'),
    //     //     "merchantTxnNo"=> $order_id,
    //     //     "aggregatorID"=> $aggregatorID,
    //     //     "amount"=> number_format($amount, 2, '.', ''),
    //     //     "currencyCode"=> "356",
    //     //     "payType"=> "0",       
    //     //     "customerEmailID"=> $email,
    //     //     "transactionType"=> "SALE",
    //     //     "txnDate"=> date('YmdHis'),
    //     //     "returnURL"=> secure_url('api/customer/icici/thankyou'),
    //     //     "customerMobileNo"=> "91".$mobile,
    //     //   // "customerName"=> $first_name,
    //     //       "addlParam1"       => "Test1",
    //     //       "addlParam2"       => "Test2",
    //     // ];
    //     $data = [
    //         "addlParam1"        => "Test1",
    //         "addlParam2"        => "Test2",
    //         "aggregatorID"      => $aggregatorID,
    //         "amount"            => number_format($amount, 2, '.', ''), 
    //         "currencyCode"      => "356",
    //         "customerEmailID"   => $email,
    //         "customerMobileNo"  => "91" . $mobile,
    //         "merchantId"        => $merchantId,
    //         "merchantTxnNo"     => $order_id,
    //         "payType"           => "0",
    //         "returnURL"         => secure_url('api/customer/icici/thankyou'),
    //         "transactionType"   => "SALE",
    //         "txnDate"           => date('YmdHis'),
    //     ];
    //     // Create secureHash
    //     // $hashKey = implode('', [
    //     //     $data["addlParam1"],
    //     //     $data["addlParam2"],
    //     //     $data["amount"],
    //     //     $data["currencyCode"],
    //     //     $data["customerEmailID"],
    //     //     $data["customerMobileNo"],
    //     //   // $data["customerName"],
    //     //     $data["merchantId"],
    //     //     $data["aggregatorID"],
    //     //     $data["merchantTxnNo"],
    //     //     $data["payType"],
    //     //     $data["returnURL"],
    //     //     $data["transactionType"],
    //     //     $data["txnDate"]
    //     // ]);

    //     //$data['secureHash'] = hash_hmac('sha256', $hashKey, env('ICICI_MERCHANT_SECRET_KEY'));
        
    //         ksort($data);

    //     // step 2: Concatenate all values (no spaces, no separators)
    //     $plainText = implode('', array_values($data));
    
    //     // step 3: Generate secure hash
    //     $secureHash = hash_hmac('sha256', $plainText, $secretKey);
    
    //     // Add hash to payload
    //     $data['secureHash'] = $secureHash;

    //     // Send request to Phicommerce using cURL
    //     $ch = curl_init(env('ICICI_PAYMENT_INITIATE_BASH_URL'));
    //     //dd($ch);
    //     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    //     curl_setopt($ch, CURLOPT_HTTPHEADER, [
    //         'Content-Type: application/json'
    //     ]);
    //     curl_setopt($ch, CURLOPT_POST, true);
    //     curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

    //     $response = curl_exec($ch);

    //     if (curl_errno($ch)) {
    //         $error = curl_error($ch);
    //         curl_close($ch);
    //         return response()->json(['error' => $error], 500);
    //     }

    //     curl_close($ch);
    //     $InitiateSaleResponse = json_decode($response, true);
    //     dd($InitiateSaleResponse);

    //     if (isset($response['redirectUrl'])) {
    //         // store transaction in DB
    //         Transaction::create([
    //             'user_id' => $user->id,
    //             'order_id' => $order_id,
    //             'transaction' => $order_id,
    //             'amount' => $amount,
    //             'currency' => 'INR',
    //             'method' => 'ICICI',
    //             'description' => 'ICICI Payment Initiated',
    //             'bank' => '',
    //             'upi' => '',
    //             'status' => 0,
    //         ]);

    //         return redirect($response['redirectUrl']);
    //     }
    // }

    public function initiatePaymentMethod($amount,$email,$mobile){
        try{
            $user = auth()->user();
        do {
            $order_id = uniqid('ORD'); // generate something like ORD671FA5C8F12A9
        } while (Checkout::where('transaction_id', $order_id)->exists());
        //    dd($order_id);
        $merchantId = env('ICICI_MERCHANT_ID');
        $aggregatorID = env('ICICI_AGGREGATOR_ID');
        $data = [
            "addlParam1"       => "Test1",
            "addlParam2"       => "Test2",
            "aggregatorID"     => env('ICICI_AGGREGATOR_ID'),  // Add this to .env
            "amount"           => number_format($amount, 2, '.', ''),
            "currencyCode"     => "356",
            "customerEmailID"  => $email,
            "customerMobileNo" => "91".$mobile,
            "merchantId"       => env('ICICI_MERCHANT_ID'),
            "merchantTxnNo"    => $order_id,
            "payType"          => "0",
            "returnURL"        => secure_url('api/customer/icici/thankyou'),
            "transactionType"  => "SALE",
            "txnDate"          => date('YmdHis'),
        ];
        ksort($data);
        $plainHashText = implode('', array_values($data));
            $secretKey = env('ICICI_MERCHANT_SECRET_KEY');
        $data['secureHash'] = hash_hmac('sha256', $plainHashText, $secretKey);

        $ch = curl_init(env('ICICI_PAYMENT_INITIATE_BASH_URL'));
        //dd($ch);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json'
        ]);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            $error = curl_error($ch);
            curl_close($ch);
            return response()->json(['error' => $error], 500);
        }

        curl_close($ch);
        $InitiateSaleResponse = json_decode($response, true);
       // dd($InitiateSaleResponse);

        if (isset($response['redirectUrl'])) {
            // store transaction in DB
            Transaction::create([
                'user_id' => $user->id,
                'order_id' => $order_id,
                'transaction' => $order_id,
                'amount' => $amount,
                'currency' => 'INR',
                'method' => 'ICICI',
                'description' => 'ICICI Payment Initiated',
                'bank' => '',
                'upi' => '',
                'status' => 0,
            ]);
            return redirect($response['redirectUrl']);
        }
        }catch (\Throwable $e) {
            // Log the error for debugging
            \Log::error('ICICI Payment Initiation Failed: ' . $e->getMessage());
            dd($e->getMessage());
            // Return error response or redirect
            return back()->with('error', 'Something went wrong while initiating payment. Please try again later.');
        }
    }

    public function payment(Request $request)
    {
        $checkoutId = $request->input('checkoutId');
        if (auth()->guard('web')->check()) {
            $data = Checkout::where('id',$checkoutId)->orderby('id','desc')->first();
        } else {
            $data = Checkout::where('id',$checkoutId)->orderby('id','desc')->first(); 
        }
        //dd($data);
        if ($data) {
            return view('front.checkout.payment', compact('data'));
        }
    }


    public function paymentStore(Request $request)
    {

        $checkoutId = $request->checkout_id;
        $paymentMethod = $request->paymentMethod;

        $checkoutData = Checkout::where('id',$checkoutId)->firstOrFail()->toArray();
       // dd($checkoutData);


        if($paymentMethod == 'Cash On Delivery')
        {
            // $checkoutData = $request->except('_token');
            $order_id = $this->checkoutRepository->create($checkoutData);
            $order = Order::with(['orderProducts.productDetails.category'])->findOrFail($order_id);
           $email_data = [
                    'name'       => $order->fname . ' ' . $order->lname,
                    'subject'    => 'Onn - Order Confirmation #' . $order->order_no,
                    'email'      => $order->email,
                    'blade_file' => 'front/emails/order_confirmation',
                    'order'      => $order
            ];
            // SendMail($email_data);
            // $order_id = 1;
          
            return view('front.checkout.complete', compact('order_id','order'))->with('success', 'Thank you for you order');

        }else{
            return redirect()->back()->with('failure', 'Something happened.Try again.');
        }
    }


    // New Payment Gateway
    public function createOrder(Request $request){
        // dd($request->all());
        $request->validate([
            'email' => 'required|email|max:255',
            'mobile' => 'required|integer|digits:10',
            'fname' => 'required|string|max:255',
            'lname' => 'required|string|max:255',
           'billing_country' => 'required|string|max:255',
           'billing_address' => 'required|string|max:1000',
           'billing_landmark' => 'nullable|string|max:255',
           'billing_city' => 'required|string|max:255',
           'billing_state' => 'required|string|max:255',
            'billing_pin' => 'required|string|max:255',
            'shippingSameAsBilling' => 'nullable|integer|digits:1',
            'shipping_country' => 'nullable|string|max:255',
            'shipping_address' => 'nullable|string|max:500',
            'shipping_landmark' => 'nullable|string|max:255',
            'shipping_city' => 'nullable|string|max:255',
            'shipping_state' => 'nullable|string|max:255',
            'shipping_pin' => 'nullable|integer|digits:6',
            'shipping_method' => 'nullable|string',
        ], [
            'mobile.*' => 'Please enter valid 10 digit mobile number',
            'billing_pin.*' => 'Please enter valid 6 digit pin',
            'shipping_pin.*' => 'Please enter valid 6 digit pin',
        ]);
        
         $order_id = $this->checkoutRepository->NewCreate($request->except('_token'));
        if ($order_id) {
           return view('front.payment.success')->with('success', 'Thank you for you order');
        } else {
            session()->flash('failure', 'Something happened. Try again.');
            return redirect()->back();
        }
    }
    public function success(Request $request)
    {
        // Validate the request
        $signature = $request->input('razorpay_signature');
        $paymentId = $request->input('razorpay_payment_id');
        $orderId = $request->input('razorpay_order_id');

        // Verify the signature manually
        $generated_signature = hash_hmac('sha256', $orderId . '|' . $paymentId, env('RAZORPAY_SECRET'));
        dd($generated_signature);
        if ($generated_signature === $signature) {
            // Payment is successful, update your database
            // ...

            return view('payment.success', compact('paymentId'));
        } else {
            // Log detailed error information
            Log::error('Razorpay Payment Verification Failed', [
                'message' => 'Signature verification failed',
                'payment_id' => $paymentId,
                'order_id' => $orderId,
                'request' => $request->all()
            ]);

            // Payment verification failed
            return view('payment.failure', ['message' => 'Payment verification failed']);
        }
    }

    public function failure()
    {
        return view('payment.failure', ['message' => 'Payment failed or canceled.']);
    }

    public function webhook(Request $request)
    {
        $apiSecret = env('RAZORPAY_SECRET');
        $signature = $request->header('X-Razorpay-Signature');
        $payload = $request->getContent();

        $expectedSignature = hash_hmac('sha256', $payload, $apiSecret);

        if ($signature === $expectedSignature) {
            $event = $request->input('event');

            if ($event === 'payment.failed') {
                $paymentId = $request->input('payload.payment.entity.id');
                $orderId = $request->input('payload.payment.entity.order_id');
                $reason = $request->input('payload.payment.entity.error_reason');

                Log::info("Payment failed. Payment ID: $paymentId, Order ID: $orderId, Reason: $reason");
            }

            return response()->json(['status' => 'success']);
        } else {
            return response()->json(['status' => 'invalid signature'], 400);
        }
    }

}