<?php

namespace App\Http\Controllers\Admin;

use App\Models\Customer;
use App\Models\WalletTxn;
use App\Models\CouponUsage;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Models\Message;
use App\Models\WinnerProductDispatch;
use DB;
class CustomerController extends Controller
{
    public function index(Request $request)
    {
        if(isset($request->date_from) || isset($request->date_to) ||isset($request->keyword)||isset($request->status)) 
            {
			$keyword=$request->keyword;
			 $from = $request->date_from ? $request->date_from : '';
                $to = date('Y-m-d', strtotime(request()->input('date_to'). '+1 day'))? date('Y-m-d', strtotime(request()->input('date_to'). '+1 day')) : '';
			$status= $request->status;
			
      // $data = Customer::select('stores.id as id','stores.unique_code as unique_code','stores.created_at as created_at','stores.store_name as store_name','stores.user_id as user_id','stores.state as state','stores.area as area','stores.city as city','stores.pin as pin','stores.address as address','stores.email as email','stores.contact as contact','stores.bussiness_name as bussiness_name','stores.status as status')->join('retailer_list_of_occ', 'retailer_list_of_occ.store_id', 'stores.id')->whereRaw("find_in_set('".$distributor."',retailer_list_of_occ.distributor_name)")->paginate(25);
		
		$query = Customer::select('customers.id as id','customers.order_sequence_int as order_sequence_int','customers.phone as phone','customers.is_gifted as is_gifted','customers.gift_id as gift_id','customers.ip as ip','customers.created_at as created_at','customers.name as name','user_txn_histories.qrcode as qrcode')->join('user_txn_histories', 'user_txn_histories.customer_id', 'customers.id');
                $query->when($keyword, function($query) use ($keyword) {
                    $query->where('customers.name', $keyword)
                    ->orWhere('customers.phone', $keyword)
                   
                    ->orWhere('user_txn_histories.qrcode', $keyword);
                });
				$query->when($status, function($query) use ($status) {
                    $query->where('customers.is_gifted', $status);
                })->whereBetween('customers.created_at', [$from, $to]);

                $data = $query->orderby('customers.id','desc')->paginate(25);
                // dd($data);
            }
            else{
                $data =Customer::select('customers.id as id','customers.order_sequence_int as order_sequence_int','customers.phone as phone','customers.is_gifted as is_gifted','customers.gift_id as gift_id','customers.ip as ip','customers.created_at as created_at','customers.name as name','user_txn_histories.qrcode as qrcode')->join('user_txn_histories', 'user_txn_histories.customer_id', 'customers.id')->orderby('customers.id','desc')->paginate(25);
               //dd($data);
            }
       //dd($data);
        return view('admin.userrequest.index', compact('data','request'));
    }


}