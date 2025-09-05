<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\Coupon;

class PromotionController extends Controller
{
    public function index(Request $request)
    {
		return view('front.promotion.index');
    }

	public function store(Request $request)
    {
        // dd($request->all());

        $validator = Validator::make($request->all(),[
            'name' => 'required | string | max:200',
            'phone' => 'required',
            'email' => 'required | email | unique:promotions,email',
        ], [
			'email.unique' => 'Thank you for your interest. We already generated a Coupon for you'
		]);

        if ($validator->fails()) {
			// return redirect()->back()->with('failure', $validator->errors()->first())->withInput($request->all());
            return redirect(url()->previous() .'#partnerForm')->withErrors($validator)->withInput();
        }

		$discount = 30;
		$validityUpto = date('Y-m-d', strtotime('+30 days'));
		$validityUpto = '2022-10-31';

		// generate new coupon
		$coupon = new Coupon;
        $coupon->name = $discount."% promotional coupon";
        $coupon->coupon_code = strtoupper(generateUniqueAlphaNumeric(10));
        $coupon->is_coupon = 1;
		$coupon->type = 1;
        $coupon->amount = $discount;
        $coupon->max_time_of_use = 1;
        $coupon->max_time_one_can_use = 1;
        $coupon->start_date = date('Y-m-d');
        $coupon->end_date = $validityUpto;
        $coupon->save();

        $values = [
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'coupon_code_id' => $coupon->id
        ];
        $resp = DB::table('promotions')->insertGetId($values);

        if ($resp) {
            // send mail
			$email_data = [
                'name' => $request->name,
                'subject' => 'Onn - '.$discount.'% Promotional coupon',
                'email' => $request->email,
				'coupon_id' => $coupon->id,
				'discountPercentage' => $discount,
				'from' => 'info@onninternational.com',
                'blade_file' => 'front/mail/promotion',
            ];
            SendMail($email_data);

            // return redirect()->back()->with('success', 'Thank you for your information ! Please check your email id for Coupon Code.');
            return redirect()->route('front.promotion.success');
        } else {
            return redirect()->back()->with('failure', 'Something happened ! Please try again.');
        }
    }
}
