<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Models\Coupon;
use App\Models\CoziWhatsapp;
use Illuminate\Http\Request;


class HomeController extends Controller
{
    public function StoreCoupon(Request $request){
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            "user_name" => "required|string",
            "user_mobile" => [
                "required",
                "integer",
                "digits:10",
                "regex:/^[0-9]{10}$/"
            ],
        ], [
            'user_name.required' => 'The user name field is required.',
            'user_mobile.required' => 'The user mobile field is required.',
            'user_mobile.integer' => 'The user mobile must be an integer.',
            'user_mobile.digits' => 'The user mobile must be exactly 10 digits.',
            'user_mobile.regex' => 'The user mobile must be exactly 10 digits.'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }else{
            $User_coupon = Coupon::where('user_mobile', $request->user_mobile)->get();
            if(count($User_coupon)<500){
                $couponCode = $this->generateCouponCode();
                $Coupon = Coupon::where('coupon_code', $couponCode)->where('user_mobile', $request->user_mobile)->first();
                if($Coupon){
                    $couponCode = $this->generateCouponCode();
                    $store = new Coupon;
                    $store->user_name =$request->user_name;
                    $store->user_mobile =$request->user_mobile;
                    $store->coupon_code = $couponCode;
                    $store->save();
                }else{
                    $store = new Coupon;
                    $store->user_name =$request->user_name;
                    $store->user_mobile =$request->user_mobile;
                    $store->coupon_code = $couponCode;
                    $store->save();
                }
                return response()->json([
                    'success' => true,
                    'message' => 'Coupon code generated successfully',
                    'coupon_code' => $store->coupon_code,
                ], 200);
            }else{
                return response()->json([
                    'success' => false,
                    'message' => 'Coupon has been 5 times inserted on this number.',
                    'errors' => ''
                ], 422);
            }
            
        }
    }

    public function generateCouponCode() {
        $capitalChars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $smallChars = 'abcdefghijklmnopqrstuvwxyz';
        $numbers = '0123456789';
    
        // Shuffle and pick 4 random capital characters
        $capitalCode = substr(str_shuffle($capitalChars), 0, 4);
    
        // Shuffle and pick 4 random small characters
        $smallCode = substr(str_shuffle($smallChars), 0, 4);
    
        // Shuffle and pick 2 random numbers
        $numberCode = substr(str_shuffle($numbers), 0, 2);
    
        // Concatenate all parts together
        $couponCode = $capitalCode . $smallCode . $numberCode;
    
        // Shuffle the final code to ensure randomness
        $couponCode = str_shuffle($couponCode);
    
        return $couponCode;
    }
    public function CoziTest(Request $request){
        try {
            // Retrieve the JSON data from the request body
            $requestData = $request->all();
            // Check if the 'messages' array exists in the decoded data
            $statuses = $requestData['statuses'] ?? [];
            $messages = $requestData['messages'] ?? [];
            $contacts = $requestData['contacts'] ?? [];
            $country_code = null;
            $recipient_number = null;
            $type = null;
            $text = null;
            $qr_code = null;
            $status = null;
            if (isset($messages)) {
                // Access the first message in the array
                $firstMessage = $requestData['messages'][0] ?? null;

                // Check if the first message exists and has the 'type' and 'text' fields
                if ($firstMessage && isset($firstMessage['type'], $firstMessage['text']['body'])) {
                    $text = $firstMessage['text']['body'];
                    $pattern = '/Your QR code is ([\w-]+)/';
                    
                    // Perform the regular expression match
                    if (preg_match($pattern, $text, $matches)) {
                        // Extract the QR code from the matches
                        $substringAfterQRCode = $matches[1];
                    } else {
                        $substringAfterQRCode = "";
                    }
                    // Trim any leading or trailing spaces
                    $substringAfterQRCode = trim($substringAfterQRCode);
                    $substringAfterQRCode = str_replace('.', '', $substringAfterQRCode);
                    $from = $firstMessage['from'];
                    $qr_code = $substringAfterQRCode?$substringAfterQRCode:null;
                    $type = $firstMessage['type'];
                    
                    
                    $country_code = substr($from, 0, 2);
                    $recipient_number = substr($from, 2, 11);
                    $recipient_number =$recipient_number;
                    // Now you have the 'type' and 'body' values from the first message
                    // You can use them as needed in your code
                }
            }else{
                $country_code = substr($statuses[0]['recipient_id'], 0, 2);
                $recipient_number = substr($statuses[0]['recipient_id'], 2, 11);
                $status = $statuses[0]['status'];
                $type = $statuses[0]['type'];
                $recipient_number =$recipient_number;
                $text = null;
                $qr_code = null;
            }
            
            // $brand_msisdn = $requestData['brand_msisdn'] ?? '';
            // $request_id = $requestData['request_id'] ?? '';
           
            // dd($recipient_number);
           
            // Perform operations based on the retrieved data
            if($requestData){
               
                $coziWhatsapp = new CoziWhatsapp();
                $coziWhatsapp->country_code = $country_code;
                $coziWhatsapp->mobile = $recipient_number;
                $coziWhatsapp->status = $status;
                $coziWhatsapp->type = $type;
                $coziWhatsapp->qr_code = $qr_code;
                $coziWhatsapp->text = $text;
                $coziWhatsapp->response = json_encode($requestData);
                $coziWhatsapp->save();

                $url = "https://coziscanandwin-743e8a2f5fad.herokuapp.com/api/login/send-new-link?phoneno={$recipient_number}&qrcode={$qr_code}";
                // $url = "";
                // https://coziscanandwin-743e8a2f5fad.herokuapp.com/api
                // $url = "https://luxagent-backend.onrender.com/api/login/send-new-link?phoneno={$recipient_number}&qrcode={$qr_code}";

                // Initialize a cURL session
                $ch = curl_init();

                // Set the URL
                curl_setopt($ch, CURLOPT_URL, $url);

                // Set the method to GET
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_HTTPGET, true);

                // If you need to set headers, uncomment the following line and add the required headers
                // curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Authorization: Bearer YOUR_TOKEN'));

                // Execute the request
                $response = curl_exec($ch);

                // Check for errors
                 if($recipient_number){
                    $coziWhatsapp->data = json_encode($response);
                    $coziWhatsapp->save();
                }
               

                // Close the cURL session
                curl_close($ch);
            }
            // if (strpos($text, 'Your QR Code is') !== false) {
            //     $response_data = $this->SendWhatsAppMessage($qr_code, $recipient_number);
                // dd($response_data);
                // $coziWhatsapp = new CoziWhatsapp();
                // $coziWhatsapp->response = $response_data;
                // $coziWhatsapp->save();
            // }
            // Return a response if needed
            return response()->json(['status' => 'success', 'data' => $requestData]);
        } catch (\Exception $e) {
            // Handle any exceptions
            $coziWhatsapp = new CoziWhatsapp();
            $coziWhatsapp->response = $e->getMessage();
            $coziWhatsapp->save();
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
    public function SendWhatsAppMessage($qr_code,$mobile){
    //    $mobile = "7908115612";
    $GetUser = CoziWhatsapp::where('mobile', $mobile)->where('qr_code', $qr_code)->first();
        try {
            //  Authenticate and obtain token
            $authResponse = Http::post('https://apis.rmlconnect.net/auth/v1/login/', [
                "username" => "LuxIndustriesNew",
                "password" => "Welcome@1"
            ])->json();
            // Extract token from the response
            $token = $authResponse['JWTAUTH'];
            // $coziWhatsapp = new CoziWhatsapp();
            // $coziWhatsapp->response = $token;
            // $coziWhatsapp->save();
            // Build the request body
            $data = [
                "phone" => '+91' . $mobile,
                "enable_acculync" => true,
                "media" => [
                    "type" => "media_template",
                    "template_name" => "playwin",
                    "lang_code" => "en",
                    "body" => [
                        [
                            "text" => "Hello", // Replace with your variable value
                        ],
                        [
                            "text" => "Dear", // Replace with your variable value
                        ]
                    ],
                    "button" => [
                        [
                            "button_no" => "0",
                            "url" => "index.html?qrcode=" . $qr_code . "_91" . $mobile
                        ]
                    ]
                ]
            ];
                // dd($data);
            // Make the HTTP request to send message
            $jsonData = json_encode($data);

            // Initialize cURL session
            $ch = curl_init();
        
            // Set cURL options
            curl_setopt($ch, CURLOPT_URL, 'https://apis.rmlconnect.net/wba/v1/messages');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
                'Authorization: ' . $token
            ]);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
        
            // Execute cURL request
            $response = curl_exec($ch);
            // Check for errors
            if (curl_errno($ch)) {
                $error_msg = curl_error($ch);
                curl_close($ch);
                throw new Exception('Curl error: ' . $error_msg);
            }

            // Get HTTP response status code
            $httpStatusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

            // Close cURL session
            curl_close($ch);

            // Decode the response
            // dd($response);
            $responseBody = json_decode($response, true);
            // dd($responseBody);
            if($GetUser){
                $GetUser->message = $responseBody;
                $GetUser->save();
            }

        } catch (\Exception $e) {
            // Handle any exceptions
            $coziWhatsapp = new CoziWhatsapp();
            $coziWhatsapp->response = $e->getMessage();
            $coziWhatsapp->save();
            return $e->getMessage();
        }
    }
    public function CoziUserData(){
        return CoziWhatsapp::latest()->where('mobile', '!=', null)->where('qr_code', '!=', null)->get();
    }
}
