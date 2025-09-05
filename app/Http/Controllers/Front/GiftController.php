<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\Customer;
use App\Models\Gift;
use App\Models\QRCode;
use App\Models\Message;
use App\Models\UserTxnHistory;
class GiftController extends Controller
{
	public function __construct() {
        $this->ip = $_SERVER['REMOTE_ADDR'];
    }
    public function index(Request $request)
    {
		return view('front.gift.index',compact('request'));
    }

    public function store(Request $request)
    {
         //dd($request->all());

       $validator = Validator::make($request->all(),[
            'name' => 'required | string | max:200',
            'phone' => 'required| numeric|digits:10',
           // 'email' => 'required | email | unique:promotions,email',
        ], [
			'phone.unique' => 'Thank you for your interest. Your mobile number alreday exists'
		]);

        if (!$validator->fails()) {
					$qr=QRCode::where('code',$request->code)->first();
			//dd($request->get("code"));
						if(!$qr){
                            return redirect()->back()->with('failure', 'QRcode is invalid');
            			}else{
								if ($qr->start_date > \Carbon\Carbon::now()) {
                    			return redirect()->back()->with('failure', 'QRcode is not valid now');
               			 		}else{
               					 // coupon code validity check
									if ($qr->end_date < \Carbon\Carbon::now() || $qr->status == 0) {
										return redirect()->back()->with('failure', 'QRcode is not expired');
									}else{
						//no of usage check
										if ($qr->no_of_usage == $qr->max_time_of_use || $qr->no_of_usage >= $qr->max_time_of_use){
											return redirect()->back()->with('failure', 'QR Code Already Used - For Customer Support : 033-4040-2121');
										}else{
											   $usage = UserTxnHistory::where('qrcode_id',$qr->id)->where('phone',$request->phone)->count();
                             				if ($usage == $qr->max_time_one_can_use || $usage >= $qr->max_time_one_can_use) {
                                 				return redirect()->back()->with('failure', 'QR Code Already Used - For Customer Support : 033-4040-2121');
                            				}else{
													$mobileCount = UserTxnHistory::where('phone',$request->phone)->count();
													if ($mobileCount == 2) {
                                 				 	 return redirect()->back()->with('failure', 'You can only scan 2 QR codes from one mobile number,kindly use different mobile number to participate further!!! For Helpline Contact - 033-4040-2121');
                            						}else{ 
														$user_count_arr=[];
														$is_gift=0;
															$data = Message::latest('id')->first();
														// generate new coupon
														$gift = Gift::all();
														foreach($gift as $item){
															$user_count_arr[]=$item->user_count;			
														}
														$OrderChk = Customer::select('order_sequence_int')->latest('id')->first();
														if (!empty($OrderChk)) 
															$new_sequence_no = (int) $OrderChk->order_sequence_int + 1;

														else $new_sequence_no = 1;


														if(in_array($new_sequence_no,$user_count_arr)){
														 $is_gift=1;
														}


														$gift_id=NULL;
														if($is_gift==1){
														$get_gift=Gift::where('user_count',$new_sequence_no)->first();
														$gift_id=$get_gift->id;
														}
														$customer = new Customer;
														$customer->order_sequence_int = $new_sequence_no;
														$customer->name = $request->name;
														$customer->phone = $request->phone;
														$customer->is_gifted=$is_gift;
														$customer->gift_id=$gift_id;
														$customer->ip= $this->ip;
														$customer->save();

														$txn = new UserTxnHistory;
														$txn->qrcode_id = $qr->id;
														$txn->qrcode = $request->code;
														$txn->phone = $request->phone;
														$txn->customer_id=$customer->id;

														$txn->save();
														$barcodeDetails=QRCode::findOrFail($qr->id);
														$barcodeDetails->no_of_usage = $qr->no_of_usage+1;
														$barcodeDetails->save();

														if($customer->is_gifted){
															 return view('front.gift.success',compact('data','customer'));
														}else{
															return view('front.gift.detail',compact('data'));
														}
													}
											}
									}
					    }
					}
				}
		
			}else{

			//}
				//return redirect(url()->previous())->withErrors($validator)->withInput();
				 return redirect()->back()->withErrors( $validator->errors());
				// return redirect()->back()->withInput($request->all())->with('failure', 'Something happened');
			}
     

     
    }
	
	
	  public function tnc(Request $request)
    {
		$data = Message::select('terms')->latest('id')->first();
		return view('front.gift.terms',compact('data','request'));
    }
	
	public function winner(Request $request)
	{
		$data = Customer::where('is_gifted',1)->orderby('id','desc')->paginate(100);
		return view('front.gift.winner', compact('data'));
			
	}
		

}
