<?php

namespace App\Http\Controllers\Admin;

use App\Models\QRCode;
use App\Models\UserTxnHistory;
use App\Models\CouponUsage;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class QRcodeController extends Controller
{
    public function index(Request $request)
    {
        if (!empty($request->term)) {
            $data = QRCode::where([['name', 'LIKE', '%' . $request->term . '%']])->paginate(25);
        } else {
            $data = QRCode::groupBy('name')->latest('id')->paginate(25);
        }
       // dd($data);
        return view('admin.qrcode.index', compact('data'));
    }

    public function create(Request $request)
    {
        return view('admin.qrcode.create');
    }

    public function store(Request $request)
    {
        //dd($request->all());
        $request->validate([
            "generate_number" => "required|numeric|min:0|not_in:0",
            "name" => "required|string|max:255",
          //  "points" => "required|numeric|min:0|not_in:0",
            "max_time_of_use" => "required|integer",
            "max_time_one_can_use" => "required|integer",
            "start_date" => "required",
            "end_date" => "required",
        ]);

        $params = $request->except('_token');
        function generateUniqueAlphaNumeric($length = 10) {
            $random_string = '';
            for ($i = 0; $i < $length; $i++) {
                $number = random_int(0, 36);
                $character = base_convert($number, 10, 36);
                $random_string .= $character;
            }
            return $random_string;
        }
        $noOfEntries = $request['generate_number'];
         // slug generate
         $slug = \Str::slug($request['name'], '-');
         $slugExistCount = QRCode::where('slug', $slug)->count();
         if ($slugExistCount > 0) $slug = $slug . '-' . ($slugExistCount + 1);
        for($i = 0; $i < $noOfEntries; $i++) {
        $storeData = new QRCode;
        $storeData->name = $request['name'];
        $storeData->slug = $slug;
        $storeData->code = strtoupper(generateUniqueAlphaNumeric(10));
        $storeData->generate_number = $request['generate_number'];
        //QrCode::phoneNumber($phoneNumber);
        $storeData->points = $request['points'];
        $storeData->max_time_of_use = $request['max_time_of_use'];
        $storeData->max_time_one_can_use = $request['max_time_one_can_use'];
        $storeData->start_date = $request['start_date'];
        $storeData->end_date = $request['end_date'];
        $storeData->save();
        }
        if ($storeData) {
            return redirect()->route('admin.scanandwin.index')->with('success', 'New qrcode created');
        } else {
            return redirect()->route('admin.scanandwin.create')->withInput($request->all())->with('success', 'Something happened');
        }
    }

    public function show(Request $request, $slug)
    {
        $data = QRCode::where('slug', $slug)->first();
        if (!empty($request->keyword)) {
			 $coupons = QRCode::where([['code', 'LIKE', '%' . $request->keyword . '%']])->paginate(500);
        } else {
            $coupons = QRCode::where('slug', $slug)->paginate(500);
		 }
        //$usage = WalletTxn::where('qrcode_id',$data->id)->with('users')->get();
        return view('admin.qrcode.detail', compact('data','coupons','request'));
    }
	public function useqrcode(Request $request, $slug)
    {
        $data = QRCode::where('slug', $slug)->where('no_of_usage','!=',0)->first();
        $coupons = QRCode::where('slug', $slug)->where('no_of_usage','!=',0)->get();
		//dd($coupons);
		//if(!empty($data)){
        //$usage = UserTxnHistory::where('qrcode_id',$data->id)->with('users')->get();
	//	}
		//else{
		//	$usage ='';}
        return view('admin.qrcode.useqrcode', compact('data','coupons'));
    }
    public function view(Request $request, $id)
    {
        $data = QRCode::where('id', $id)->first();
        
        $coupons = QRCode::where('id', $id)->get();
        //$usage = WalletTxn::where('qrcode_id',$data->id)->with('users')->get();
        return view('admin.qrcode.view', compact('data','coupons'));
    }


    public function edit(Request $request, $id)
    {
        $data = QRCode::findOrfail($id);
        return view('admin.qrcode.detail-edit', compact('data'));
    }

    public function update(Request $request, $id)
    {
        // dd($request->all());

        $request->validate([
            "name" => "required|string|max:255",
           
            "start_date" => "required",
            "end_date" => "required",
        ]);

        $storeData = QRCode::findOrFail($id);
        // slug generate
        if ($request->name!=$storeData->name) {
            $slug = \Str::slug($request['name'], '-');
            $slugExistCount = QRCode::where('slug', $slug)->count();
            if ($slugExistCount > 0) $slug = $slug . '-' . ($slugExistCount + 1);
            $storeData->slug = $slug;
        }
        $storeData->name = $request['name'];
        //$storeData->code = $request['code'];
        
        $storeData->start_date = $request['start_date'];
        $storeData->end_date = $request['end_date'];
        $storeData->save();

        if ($storeData) {
            return redirect('/admin/scanandwin/Qr/'.$storeData->slug.'/view')->with('success', 'qrcode updated');
        } else {
            return redirect()->route('admin.scanandwin.view')->withInput($request->all())->with('success', 'Something happened');
        }
    }

    public function status(Request $request, $id)
    {
        $storeData = QRCode::findOrFail($id);
        $slug=$storeData->slug;
        $status = ($storeData->status == 1) ? 0 : 1;
        $storeData->status = $status;
        $storeData->save();

        if ($storeData) {
            //return redirect()->route('admin.qrcode.view',$id);
            return redirect('/admin/scanandwin/Qr/'.$slug.'/view');
        } else {
            return redirect()->route('admin.qrcode.index')->withInput($request->all());
        }
    }

    public function destroy(Request $request, $id)
    {
        $storeData=RetailerBarcode::destroy($id);

        return redirect()->route('admin.qrcode.index');
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
		//$data = QRCode::orderby('id','desc')->get()->toArray();
		 if (!empty($request->keyword)) {
			 $data = QRCode::where([['code', 'LIKE', '%' . $request->keyword . '%']])->get();
        } else {
            $data = QRCode::where('slug', $request->slug)->get();
		 }
        if (count($data) > 0) {
            $delimiter = ","; 
            $filename = "qrcodes-".date('Y-m-d').".csv"; 

            // Create a file pointer 
            $f = fopen('php://memory', 'w'); 

            // Set column headers 
            $fields = array('SR', 'URL', 'CODE'); 
            fputcsv($f, $fields, $delimiter); 

            $count = 1;

            foreach($data as $row) {
                //$datetime = date('j F, Y h:i A', strtotime($row['created_at']));
				$url='https://onninternational.com/scanandwin?code='.$row['code'];
                $lineData = array(
                    $count,
					$url,
                    $row['code']
                    
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
}