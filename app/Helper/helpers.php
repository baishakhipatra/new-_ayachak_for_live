<?php

use App\Models\Order;
use Illuminate\Support\Facades\Mail;
use App\Models\Settings;
use App\Models\Wishlist;
use Illuminate\Support\Facades\Auth;

// $ip = $_SERVER['REMOTE_ADDR'];

// send mail helper
function SendMail($data)
{
	if(isset($data['from']) || !empty($data['from'])) {
		$mail_from = $data['from'];
	} else {
		$mail_from = 'info@onninternational.com';
		// $mail_from = 'support@onninternational.com';
	}
	// $mail_from = $data['from'] ? $data['from'] : 'support@onninternational.com';

    // mail log
    $newMail = new \App\Models\MailLog();
    $newMail->from = $mail_from;
    $newMail->to = $data['email'];
    $newMail->subject = $data['subject'];
    $newMail->blade_file = $data['blade_file'];
    $newMail->payload = json_encode($data);
    $newMail->save();

    // send mail
    Mail::send($data['blade_file'], $data, function ($message) use ($data) {
		if(isset($data['from']) || !empty($data['from'])) {
			$mail_from = $data['from'];
		} else {
            $mail_from = 'info@onninternational.com';
            // $mail_from = 'support@onninternational.com';
		}

		// $mail_from = $data['from'] ? $data['from'] : 'support@onninternational.com';
        $message->to($data['email'], $data['name'])->subject($data['subject'])->from($mail_from, env('APP_NAME'));
    });
}


// multi-dimensional in_array
function in_array_r($needle, $haystack, $strict = false) {
    foreach ($haystack as $item) {
        if (($strict ? $item === $needle : $item == $needle) || (is_array($item) && in_array_r($needle, $item, $strict))) return true;
    }
    return false;
}

// number to word
function amountInWords(float $number)
{
    $decimal = round($number - ($no = floor($number)), 2) * 100;
    $hundred = null;
    $digits_length = strlen($no);
    $i = 0;
    $str = array();
    $words = array(0 => '', 1 => 'one', 2 => 'two',
        3 => 'three', 4 => 'four', 5 => 'five', 6 => 'six',
        7 => 'seven', 8 => 'eight', 9 => 'nine',
        10 => 'ten', 11 => 'eleven', 12 => 'twelve',
        13 => 'thirteen', 14 => 'fourteen', 15 => 'fifteen',
        16 => 'sixteen', 17 => 'seventeen', 18 => 'eighteen',
        19 => 'nineteen', 20 => 'twenty', 30 => 'thirty',
        40 => 'forty', 50 => 'fifty', 60 => 'sixty',
        70 => 'seventy', 80 => 'eighty', 90 => 'ninety');
    $digits = array('', 'hundred','thousand','lakh', 'crore');
    while( $i < $digits_length ) {
        $divider = ($i == 2) ? 10 : 100;
        $number = floor($no % $divider);
        $no = floor($no / $divider);
        $i += $divider == 10 ? 1 : 2;
        if ($number) {
            $plural = (($counter = count($str)) && $number > 9) ? 's' : null;
            $hundred = ($counter == 1 && $str[0]) ? ' and ' : null;
            $str [] = ($number < 21) ? $words[$number].' '. $digits[$counter]. $plural.' '.$hundred:$words[floor($number / 10) * 10].' '.$words[$number % 10]. ' '.$digits[$counter].$plural.' '.$hundred;
        } else $str[] = null;
    }
    $Rupees = implode('', array_reverse($str));
    $paise = ($decimal > 0) ? "." . ($words[$decimal / 10] . " " . $words[$decimal % 10]) . ' Paise' : '';
    return ($Rupees ? $Rupees . 'Rupees ' : '') . $paise;
}

