<?php

namespace App\Http\Controllers\Admin;

use App\Models\Gift;
use App\Models\WalletTxn;
use App\Models\CouponUsage;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Session as FacadesSession;
class GiftController extends Controller
{
    public function index(Request $request)
    {
        
       $data = Gift::latest('id')->paginate(25);
       
        return view('admin.gift.index', compact('data'));
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
		//if(!empty($request['multiple_user_count'])){
			
       	// $storeData->user_count = $request['multiple_user_count'];
		// $storeData->type='multiple';
		//}else{
		 $storeData->user_count = $request['user_count'];
		// $storeData->type='single';
		//}
        $storeData->gift_title = $request['gift_title'];
		//$storeData->limit = $request['limit'];
        $storeData->start_date = $request['start_date']?? '';
        $storeData->end_date = $request['end_date']?? '';
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
      // if(!empty($request['multiple_user_count'])){
			
       //	 $storeData->user_count = $request['multiple_user_count'];
		// $storeData->type='multiple';
	//	}else{
		 $storeData->user_count = $request['user_count'];
		// $storeData->type='single';
		//}
        $storeData->gift_title = $request['gift_title'] ?? '';
		//$storeData->limit = $request['limit'];
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
        $data = QRCode::orderby('id','desc')->get()->toArray();

        if (count($data) > 0) {
            $delimiter = ","; 
            $filename = "qrcodes-".date('Y-m-d').".csv"; 

            // Create a file pointer 
            $f = fopen('php://memory', 'w'); 

            // Set column headers 
            $fields = array('SR', 'CODE', 'QRCODE DETAILS', 'POINTS', 'START DATE', 'END DATE','MAX TIME USE' ,'STATUS','DATETIME'); 
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
	
	
	public function CSVUpload(Request $request)
    {
		 $request->validate([
            "file" => "required",
          

        ]);
        if (!empty($request->file)) {
            $file = $request->file('file');
            $filename = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();
            $tempPath = $file->getRealPath();
            $fileSize = $file->getSize();
            $mimeType = $file->getMimeType();

            $valid_extension = array("csv");
            $maxFileSize = 50097152;
            if (in_array(strtolower($extension), $valid_extension)) {
                if ($fileSize <= $maxFileSize) {
                    $location = 'public/uploads/csv';
                    $file->move($location, $filename);
                    // $filepath = public_path($location . "/" . $filename);
					$filepath = $location."/".$filename;
					
					// dd($filepath);
					
                    $file = fopen($filepath, "r");
                    $importData_arr = array();
                    $i = 0;
                    while (($filedata = fgetcsv($file, 10000, ",")) !== FALSE) {
                        $num = count($filedata);
                        // Skip first row
                        if ($i == 0) {
                            $i++;
                            continue;
                        }
                        for ($c = 0; $c < $num; $c++) {
                            $importData_arr[$i][] = $filedata[$c];
                        }
                        $i++;
                    }
                    fclose($file);
                        $count = $total = 0;
                        $successArr = $failureArr = [];
                        $commaSeperatedCats = '';

                    foreach ($importData_arr as $importData) {
                           
                        $insertData = array(
                            "user_count" => isset($importData[0]) ? $importData[0] : null,
                            "gift_title" => isset($importData[1]) ? $importData[1] : null,
                            "start_date" => isset($importData[2]) ? $importData[2] : null,
							"end_date" => isset($importData[3]) ? $importData[3] : null,
                            "status" => 1,
							"created_at" => now(),
							"updated_at" => now(),
                          
                        );

                        $resp = Gift::insertData($insertData, $count,$successArr,$failureArr);
                        $count = $resp['count'];
                        $successArr = $resp['successArr'];
                        $failureArr = $resp['failureArr'];
                        $total++;
                    }

                    if($count==0){
                        FacadesSession::flash('csv', 'Already Uploaded. ');
                    }
                    else{
                         FacadesSession::flash('csv', 'Import Successful. '.$count.' Data Uploaded');
                    }
            } else {
                Session::flash('message', 'File too large. File must be less than 50MB.');
            }
        } else {
            Session::flash('message', 'Invalid File Extension. supported extensions are ' . implode(', ', $valid_extension));
        }
    } else {
        Session::flash('message', 'No file found.');
    }
        return redirect()->back();
    }

}