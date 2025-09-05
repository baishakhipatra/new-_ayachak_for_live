<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\Customer;
use App\Models\FestiveOffer;
use App\Models\Coupon;
use App\Models\FestiveOfferContent;
class FestiveOfferController extends Controller
{
	public function __construct() {
        $this->ip = $_SERVER['REMOTE_ADDR'];
    }
    public function index(Request $request)
    {
		return view('front.festive-offer.index',compact('request'));
    }

    public function store(Request $request)
    {
         //dd($request->all());

       $validator = Validator::make($request->all(),[
            'name' => 'required | string | max:200',
            'phone' => 'required| numeric|digits:10| unique:festive_offers,phone',
            'email' => 'required | email | unique:festive_offers,email',
        ], [
			'phone.unique' => 'Thank you for your interest. Your mobile number alreday exists',
		    'email.unique' => 'Thank you for your interest. Your email alreday exists',
		]);

        if (!$validator->fails()) {
					
			$customer = new FestiveOffer;
			$customer->name = $request->name;
			$customer->phone = $request->phone;
			$customer->email=$request->email;
			$customer->save();
			$coupon=Coupon::where('name','Festive Offer 30')->first();	
			$email_data = [
                        'name' => $request->name,
                        'subject' => 'Festive Offer',
                        'email' => $request->email,
						'coupon_code' => $coupon->coupon_code,
                        'blade_file' => 'front/mail/festiveoffer',
                    ];
             SendMail($email_data);
			 
				return view('front.festive-offer.detail',compact('customer'));
												
			}else{
				 return redirect()->back()->withErrors( $validator->errors());
				
			}
     

     
    }
	
	
	public function tnc(Request $request)
    {
		$data = FestiveOfferContent::select('terms')->latest('id')->first();
		return view('front.festive-offer.terms',compact('data','request'));
    }
	
		

}
