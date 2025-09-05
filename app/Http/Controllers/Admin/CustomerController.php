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

    public function create(Request $request)
    {
        return view('admin.gift.create');
    }

    public function store(Request $request)
    {
        //dd($request->all());
        $request->validate([
          "user_count" => "required|numeric|min:0|not_in:0",
            "gift_title" => "required|string|max:255",
            "start_date" => "required",
            "end_date" => "required",
        ]);

        $params = $request->except('_token');
        
        
         // slug generate
       
        $storeData = new Gift;
        $storeData->user_count = $request['user_count'];
        $storeData->gift_title = $request['gift_title'];
        $storeData->start_date = $request['start_date']?? '';
        $storeData->end_date = $request['end_date']?? '';
        $upload_path = "public/uploads/gift/";
            $image = $request['gift_image'];
            $imageName = time() . "." . $image->getClientOriginalName();
            $image->move($upload_path, $imageName);
            $uploadedImage = $imageName;
            $storeData->gift_image = $upload_path . $uploadedImage;
            $storeData->save();
        
        if ($storeData) {
            return redirect()->route('admin.gift.index')->with('success', 'New gift created');
        } else {
            return redirect()->route('admin.gift.create')->withInput($request->all())->with('success', 'Something happened');
        }
    }

  function show(Request $request, $id)
    {
        $data = Gift::where('id', $id)->first();
        
       
        return view('admin.gift.detail', compact('data'));
    }


    public function edit(Request $request, $id)
    {
        $data = Gift::findOrfail($id);
        return view('admin.gift.edit', compact('data'));
    }

    public function update(Request $request, $id)
    {
        // dd($request->all());

       
           

        $storeData = Gift::findOrFail($id);
        // slug generate
        $storeData->user_count = $request['user_count'] ?? '';
        $storeData->gift_title = $request['gift_title'] ?? '';
		$storeData->start_date = $request['start_date'] ?? '';
        $storeData->end_date = $request['end_date'] ?? '';
        if (isset($request['gift_image'])) {
        $upload_path = "public/uploads/gift/";
            $image = $request['gift_image'];
            $imageName = time() . "." . $image->getClientOriginalName();
            $image->move($upload_path, $imageName);
            $uploadedImage = $imageName;
            $storeData->gift_image = $upload_path . $uploadedImage;
		}
        $storeData->save();

        if ($storeData) {
            return redirect()->back()->with('success', 'gift updated');
        } else {
            return redirect()->route('admin.gift.view')->withInput($request->all())->with('success', 'Something happened');
        }
    }

    public function status(Request $request, $id)
    {
        $storeData = Gift::findOrFail($id);
        $slug=$storeData->slug;
        $status = ($storeData->status == 1) ? 0 : 1;
        $storeData->status = $status;
        $storeData->save();

        if ($storeData) {
            //return redirect()->route('admin.qrcode.view',$id);
            return redirect()->back();
        } else {
            return redirect()->route('admin.gift.index')->withInput($request->all());
        }
    }

    public function destroy(Request $request, $id)
    {
        $storeData=Gift::destroy($id);

        return redirect()->route('admin.gift.index');
    }

    public function bulkDestroy(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'bulk_action' => 'required',
            'delete_check' => 'required|array',
        ], [
            'delete_check.*' => 'Please select at least one item'
        ]);

        if (!$validator->fails()) {
            if ($request['bulk_action'] == 'delete') {
                foreach ($request->delete_check as $index => $delete_id) {
                    QRCode::where('id', $delete_id)->delete();
                }

                return redirect()->route('admin.qrcode.index')->with('success', 'Selected items deleted');
            } else {
                return redirect()->route('admin.qrcode.index')->with('failure', 'Please select an action')->withInput($request->all());
            }
        } else {
            return redirect()->route('admin.qrcode.index')->with('failure', $validator->errors()->first())->withInput($request->all());
        }
    }

    public function csvExport(Request $request)
    {
         $from = $request->date_from ? $request->date_from : date('Y-m-01');
                $to = date('Y-m-d', strtotime(request()->input('date_to'). '+1 day'))? date('Y-m-d', strtotime(request()->input('date_to'). '+1 day')) : date('Y-m-d');
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

                $data = $query->orderby('customers.id','desc')->get();
                // dd($data);
            }
            else{
               // dd('hi');
                $data =Customer::select('customers.id as id','customers.order_sequence_int as order_sequence_int','customers.phone as phone','customers.is_gifted as is_gifted','customers.gift_id as gift_id','customers.ip as ip','customers.created_at as created_at','customers.name as name','user_txn_histories.qrcode as qrcode')->join('user_txn_histories', 'user_txn_histories.customer_id', 'customers.id')->whereBetween('customers.created_at', [$from, $to])->orderby('customers.id','desc')->get();
               //dd($data);
            }

        if (count($data) > 0) {
            $delimiter = ","; 
            $filename = "customer-request-".date('Y-m-d').".csv"; 

            // Create a file pointer 
            $f = fopen('php://memory', 'w'); 

            // Set column headers 
            $fields = array('SR', 'NAME', 'MOBILE', 'CODE', 'WIN STATUS', 'GIFT','DATETIME'); 
            fputcsv($f, $fields, $delimiter); 

            $count = 1;

            foreach($data as $row) {
                $datetime = date('j F, Y h:i A', strtotime($row['created_at']));
				 $giftDetails=DB::table('gifts')->where('id', $row->gift_id)->first();
                $lineData = array(
                    $row['order_sequence_int'],
                    $row['name'],
                    $row['phone'],
                    $row['qrcode'],
                    $row['is_gifted'] == 1 ? 'WIN' : 'NOT WIN',
                     $giftDetails->gift_title ?? '',
                    
                    
                    $datetime
                );

                fputcsv($f, $lineData, $delimiter);

                $count++;
            }

            // Move back to beginning of file
            fseek($f, 0);

            // Set headers to download file rather than displayed
            header('Content-Type: text/csv');
            header('Content-Disposition: attachment; filename="' . $filename . '";');

            //output all remaining data on a file pointer
            fpassthru($f);
        }
    }

    public function csvExportSlug(Request $request, $slug)
    {
        $data = QRCode::where('slug', $slug)->get()->toArray();

        if (count($data) > 0) {
            $delimiter = ","; 
            $filename = "onnb2b-reward-barcodes-".date('Y-m-d').".csv";  

            // Create a file pointer 
            $f = fopen('php://memory', 'w'); 

            // Set column headers 
            $fields = array('SR', 'CODE', 'BARCODE DETAILS', 'POINTS', 'START DATE', 'END DATE','MAX TIME USE','STATUS', 'DATETIME'); 
            fputcsv($f, $fields, $delimiter);  

            $count = 1;

            foreach($data as $row) {
                $datetime = date('j F, Y h:i A', strtotime($row['created_at']));

                $lineData = array(
                    $count,
                    $row['code'],
                    $row['name'],
                    $row['amount'],
                    $row['start_date'],
                    $row['end_date'],
                    $row['max_time_of_use'],
                    $row['status'] == 1 ? 'Active' : 'Inactive',
                    $datetime
                );

                fputcsv($f, $lineData, $delimiter);

                $count++;
            }

            // Move back to beginning of file
            fseek($f, 0);

            // Set headers to download file rather than displayed
            header('Content-Type: text/csv');
            header('Content-Disposition: attachment; filename="' . $filename . '";');

            //output all remaining data on a file pointer
            fpassthru($f);
        }
    }
	
	public function cms(Request $request)
    {
        
       $data = Message::latest('id')->first();
       //dd($data);
        return view('admin.cms.index', compact('data','request'));
    }
	
	public function cmsstore(Request $request)
    {
        //dd($request->all());
       
        $params = $request->except('_token');
        
        
         // slug generate
       
        $storeData =  Message::findOrFail($request->id);
        $storeData->success_message = $request['success_message'];
        $storeData->failure_message = $request['failure_message'];
        $storeData->save();
        
        if ($storeData) {
            return redirect()->back()->with('success', 'New cms created');
        } else {
            return redirect()->back()->withInput($request->all())->with('failure', 'Something happened');
        }
    }
	
	
	public function terms(Request $request)
    {
        
       $data = Message::latest('id')->first();
       //dd($data);
        return view('admin.cms.terms', compact('data','request'));
    }
	
	public function termsstore(Request $request)
    {
        //dd($request->all());
       
        $params = $request->except('_token');
        
        
         // slug generate
       
        $storeData =  Message::findOrFail($request->id);
        $storeData->terms = $request['terms'];
        $storeData->save();
        
        if ($storeData) {
            return redirect()->back()->with('success', 'terms and condition content updated');
        } else {
            return redirect()->back()->withInput($request->all())->with('failure', 'Something happened');
        }
    }
	
	//dispatch
	public function dispatch($id)
    {
        $data =  Customer::where('id',$id)->first();
		$dispatch = WinnerProductDispatch::where('user_id',$id)->first();
        $images = WinnerProductDispatch::where('user_id',$id)->get();
        return view('admin.userrequest.dispatch', compact('data','dispatch','images'));
    }
	//dispatch
	public function dispatchStore(Request $request)
    {
        //dd($request->all());
       
        $params = $request->except('_token');
        
        
         // slug generate
        $upload_path = "uploads/dispatch/";
        $dispatch = new WinnerProductDispatch();
		$dispatch->tracking_id=$request->tracking_id;
        $dispatch->user_id = $request->user_id;
		$dispatch->gift_id = $request->gift_id;
		if(isset($params['image'])){
                $image = $params['image'];
                $imageName = time().".".mt_rand().".".$image->getClientOriginalName();
                $image->move($upload_path, $imageName);
                $uploadedImage = $imageName;
                $data->image = $upload_path.$uploadedImage;
            }
        $dispatch->save();
        
        if ($dispatch) {
            return redirect()->back()->with('success', 'content updated');
        } else {
            return redirect()->back()->withInput($request->all())->with('failure', 'Something happened');
        }
    }



















    public function indexCSV(Request $request) {
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

                $data = $query->get();
                // dd($data);
            }
            else{
                $data =Customer::select('customers.id as id','customers.order_sequence_int as order_sequence_int','customers.phone as phone','customers.is_gifted as is_gifted','customers.gift_id as gift_id','customers.ip as ip','customers.created_at as created_at','customers.name as name','user_txn_histories.qrcode as qrcode')->join('user_txn_histories', 'user_txn_histories.customer_id', 'customers.id')->get();
               //dd($data);
            }







        if (count($data) > 0) {
            // initializing vars
            // $my_month =  explode("-",$month);
            // $year_val = $my_month[0];
            // $month_val = $my_month[1];
            // $dates_month=dates_month($month_val,$year_val);
            // $month_names = $dates_month['month_names'];
            // $date_values = $dates_month['date_values'];
            // $totaldays=count($dates_month['date_values']);

            // generating table head content
            $tableHead = ['SR', 'NAME', 'MOBILE', 'CODE', 'WIN STATUS', 'GIFT', 'DATETIME'];
            // foreach($month_names as $months) {
            //     array_push($tableHead, $months);
            // }

            // dd($tableHead);

            // generating table body
            $tableBody = [];
            foreach($data as $index => $item) {
                // $findTeamDetails = findTeamDetails($item->id, $item->type);

                // dd($findTeamDetails[0]['nsm']);

                $qr=DB::table('user_txn_histories')->where('customer_id', $item->id)->first();
                $giftDetails=DB::table('gifts')->where('id', $item->gift_id)->first();
                if(!empty($item->ip)){
                    $getLocation = getLocation($item->ip);
                    $city = $getLocation->city ?? '';
                    $region = $getLocation->region ?? '';
                    $postal = $getLocation->postal ?? '';
                    $org = $getLocation->org ?? '';
                }

                /*
                $monthlyDates = [];
                foreach($date_values as $date) {
                    $dates_attendance=dates_attendance($item->id, $date);

                    if($dates_attendance[0][0]['date_wise_attendance'][0]['is_present']=='A') {
                        $htmlRow = '<td class="redColor" style="background-color: red;color: #fff;padding: 15px;text-align: center;border: 1px solid #fff; vertical-align: middle;">'.
                            $dates_attendance[0][0]['date_wise_attendance'][0]['is_present']
                        .'</td>';
                    }
                    elseif($dates_attendance[0][0]['date_wise_attendance'][0]['is_present']=='P') {
                        $htmlRow = '<td class="redColor" style="background-color: rgb(1, 134, 52); color:#fff;padding: 15px;text-align: center;border: 1px solid #fff; vertical-align: middle;">'.
                            $dates_attendance[0][0]['date_wise_attendance'][0]['is_present']
                        .'</td>';
                    }
                    elseif($dates_attendance[0][0]['date_wise_attendance'][0]['is_present']=='W') {
                        $htmlRow = '<td class="redColor"  style="background-color: rgb(241, 225, 0); color:#fff; padding: 15px;text-align: center;border: 1px solid #fff; vertical-align: middle;">'.
                            $dates_attendance[0][0]['date_wise_attendance'][0]['is_present']
                        .'</td>';
                    }
                    else {
                        $htmlRow = '<td class="redColor"  style="background-color: #294fa1da; color:#fff; padding: 15px;text-align: center;border: 1px solid #fff; vertical-align: middle;">'.
                            $dates_attendance[0][0]['date_wise_attendance'][0]['is_present']
                        .'</td>';
                    }

                    array_push($monthlyDates, $htmlRow);
                }

                if ($item->status == 1) {
                    $empStatClass = 'success';
                    $empStatType = 'Active';
                } else {
                    $empStatClass = 'danger';
                    $empStatType = 'Inactive';
                }
                
                $empStatus = '<span class="badge bg-'.$empStatClass.'">'.$empStatType.'</span>';
                */

                $tableBody[] = [
                    $item->order_sequence_int,
                    $item->name,
                    $item->phone,
                    $item->qrcode,
                    $giftDetails->gift_title ?? '',
                    \Carbon\Carbon::parse($item->created_at)->format('d/m/Y g:i:s A')
                ];
            }

            $finalHtml = '';

            $finalHtml .= '
            <table class="table">
                <thead>
                    <tr>';
                    foreach($tableHead as $head) {
                        $finalHtml .= '<th>'.$head.'</th>';
                    }
            $finalHtml .= '</tr>
                </thead>
                <tbody>';

                    foreach($tableBody as $bodyIndex => $body) {
                        $finalHtml .=
                        '<tr>
                            <td>'.$body[0].'</td>
                            <td>'.$body[1].'</td>
                            <td>'.$body[2].'</td>
                            <td>'.$body[3].'</td>
                            <td>'.$body[4].'</td>
                            <td>'.$body[5].'</td>';

                            // monthly dates attendance
                            // foreach($body[12] as $attendance) {
                            //     $finalHtml .= $attendance;
                            // }

                        $finalHtml .= '</tr>';
                    }
            $finalHtml .= '
                </tbody>
            </table>
            ';

            // dd($finalHtml);

            return response()->json([
                'status' => 200,
                'message' => 'Data found',
                'data' => $finalHtml
            ]);
        } else {
            return response()->json([
                'status' => 400,
                'message' => 'No data found'
            ]);
        }
    }


}