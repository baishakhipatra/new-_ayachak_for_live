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
use Illuminate\Support\Facades\Validator;
use DB;
use Razorpay\Api\Api;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
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

        if ($cartItems->isEmpty()) {
            return redirect()->route('front.cart.index')->with('warning', 'Your cart is empty.');
        }


        $subtotal = 0;
        foreach ($cartItems as $item) {
            $price = $item->offer_price > 0 ? $item->offer_price : $item->price;
            $subtotal += ($price * $item->qty);
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
                    // Percentage coupon -> compute currency discount on subtotal
                    $discount = ($subtotal * floatval($coupon->amount)) / 100;
                }
            }
        }

        $applyDiscountBeforeTax = false; 

        $tax = 0;
        if ($applyDiscountBeforeTax) {
           
            $discountPercent = $subtotal > 0 ? ($discount / $subtotal) : 0;
            foreach ($cartItems as $item) {
                $price = $item->offer_price > 0 ? $item->offer_price : $item->price;
                $lineSubtotal = $price * $item->qty;
                $lineDiscount = $lineSubtotal * $discountPercent;
                $lineTaxable = $lineSubtotal - $lineDiscount;
                $gstPercent = $item->productDetails->gst ?? 0;
                $tax += ($lineTaxable * $gstPercent) / 100;
            }
            $total = $subtotal - $discount + $tax;
        } else {
            
            foreach ($cartItems as $item) {
                $price = $item->offer_price > 0 ? $item->offer_price : $item->price;
                $lineSubtotal = $price * $item->qty;
                $gstPercent = $item->productDetails->gst ?? 0;
                $tax += ($lineSubtotal * $gstPercent) / 100;
            }
            $total = $subtotal + $tax - $discount;
        }

       
        $subtotal = round($subtotal, 2);
        $tax = round($tax, 2);
        $discount = round($discount, 2);
        $total = round($total, 2);

        // $api = new Api(env('RAZORPAY_KEY_ID'), env('RAZORPAY_KEY_SECRET'));

        // $razorpayOrder = $api->order->create([
        //     'receipt'         => 'order_' . uniqid(),
        //     'amount'          => $total  * 100,
        //     'currency'        => 'INR',
        //     'payment_capture' => 1 
        // ]);

        // $razorpayOrderId = $razorpayOrder['id']; 

        return view('front.checkout.index', compact(
            'cartItems',
            'subtotal',
            'tax',
            'discount',
            'total',
            'coupon',
            'checkoutId'
            //'razorpayOrderId'
        ));
    }

    public function coupon(Request $request)
    {
        $couponData = $this->checkoutRepository->couponCheck($request->code);
        return $couponData;
    }

    public function store(Request $request)
    {
        $checkoutId = $request->checkout_id;
        //dd($checkoutId);
        
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

            'address_option' => 'required|in:same,different',
            'shipping_country' => 'nullable|string|max:255',
            'shipping_first_name' => 'nullable|string|max:255',
            'shipping_last_name' => 'nullable|string|max:255',
            'shipping_address' => 'nullable|string|max:500',
            'shipping_city' => 'nullable|string|max:255',
            'shipping_state' => 'nullable|string|max:255',
            'shipping_pin' => 'nullable|string|max:6',
            'shipping_mobile' => 'nullable|digits:10',
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
            'pin' => $request->billing_pin,
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
                'pin' => $request->shipping_pin,
            ];
        }

        $checkoutData = [
            'user_id' => $userId,
            'billing_address' => json_encode($billingAddress),
            'shipping_address' => json_encode($shippingAddress),
        ];

        //dd($checkoutData);

        

        if ($checkoutId) {
            Checkout::where('id', $checkoutId)->update($checkoutData);
        } else {
            
            $checkoutId = Checkout::create($checkoutData)->id;
        }

        return response()->json([
            'success' => true,
            'redirect_url' => route('front.checkout.payment', [
                'checkoutId' => $checkoutId,
            ])
        ]);
    }


    public function payment(Request $request,$checkoutId)
    {
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

        if($paymentMethod == 'cash_on_delivery')
        {
            // $checkoutData = $request->except('_token');
            $order_id = $this->checkoutRepository->create($checkoutData);
            $order = Order::with(['orderProducts.productDetails.category'])->findOrFail($order_id);
            //dd($order);
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
    // public function createOrder(Request $request){
    //     dd($request->all());
    //     return view('front.payment.success');
    //     $razorpayKey = env('RAZORPAY_KEY');
    //     $razorpaySecret = env('RAZORPAY_SECRET');

    //     // Prepare the data for the order
    //     $orderData = [
    //         'receipt'         => 'rcptid_' . time(),
    //         // 'amount'          => $request->input('amount') * 100, // amount in the smallest currency unit
    //         'amount'          => 1 * 100, // amount in the smallest currency unit
    //         'currency'        => 'INR',
    //         'payment_capture' => 1 // auto capture
    //     ];

    //     // Encode the order data
    //     $jsonData = json_encode($orderData);

    //     // Initialize cURL
    //     $ch = curl_init();

    //     // Set cURL options
    //     curl_setopt($ch, CURLOPT_URL, 'https://api.razorpay.com/v1/orders');
    //     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    //     curl_setopt($ch, CURLOPT_POST, true);
    //     curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
    //     curl_setopt($ch, CURLOPT_HTTPHEADER, [
    //         'Content-Type: application/json',
    //         'Authorization: Basic ' . base64_encode("$razorpayKey:$razorpaySecret")
    //     ]);

    //     // Execute the cURL request
    //     $response = curl_exec($ch);
    //     // Check for errors
    //     if ($response === false) {
    //         $errorMessage = curl_error($ch);
    //         return response()->json(['error' => $errorMessage], 500);
    //     }

    //     // Decode the response
    //     $responseData = json_decode($response, true);

    //     // Check if order creation was successful
    //     if (isset($responseData['id'])) {
    //         return response()->json([
    //             'orderId' => $responseData['id'],
    //             'amount' => $request->input('amount')
    //         ]);
    //     } else {
    //         return response()->json(['error' => 'Order creation failed. Please try again.'], 500);
    //     }
    // }
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

                // Optionally, update your database to mark the payment as failed
            }

            return response()->json(['status' => 'success']);
        } else {
            return response()->json(['status' => 'invalid signature'], 400);
        }
    }

}