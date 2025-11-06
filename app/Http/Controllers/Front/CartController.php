<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Interfaces\CheckoutInterface;
use App\Interfaces\CartInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\CartOffer;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\Cart;
use App\Models\Coupon; 
use App\Models\User; 
use App\Models\Checkout;
use App\Models\CheckoutProduct;
use App\Models\Address;
use App\Models\Transaction;
use Illuminate\Support\Facades\Validator;
use DB;
use Razorpay\Api\Api;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use App\Mail\OrderConfirmationMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Http;
use App\Models\PaymentLog;

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

        $checkout = Checkout::where('id', $checkoutId)->first();
            if ($checkoutId) {
                Checkout::where('id', $checkoutId)->update($checkoutData);
            } else {
                
                $checkoutId = Checkout::create($checkoutData)->id;
            }
        // dd($checkout);
        $InitiateSaleResponse = $this->initiatePaymentMethod($checkout->final_amount, $request->email, $request->mobile,$userId,$checkoutId);
        
        if (isset($InitiateSaleResponse['responseCode']) && $InitiateSaleResponse['responseCode'] === 'R1000') {
             return response()->json([
                'status' => true,
                'response' => "Transaction has been successfully generated.",
                'merchantTxnNo' => $InitiateSaleResponse['merchantTxnNo'] ?? null,
                'redirect_url' => isset($InitiateSaleResponse['redirectURI'], $InitiateSaleResponse['tranCtx'])
                        ? $InitiateSaleResponse['redirectURI'] . '?tranCtx=' . $InitiateSaleResponse['tranCtx']
                        : null,
            ], 200);
        }
        return response()->json([
            'status' => false,
            'response' => 'Failed to initiate transaction.',
            'error' => $InitiateSaleResponse
        ], 400);
    }

    protected function initiatePaymentMethod($amount,$email,$mobile,$userId,$checkout_id){
        do {
            $order_id = uniqid('ORD'); // generate something like ORD671FA5C8F12A9
        } while (Checkout::where('merchantTxnNo', $order_id)->exists());
       
        $user = User::find($userId);
        $merchantId = env('ICICI_MARCHANT_ID');
        $aggregatorID = env('ICICI_AGGREGATOR_ID');
        $secretKey    = env('ICICI_MARCHANT_SECRET_KEY');
        
        $data = [
            "addlParam1" => "",
            "addlParam2" => "",
            "amount" => number_format($amount, 2, '.', ''),
            "currencyCode" => "356",
            "customerEmailID" => $email ?? "testmail123@gmail.com",
            "customerMobileNo" => "91" . ($mobile ?? "9876543210"),
            "customerName" => $user?$user->name:"N/A",
            "merchantId" => $merchantId,
            "merchantTxnNo" => $order_id,
            "payType" => "0",
            "returnURL"         =>route('front.icici.thankyou'),
            "transactionType" => "SALE",
            "txnDate" => date('YmdHis')
        ];
        
        // Step 1: Sort the array alphabetically by key
        $sortedData = $data;
        ksort($sortedData);
        // Step 2: Concatenate all values in sorted order
        $hashText = implode('', array_values($sortedData));
        // dd($sortedData);
        // Step 3: Generate secure hash using your secret key
        $data["secureHash"] = hash_hmac('sha256', $hashText, $secretKey);
       
       $ch = curl_init(env('ICICI_PAYMENT_INITIATE_BASH_URL')); // or env('ICICI_PAYMENT_INITIATE_BASH_URL')

        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_TIMEOUT => 60,            // wait up to 60 seconds
            CURLOPT_CONNECTTIMEOUT => 20,     // connection timeout 20s
            CURLOPT_SSL_VERIFYPEER => false,  // QA might use self-signed certs
        ]);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);
        
        // Log and check
        // \Log::info('ICICI PG Response: '.$response);
        // \Log::info('HTTP Code: '.$httpCode);
        // \Log::info('CURL Error: '.$error);

        if (curl_errno($ch)) {
            $error = curl_error($ch);
            curl_close($ch);
            return response()->json(['error' => $error], 500);
        }

        curl_close($ch);
        $InitiateSaleResponse = json_decode($response, true);
        
        if (isset($InitiateSaleResponse['responseCode']) && $InitiateSaleResponse['responseCode'] === 'R1000') {
                
            Checkout::updateOrCreate(
                ['id' => $checkout_id],
                ['merchantTxnNo' => $InitiateSaleResponse['merchantTxnNo'] ?? null]
            );
        
            // store transaction in DB
            Transaction::create([
                'user_id' => $userId,
                'merchantTxnNo' => $order_id,
                'amount' => $amount,
                'currency' => 'INR',
                'status' => 0,
            ]);
            
            Log::info('payment_date', [
                'status' => 'success',
                'message' => 'Payment date recorded',
                'user_id' => $userId,
                'icici_merchantTxnNo' => $InitiateSaleResponse['merchantTxnNo'],
                'payment_date' => now()->toDateTimeString()
            ]);
        
        }
        return json_decode($response, true);
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
    
    public function ICICIThankyou(Request $request)
    {
        $response = $request->all(); // Get all data
    
        PaymentLog::create([
            'gateway' => 'ICICI',
            'transaction_id' => $response['txnID'] ?? null,
            'merchant_txn_no' => $response['merchantTxnNo'] ?? null,
            'response_payload' => json_encode($response),
            'status' => $response['responseCode'] ?? null,
            'message' => isset($response['respDescription']) ? $response['respDescription'] . '(authorized)' : null,
        ]);
    
        $merchantTxnNo = $response['merchantTxnNo'] ?? null;
        $OrderMerchantNumber = Checkout::where('merchantTxnNo', $merchantTxnNo)->first();
    
        $message = '';
        $success_message = '';
    
        // Case: Invalid merchantTxnNo
        if (!$OrderMerchantNumber) {
            $message = 'No data found by this merchantTxnNo.';
            return view('icici.thanks', compact('message'));
        }
    
        // Case: Payment success
        if (
            isset($response['respDescription']) &&
            $response['respDescription'] === 'Transaction successful'
        ) {
            DB::beginTransaction(); // ✅ Start DB transaction
            try {
                PaymentLog::create([
                    'gateway' => 'ICICI',
                    'transaction_id' => $response['txnID'] ?? null,
                    'merchant_txn_no' => $response['merchantTxnNo'] ?? null,
                    'response_payload' => json_encode($response),
                    'status' => $response['responseCode'] ?? null,
                    'message' => isset($response['respDescription']) ? $response['respDescription'] . '(completed)' : null,
                ]);
    
                $fetchcheckoutProduct = CheckoutProduct::where('checkout_id', $OrderMerchantNumber->id)->get();
                // dd($response); // Commented for production safety
    
                // Decode billing and shipping JSON data
                $billing = json_decode($OrderMerchantNumber->billing_address, true);
                $shipping = json_decode($OrderMerchantNumber->shipping_address, true);
    
                // Get coupon (if any)
                $coupon = Coupon::find($OrderMerchantNumber->coupon_id);
    
                // Create new Order
                $order = new Order;
                $order->order_no = $OrderMerchantNumber->merchantTxnNo;
                $order->user_id = $OrderMerchantNumber->user_id;
                $order->ip = request()->ip(); // capture client IP address
    
                // Billing details
                $order->fname = $billing['first_name'] ?? '';
                $order->lname = $billing['last_name'] ?? '';
                $order->email = $billing['email'] ?? '';
                $order->mobile = $billing['mobile'] ?? '';
                $order->billing_address = $billing['address'] ?? '';
                $order->billing_country = $billing['country'] ?? '';
                $order->billing_state = $billing['state'] ?? '';
                $order->billing_city = $billing['city'] ?? '';
    
                // Shipping details
                $order->shipping_address = $shipping['address'] ?? '';
                $order->shipping_country = $shipping['country'] ?? '';
                $order->shipping_state = $shipping['state'] ?? '';
                $order->shipping_city = $shipping['city'] ?? '';
    
                // Amounts
                $order->amount = $OrderMerchantNumber->sub_total_amount;
                $order->discount_amount = $OrderMerchantNumber->discount_amount;
                $order->tax_amount = $OrderMerchantNumber->gst_amount;
                $order->final_amount = $response['amount'];
    
                $order->coupon_code_id = $coupon ? $coupon->id : 0;
                $order->coupon_code_discount_type = $coupon ? $coupon->type : null;
    
                $order->payment_method = $response['paymentMode'];
                $order->is_paid = 1;
                $order->txn_id = $response['txnID'];
                $order->save();
    
                // Save order items
                if ($order && count($fetchcheckoutProduct) > 0) {
                    foreach ($fetchcheckoutProduct as $item) {
                        $orderItem = new OrderProduct;
                        $orderItem->order_id = $order->id;
                        $orderItem->product_id = $item->product_id;
                        $orderItem->product_name = $item->product_name;
                        $orderItem->product_image = $item->product_image;
                        $orderItem->product_slug = $item->product_slug;
                        $orderItem->product_variation_id = $item->product_variation_id;
                        $orderItem->sku_code = $item->sku_code;
                        $orderItem->gst_amount = $item->gst;
                        $orderItem->total = $item->price;
                        $orderItem->price = $item->price;
                        $orderItem->offer_price = $item->offer_price;
                        $orderItem->qty = $item->qty;
                        $orderItem->save();
                    }
                }
    
                // Update Transaction Data
                $updateTransaction = Transaction::where('merchantTxnNo', $merchantTxnNo)->first();
                if ($updateTransaction) {
                    $updateTransaction->txnID = $response['txnID'];
                    $updateTransaction->paymentID = $response['paymentID'];
                    $updateTransaction->method = $response['paymentMode'];
                    $updateTransaction->description = $response['respDescription'];
                    $updateTransaction->status = 2;
                    $updateTransaction->save();
                }
    
                // Delete checkout after successful order
                $OrderMerchantNumber->delete();

                if($order->user_id){
                    Cart::where('user_id', $order->user_id)->delete();
                }
    
                DB::commit(); // ✅ Commit transaction
    
               $message = $response['respDescription'] ?? 'Payment processed successfully.';
                $success_message = 'Your payment was processed successfully. Thank you!';

            
                // In production you probably don't want to show exception details; pass only friendly messages.
                // Return view with response, success_message and message
                return view('icici.thanks', compact('response', 'success_message', 'message'));
            } catch (\Exception $e) {
                 DB::rollBack(); // rollback on error
            
                // Log full exception for debugging
                \Log::error('ICICIThankyou Error: ' . $e->getMessage(), [
                    'exception' => $e,
                    'merchantTxnNo' => $merchantTxnNo ?? null,
                    'response' => $response ?? null,
                ]);
            
                // Friendly message for users
                $message = 'Something went wrong while processing your payment. Please contact support.';
            
            
                // Optionally set a lower-priority success_message to empty
                $success_message = '';
            
                // Pass $errorMessage only for debugging; view can show it conditionally.
                return view('icici.thanks', compact('response', 'success_message', 'message'));
            }
        } else {
              // Friendly message for users
            $message = 'Something went wrong while processing your payment. Please contact support.';
        
            // When APP_DEBUG = true, pass the real exception message (helpful in dev)
        
            // Optionally set a lower-priority success_message to empty
            $success_message = '';
        
            // Pass $errorMessage only for debugging; view can show it conditionally.
            return view('icici.thanks', compact('response', 'success_message', 'message'));
        }
    }

}