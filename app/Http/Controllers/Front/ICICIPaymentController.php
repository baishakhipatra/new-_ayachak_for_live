<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Order;
use App\Models\Transaction;

class ICICIPaymentController extends Controller
{
    private $initiateUrl = "https://qa.phicommerce.com/pg/api/v2/initiateSale";
    private $statusUrl = "https://qa.phicommerce.com/pg/api/command";
    private $merchantId = "T_08884";  
    private $merchantKey = "abc"; 
    private $terminalId = "YOUR_TERMINAL_ID";   

    public function initiatePayment(Request $request)
    {
        $order_id = uniqid('ORD');
        $amount = $request->final_amount;
        $user = auth()->user();

        $payload = [
            "merchantId" => $this->merchantId,
            "terminalId" => $this->terminalId,
            "uniqueTransactionNo" => $order_id,
            "txnAmount" => $amount,
            "currencyCode" => "356", // INR
            "txnType" => "SALE",
            "redirectUrl" => route('front.checkout.icici.callback'),
            "additionalInfo" => [
                "email" => $user->email,
                "mobile" => $user->mobile,
            ],
        ];

        // initiate payment via PhiCommerce
        $response = Http::post($this->initiateUrl, $payload)->json();

        \Log::info('ICICI Initiate Payload:', $payload);
        \Log::info('ICICI Initiate Response:', $response);

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

        return back()->with('error', 'Payment initiation failed.');
    }

    public function callback(Request $request)
    {
        // ICICI redirects user here after payment
        $responseData = $request->all();
        $orderId = $responseData['uniqueTransactionNo'];

        // verify payment status
        $statusCheck = Http::post($this->statusUrl, [
            "merchantId" => $this->merchantId,
            "terminalId" => $this->terminalId,
            "uniqueTransactionNo" => $orderId,
            "command" => "STATUS",
        ])->json();

        if ($statusCheck['status'] == 'SUCCESS') {
            Transaction::where('transaction', $orderId)->update([
                'status' => 1,
                'description' => 'Payment successful',
                'online_payment_id' => $statusCheck['transactionReference'],
            ]);

            return view('front.checkout.complete', [
                'order_id' => $orderId,
                'order' => $statusCheck,
            ])->with('success', 'Payment successful');
        } else {
            return view('front.checkout.failed', compact('statusCheck'))
                ->with('error', 'Payment failed.');
        }
    }

    public function checkStatus($order_id)
    {
        $status = Http::post($this->statusUrl, [
            "merchantId" => $this->merchantId,
            "terminalId" => $this->terminalId,
            "uniqueTransactionNo" => $order_id,
            "command" => "STATUS",
        ])->json();

        return response()->json($status);
    }
}