// variation colors fetch
function variationColors(int $productId, int $maxColorsToShow) {
    $relatedProductsVariationRAW = \DB::select('SELECT pc.id, pc.position, pc.color AS color_id, c.name as org_color_name, c.code as color_code, pc.status, pc.color_name as updated_color_name, pc.color_fabric FROM product_color_sizes pc JOIN colors c ON pc.color = c.id WHERE pc.product_id = '.$productId.' GROUP BY pc.color ORDER BY pc.position ASC');

    $resp = '';

    if (count($relatedProductsVariationRAW) > 0) {
        $resp .= '<div class="color"><ul class="product__color">';

        $usedColros = $activeColros = 1;
        foreach($relatedProductsVariationRAW as $relatedProsVarKey => $relatedProsVarVal) {
            if($relatedProsVarVal->status == 1) {
                if($usedColros < $maxColorsToShow + 1) {

                    // set color name
                    if ($relatedProsVarVal->updated_color_name) {
                        $colorNameToDislay = $relatedProsVarVal->updated_color_name;
                    } else {
                        // $orgColorName = \App\Models\Color::select('name')->where('id', $productWiseColorsVal->color)->first();

                        $colorNameToDislay = $relatedProsVarVal->org_color_name;
                    }

                    if ($relatedProsVarVal->color_fabric != null) {
                        $resp .= '<li style="background-image: url('.asset($relatedProsVarVal->color_fabric).');background-size: 20px;" class="color-holder" data-bs-toggle="tooltip" data-bs-placement="top" title="'.$colorNameToDislay.'"></li>';
                    } else {
                        if ($relatedProsVarVal->color_id == 61) {
                            $resp .= '<li style="background: -webkit-linear-gradient(left,  rgba(219,2,2,1) 0%,rgba(219,2,2,1) 9%,rgba(219,2,2,1) 10%,rgba(254,191,1,1) 10%,rgba(254,191,1,1) 10%,rgba(254,191,1,1) 20%,rgba(1,52,170,1) 20%,rgba(1,52,170,1) 20%,rgba(1,52,170,1) 30%,rgba(15,0,13,1) 30%,rgba(15,0,13,1) 30%,rgba(15,0,13,1) 40%,rgba(239,77,2,1) 40%,rgba(239,77,2,1) 40%,rgba(239,77,2,1) 50%,rgba(254,191,1,1) 50%,rgba(137,137,137,1) 50%,rgba(137,137,137,1) 60%,rgba(254,191,1,1) 60%,rgba(254,191,1,1) 60%,rgba(254,191,1,1) 70%,rgba(189,232,2,1) 70%,rgba(189,232,2,1) 80%,rgba(209,2,160,1) 80%,rgba(209,2,160,1) 90%,rgba(48,45,0,1) 90%);" class="color-holder" data-bs-toggle="tooltip" data-bs-placement="top" title="'.$colorNameToDislay.'"></li>';
                        } else {
                            $resp .= '<li style="background-color: '.$relatedProsVarVal->color_code.'" class="color-holder" data-bs-toggle="tooltip" data-bs-placement="top" title="'.$colorNameToDislay.'"></li>';
                        }
                    }

                    $usedColros++;
                }
                $activeColros++;
            }
        }

        if ($activeColros > $maxColorsToShow && $usedColros == $maxColorsToShow + 1) $resp .= '<li>+ more</li>';

        $resp .= '</ul></div>';

        return $resp;
    }
}

// unicommerce inventory map
function unicommerceInventory($sku_code, $token, $originalSku = '') {
    $url = 'https://cozyworld.unicommerce.com/services/rest/v1/inventory/inventorySnapshot/get';

    $headers = array(
        'Authorization: Bearer '.$token,
        'Content-Type: application/json',
        'Facility: cozyworld',
    );

    $channelProductId = mt_rand();

    $body2['itemTypeSKUs'] = [$sku_code];
    $body2['updatedSinceInMinutes'] = 1440;

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

    // dd($decoded_response);

    if ($decoded_response->successful == true) {
        $inventory = $decoded_response->inventorySnapshots[0]->inventory;

        // check for combo sku (1pc/ 2 pc)
        if ($originalSku != $sku_code) {
            $checkPcs = (int) substr($originalSku, -3, 1);
            $inventory = (int) floor($inventory / $checkPcs);
        }

        $payload = DB::table('product_color_sizes')
        ->where('code', $originalSku)
        ->update([
            "stock" => $inventory,
            "last_stock_synched" => date('Y-m-d H:i:s')
        ]);
        // dd($inventory);

        $payload = DB::table('third_party_payloads')->insert([
            "type" => "unicommerce",
            "status" => "success",
            "order_id" => "",
            "request_body" => json_encode($body2),
            "payload" => $response
        ]);
        return $inventory;
    } else {
        $payload = DB::table('third_party_payloads')->insert([
            "type" => "unicommerce",
            "status" => "failure",
            "order_id" => "",
            "request_body" => json_encode($body2),
            "payload" => $response
        ]);
        return false;
    }
}

