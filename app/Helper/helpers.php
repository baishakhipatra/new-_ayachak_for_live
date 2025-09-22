<?php

use App\Models\Order;
use App\Models\Settings;
use App\Models\{Admin, DesignationPermission, Designation, Permission};
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

// $ip = $_SERVER['REMOTE_ADDR'];
    if(!function_exists('hasPermissionByParent')){
        function hasPermissionByParent($parentName){
            // Ensure designation is loaded
            $user = Auth::guard('admin')->user();
            if (!$user || !$user->designation) {
                return false;
            }
            $permission_id = Permission::where('parent_name', $parentName)->value('id');
            if($permission_id){
                return DesignationPermission::where('permission_id', $permission_id)->where('designation_id', $user->designation->id)->exists();
            }else{
                return false;
            }
        }
    }

    // if(!function_exists('hasPermissionByChild')){
    //     function hasPermissionByChild($childName){
    //         // Ensure designation is loaded
    //         $user = Auth::guard('admin')->user();
    //         if (!$user || !$user->designation) {
    //             return false;
    //         }
    //         $permission_id = Permission::where('name', $childName)->value('id');
    //         if($permission_id){
    //             return DesignationPermission::where('permission_id', $permission_id)->where('designation_id', $user->designation->id)->exists();
    //         }else{
    //             return false;
    //         }
    //     }
    // }

// send mail helper
    function SendMail($data)
    {
        $mail_from = (isset($data['from']) && !empty($data['from']))
            ? $data['from']
            : 'ayachak@vanguardit.co';

            $newMail = new \App\Models\MailLog();
            $newMail->from       = $mail_from;
            $newMail->to         = $data['email'];
            $newMail->subject    = $data['subject'];
            $newMail->blade_file = $data['blade_file'];
            $newMail->payload    = json_encode([
                'to'      => $data['email'],
                'subject' => $data['subject'],
                'order_id'=> $data['order']->id ?? null
            ]);
            $newMail->save();

        // send mail
        try {
            Mail::send($data['blade_file'], $data, function ($message) use ($data, $mail_from) {
                $message->to($data['email'], $data['name'])
                        ->subject($data['subject'])
                        ->from($mail_from, env('APP_NAME'));
            });
            return true; 
        } catch (\Swift_TransportException $e) {
            Log::error('Mail sending failed due to transport issues: ' . $e->getMessage());
            dd($e->getMessage());
        } catch (Exception $e) {
            Log::error('Mail sending failed: ' . $e->getMessage());
            dd($e->getMessage());
        }
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







