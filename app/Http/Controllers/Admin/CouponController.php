<?php

namespace App\Http\Controllers\Admin;

use App\Interfaces\CouponInterface;
use App\Models\Coupon;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Session as FacadesSession;
class CouponController extends Controller
{
    // private CouponInterface $couponRepository;

    public function __construct(CouponInterface $couponRepository)
    {
        $this->couponRepository = $couponRepository;
    }

    public function index(Request $request)
    {
        if (!empty($request->term)) {
            $data = $this->couponRepository->getSearchCoupons($request->term);
        } else {
            $data = $this->couponRepository->listAllCoupons();
        }
        return view('admin.coupon.index', compact('data'));
    }

    public function store(Request $request)
    {
        $request->validate([
            "is_coupon" => "required|integer",
            "name" => "required|string|max:255",
            "coupon_code" => "required|string|max:255",
            "type" => "required|integer",
            "amount" => "required",
            "max_time_of_use" => "required|integer",
            "max_time_one_can_use" => "required|integer",
            "start_date" => "required",
            "end_date" => "required",
        ]);

        $params = $request->except('_token');
        $storeData = $this->couponRepository->create($params);

        if ($storeData) {
            return redirect()->route('admin.coupon.index')->with('success', 'New coupon created');
        } else {
            return redirect()->route('admin.coupon.create')->withInput($request->all())->with('success', 'Something happened');
        }
    }

    public function show(Request $request, $id)
    {
        $data = $this->couponRepository->listById($id);
        $usage = $this->couponRepository->usageById($id);
        return view('admin.coupon.detail', compact('data', 'usage'));
    }

    public function update(Request $request, $id)
    {
        // dd($request->all());

        $request->validate([
            "name" => "required|string|max:255",
            "coupon_code" => "required|string|max:255",
            "type" => "required|integer",
            "amount" => "required",
            "max_time_of_use" => "required|integer",
            "max_time_one_can_use" => "required|integer",
            "start_date" => "required",
            "end_date" => "required",
        ]);

        $params = $request->except('_token');
        $storeData = $this->couponRepository->update($id, $params);

        if ($storeData) {
            return redirect()->route('admin.coupon.index')->with('success', 'Coupon updated');
        } else {
            return redirect()->route('admin.coupon.create')->withInput($request->all())->with('success', 'Something happened');
        }
    }

    public function status(Request $request, $id)
    {
        $storeData = $this->couponRepository->toggle($id);

        if ($storeData) {
            return redirect()->route('admin.coupon.index');
        } else {
            return redirect()->route('admin.coupon.create')->withInput($request->all());
        }
    }

    public function destroy(Request $request, $id)
    {
        $this->couponRepository->delete($id);

        return redirect()->route('admin.coupon.index');
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
                    Coupon::where('id', $delete_id)->delete();
                }

                return redirect()->route('admin.coupon.index')->with('success', 'Selected items deleted');
            } else {
                return redirect()->route('admin.coupon.index')->with('failure', 'Please select an action')->withInput($request->all());
            }
        } else {
            return redirect()->route('admin.coupon.index')->with('failure', $validator->errors()->first())->withInput($request->all());
        }
    }
	
	
	    public function CSVUpload(Request $request)
    {
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
                    $filepath = $location . "/" . $filename;

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
                    $successCount = 0;

                    foreach ($importData_arr as $importData) {
                        $insertData = array(
                            "name" => isset($importData[0]) ? $importData[0] : null,
                            "coupon_code" => isset($importData[1]) ? $importData[1] : null,
                            "is_coupon" => 1,
                            "type" => isset($importData[2]) ? $importData[2] : null,
                            "amount" => isset($importData[3]) ? $importData[3] : null,
                            "max_time_of_use" => isset($importData[4]) ? $importData[4] : null,
                            "max_time_one_can_use" => isset($importData[5]) ? $importData[5] : 1,
                            "start_date" => isset($importData[6]) ? $importData[6] : null,
                            "end_date" => isset($importData[7]) ? $importData[7] : 1,
                            "STATUS" =>  1
                        );

                        $resp = Coupon::insertData($insertData, $successCount);
                        $successCount = $resp['successCount'];
                    }

                    FacadesSession::flash('message', 'CSV Import Complete. Total no of entries: ' . count($importData_arr) . '. Successfull: ' . $successCount . ', Failed: ' . (count($importData_arr) - $successCount));
                } else {
                    FacadesSession::flash('message', 'File too large. File must be less than 50MB.');
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