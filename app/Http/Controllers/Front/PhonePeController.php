<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Transaction;
use App\Models\Order;
use App\Interfaces\CheckoutInterface;

class PhonePeController extends Controller
{
    public function __construct(CheckoutInterface $checkoutRepository) 
    {
        $this->checkoutRepository = $checkoutRepository;
    }
   // Dispatch the job from the controller
    public function initiatePayment(Request $request)
    {
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
        
            $OrderData = $this->checkoutRepository->NewCreate($request->except('_token'));
            $phonepe_order_id = Order::where('id', $OrderData->order_id)->value('order_no');
            $phonepe_order_id = $phonepe_order_id?$phonepe_order_id:"";
            $final_amount = $request->input('final_amount');
            $form_data = $request->all();
            $json_form_data = json_encode($form_data);
            $redirectUrl = "https://www.win.luxcozi.com/OP/phonepe/payment-callback";
           // Replace these with your actual PhonePe API credentials
            $apiKey = 'f94f0bb9-bcfb-4077-adc0-3f8408a17bf7';
            $merchantId = 'PGTESTPAYUAT115';
            $keyIndex=1;
            $mid = rand (1000,9999).'_'.time();
            $transaction_id = $OrderData->transaction?$OrderData->transaction:"MUID_COZI".$mid;
            // Prepare the payment request data (you should customize this)
            $paymentData = array(
                'merchantId' => $merchantId,
                'merchantTransactionId' => $transaction_id,
                "merchantUserId"=>"MUID_COZI".$mid,
                'amount' => $final_amount*100, // Amount in paisa (10 INR)
                'redirectUrl'=>"$redirectUrl",
                'redirectMode'=>"POST",
                'callbackUrl'=>"$redirectUrl",
                "merchantOrderId"=> $phonepe_order_id,
                "mobileNumber"=>auth()->guard('web')->user()->mobile,
                "message"=>"Order description",
                // "email"=>"xyz@gmail.com",
                "shortName"=>"CUSTMER_Name",
                "paymentInstrument"=> array(    
                    "type"=> "PAY_PAGE",
                )
            );
            $jsonencode = json_encode($paymentData);
            $payloadMain = base64_encode($jsonencode);




            $payload = $payloadMain . "/pg/v1/pay" . $apiKey;
            $sha256 = hash("sha256", $payload);
            $final_x_header = $sha256 . '###' . $keyIndex;
            $request = json_encode(array('request'=>$payloadMain));

            $curl = curl_init();
            curl_setopt_array($curl, [
            CURLOPT_URL => "https://api-preprod.phonepe.com/apis/pg-sandbox/pg/v1/pay",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $request,
            CURLOPT_HTTPHEADER => [
                "Content-Type: application/json",
                "X-VERIFY: " . $final_x_header,
                "accept: application/json"
            ],
            ]);
            
            $response = curl_exec($curl);
            $err = curl_error($curl);
            
            curl_close($curl);
            
            if ($err) {
                echo "cURL Error #:" . $err;
            } else {
                $res = json_decode($response);
                // echo"<pre>";
                // print_r($res);
                // echo"</pre>"; 
                if(isset($res->success) && $res->success==true && $res->code=="PAYMENT_INITIATED"){
                    $paymentCode=$res->code;
                    $paymentMsg=$res->message;
                    $payUrl=$res->data->instrumentResponse->redirectInfo->url;
                    return redirect()->away($payUrl);
                }else{
                    return redirect()->back()->with('status', 'Please Try Again Later.');
                }
            }

        return redirect()->back()->with('status', 'Payment initiation in progress.');
    }

    public function confirmPayment(Request $request) {
        if($request->code == 'PAYMENT_SUCCESS'){
            $transactionId = $request->transactionId;
            $merchantId=$request->merchantId;
            $providerReferenceId=$request->providerReferenceId;
            $merchantOrderId=$request->merchantOrderId;
            $checksum=$request->checksum;
            $status=$request->code;
            //Transaction completed, You can add transaction details into database
            $data = [
                'phonepe_reference_id' => $providerReferenceId,
                'phonepe_checksum' => $checksum,
                'status' => 1,
            ];
            if($transactionId !=''){
                Transaction::where('transaction', $transactionId)->update($data); 
                $order_transaction = Transaction::where('transaction', $transactionId)->first();
                if($order_transaction){
                    Order::where('id', $order_transaction->order_id)->update(['status'=>1, 'payment_method'=>'online_payment']); 
                }
            }
            return view('front.payment.success')->with('success', 'Thank you for you order');
        }else{
            session()->flash('failure', '' .$request->code.'Something happened. Try again.');
            return redirect()->back();
            //HANDLE YOUR ERROR MESSAGE HERE
        }
            
        
    }
}