function unicommerceLogin() {
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

if(!function_exists('generateUniqueAlphaNumeric')) {
	function generateUniqueAlphaNumeric($length = 10) {
		$random_string = '';
		for ($i = 0; $i < $length; $i++) {
			$number = random_int(0, 36);
			$character = base_convert($number, 10, 36);
			$random_string .= $character;
		}
		return $random_string;
	}
}

function perCustomerList($id)
{
    return Order::where('user_id',$id)->get();
}

function GSTHeading($billing_state) {
    if ($billing_state == "West Bengal" || $billing_state == "westbengal") {
        return "CGST + SGST";
    } else {
        return "IGST";
    }
}

function GSTCalculation($billing_state, $gstAmount) {
    if ($billing_state == "West Bengal" || $billing_state == "westbengal") {
        $gstAmountDivided = $gstAmount / 2;
        $showGstAmount = sprintf("%.3f", $gstAmountDivided);

        return $showGstAmount.'% + '.$showGstAmount.'%';
        // return "CGST + SGST";
    } else {
        return sprintf("%.3f", $gstAmount);
    }
}

function week_range($date) {
    $ts = strtotime($date);
    $start = (date('w', $ts) == 0) ? $ts : strtotime('last sunday', $ts);
    return array(date('Y-m-d', $start), date('Y-m-d', strtotime('next saturday', $start)));
}

function cgstCalc($billing_state, $gstAmount, $gstPercentage, $type = null) {
    $westBengalTypoArray = ['West Bengal', 'WestBengal', 'WB', 'Westbengal', 'westbengal', 'WESTBENGAL', 'WEST BENGAL', 'West bengal', 'Weat Bengal', 'west bengal', 'Wset Bengal', 'West bangal'];

    if (in_array($billing_state, $westBengalTypoArray)) {
        $amount = sprintf('%.2f', $gstAmount/2);
        $pert = sprintf('%.3f', $gstPercentage/2);

        if (!$type) {
            return $amount.'<br>('.$pert.'%)';
        } else {
            return $amount;
        }
    }
}

function sgstCalc($billing_state, $gstAmount, $gstPercentage, $type = null) {
    $westBengalTypoArray = ['West Bengal', 'WestBengal', 'WB', 'Westbengal', 'westbengal', 'WESTBENGAL', 'WEST BENGAL', 'West bengal', 'Weat Bengal', 'west bengal', 'Wset Bengal', 'West bangal'];

    if (in_array($billing_state, $westBengalTypoArray)) {
        $amount = sprintf('%.2f', $gstAmount/2);
        $pert = sprintf('%.3f', $gstPercentage/2);

        if (!$type) {
            return $amount.'<br>('.$pert.'%)';
        } else {
            return $amount;
        }
    }
}

function igstCalc($billing_state, $gstAmount, $gstPercentage, $type = null) {
    $westBengalTypoArray = ['West Bengal', 'WestBengal', 'WB', 'Westbengal', 'westbengal', 'WESTBENGAL', 'WEST BENGAL', 'West bengal', 'Weat Bengal', 'west bengal', 'Wset Bengal', 'West bangal'];

    if (!in_array($billing_state, $westBengalTypoArray)) {
        $amount = sprintf('%.2f', $gstAmount);
        $pert = sprintf('%.3f', $gstPercentage);

        if (!$type) {
            return $amount.'<br>('.$pert.'%)';
        } else {
            return $amount;
        }
    }
}

// check if provided state is West Bengal, to differentiate between CGST/ SGST & IGST
if(!function_exists('stateCheck')) {
	function stateCheck($state) {
		$westBengalTypoArray = ['West Bengal', 'WestBengal', 'WB', 'Westbengal', 'westbengal', 'WESTBENGAL', 'WEST BENGAL', 'West bengal', 'Weat Bengal', 'west bengal', 'Wset Bengal', 'West bangal'];

		if (in_array($state, $westBengalTypoArray)) {
			return true;
		} else {
			return false;
		}
	}
}

// calculate tax/ gst amount
if(!function_exists('taxCalculation')) {
	function taxCalculation($singlePrice, $qty) {
		if($singlePrice <= 1000) {
			$gst = 5;
		} else {
			$gst = 12;
		}

		$totalPrice = $singlePrice * $qty;

		$singleTax = (float) sprintf('%.2f', (($gst / (100 + $gst)) * $singlePrice));
		$totalTax = (float) sprintf('%.2f', (($gst / (100 + $gst)) * $singlePrice) * $qty);
		//dd($totalTax);
		// rate
		$afterTaxSingleValue = $singlePrice - $singleTax;

		// taxable value
		$afterTaxTotalValue = $totalPrice - $totalTax;

		return [$totalTax, $gst, $afterTaxSingleValue, $afterTaxTotalValue];
	}
}

/**
* @param string $state
* @param int $price 
* @param int $qty 
* @return array $taxAmount, $gstAmount, $rate, $taxableAmount
*/
if(!function_exists('CGSTCalculation')) {
	function CGSTCalculation($state, $price, $qty) {
		if(stateCheck($state)) {
			if($price <= 1000) {
			$gst = 5;
		    } else {
			$gst = 12;
		    }

		$totalPrice = $price * $qty;
		
	    
		$singleTax = (float) sprintf('%.2f', (($gst / (100 + $gst)) * $price));
		$totalTax = (float) sprintf('%.2f', (($gst / (100 + $gst)) * $totalPrice/2));
		
		//$totalTax= (float) sprintf('%.2f',  (($gst / 100) * $price)* $qty);
		//dd($totalTax);
		// rate
		$afterTaxSingleValue = $price - $singleTax;

		// taxable value
		$afterTaxTotalValue = $totalPrice - $totalTax;

		return [$totalTax, $gst, $afterTaxSingleValue, $afterTaxTotalValue];
			//return taxCalculation($price, $qty);
		}
	}
}

/**
* @param string $state
* @param int $price 
* @param int $qty 
* @return array $taxAmount, $gstAmount, $rate, $taxableAmount
*/
if(!function_exists('SGSTCalculation')) {
	function SGSTCalculation($state, $price, $qty) {
		if(stateCheck($state)) {
			if($price <= 1000) {
			$gst = 5;
		    } else {
			$gst = 12;
		    }

		$totalPrice = $price * $qty;

		$singleTax = (float) sprintf('%.2f', (($gst / (100 + $gst)) * $price));
		$totalTax = (float) sprintf('%.2f', (($gst / (100 + $gst)) * $totalPrice/2));
		//dd($totalTax);
		// rate
		$afterTaxSingleValue = $price - $singleTax;

		// taxable value
		$afterTaxTotalValue = $totalPrice - $totalTax;

		return [$totalTax, $gst, $afterTaxSingleValue, $afterTaxTotalValue];
			//return taxCalculation($price, $qty);
		}
	}
}

/**
* @param string $state
* @param int $price 
* @param int $qty 
* @return array $taxAmount, $gstAmount, $rate, $taxableAmount
*/
if(!function_exists('IGSTCalculation')) {
	function IGSTCalculation($state, $price, $qty) {
		if(!stateCheck($state)) {
			//return taxCalculation($price, $qty);
			if($price <= 1000) {
				$gst = 5;
			} else {
				$gst = 12;
			}

			$totalPrice = $price * $qty;

			$singleTax = (float) sprintf('%.2f', (($gst / (100 + $gst)) * $price));
			$totalTax = (float) sprintf('%.2f', (($gst / (100 + $gst)) * $price) * $qty);
			//dd($totalTax);
			// rate
			$afterTaxSingleValue = $price - $singleTax;

			// taxable value
			$afterTaxTotalValue = $totalPrice - $totalTax;

			return [$totalTax, $gst, $afterTaxSingleValue, $afterTaxTotalValue];
		}
	}
}

if(!function_exists('getTruncatedCCNumber')) {
	function getTruncatedCCNumber($ccNum){
        return str_replace(range(0,9), "*", substr($ccNum, 0, -4)) .  substr($ccNum, -4);
    }
}

function getPaymentResp($paymentId){  
   // $paymentId = 'pay_LXify6VpzmCufI';
    $settings = Settings::all();      
    $url = 'https://api.razorpay.com/v1/payments/'.$paymentId;
    
    $razorpay_key_id = env('RAZORPAY_KEY');
    $razorpay_key_secret = env('RAZORPAY_SECRET');
    
    $headers = array(
        'Content-Type: application/x-www-form-urlencoded',
        'Authorization: Basic '. base64_encode("$razorpay_key_id:$razorpay_key_secret")
    );

    // Open connection
    $ch = curl_init();
    // Set the url, number of POST vars, POST data
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET"); 
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    // Disabling SSL Certificate support temporarly
    // curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    //curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
    // Execute post
    $result = curl_exec($ch);
    // dd($result);
    //echo $result;
    //pr($result);
    curl_close($ch);

    $payment_det = json_decode($result);
    // echo $payment_det->amount;
    // dd($payment_det);

    // dd($result);
    return $payment_det;
}


//location from api

function getLocation($ip){  
  
    $url = 'https://ipinfo.io/'.$ip.'/geo';
    $headers = array(
        'Content-Type: application/json',
    );

    // Open connection
    $ch = curl_init();
    // Set the url, number of POST vars, POST data
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET"); 
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    // Disabling SSL Certificate support temporarly
    // curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    //curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
    // Execute post
    $result = curl_exec($ch);
    // dd($result);
    //echo $result;
    //pr($result);
    curl_close($ch);

    $payment_det = json_decode($result);
    // echo $payment_det->amount;
    // dd($payment_det);

    // dd($result);
    return $payment_det;
}

function active_wishhList($product_id){
    $user_id = "";
    if (Auth::guard('web')->check()) {
        $user_id = Auth::guard('web')->user()->id;
    }
    return Wishlist::where('product_id', $product_id)->where('user_id', $user_id)->first();
}

