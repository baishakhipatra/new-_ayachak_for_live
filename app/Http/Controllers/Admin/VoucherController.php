<?php

namespace App\Http\Controllers\Admin;

use App\Interfaces\CouponInterface;
use App\Models\Coupon;
use App\Models\CouponUsage;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class VoucherController extends Controller
{
    // private CouponInterface $couponRepository;

    public function __construct(CouponInterface $couponRepository)
    {
        $this->couponRepository = $couponRepository;
    }

    public function index(Request $request)
    {
        if (!empty($request->term)) {
            $data = $this->couponRepository->getSearchVouchers($request->term);
        } else {
            $data = $this->couponRepository->listAllVouchers();
        }
        return view('admin.voucher.index', compact('data'));
    }

    public function create(Request $request)
    {
        return view('admin.voucher.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            "is_coupon" => "required|integer",
            "generate_number" => "required|numeric|min:0|not_in:0",
            "name" => "required|string|max:255",
            "type" => "required|integer",
            "amount" => "required|numeric|min:0|not_in:0",
            "max_time_of_use" => "required|integer",
            "max_time_one_can_use" => "required|integer",
            "start_date" => "required",
            "end_date" => "required",
        ]);

        $params = $request->except('_token');
        $storeData = $this->couponRepository->createVoucher($params);

        if ($storeData) {
            return redirect()->route('admin.voucher.index')->with('success', 'New voucher created');
        } else {
            return redirect()->route('admin.voucher.create')->withInput($request->all())->with('success', 'Something happened');
        }
    }

    public function show(Request $request, $slug)
    {
        $data = Coupon::where('slug', $slug)->first();
        $coupons = Coupon::where('slug', $slug)->get();
        $usage = CouponUsage::where('coupon_code_id', $data->id)->get();
        return view('admin.voucher.detail', compact('data', 'usage', 'coupons'));
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
            return redirect()->route('admin.voucher.index')->with('success', 'Voucher updated');
        } else {
            return redirect()->route('admin.voucher.create')->withInput($request->all())->with('success', 'Something happened');
        }
    }

    public function status(Request $request, $id)
    {
        $storeData = $this->couponRepository->toggle($id);

        if ($storeData) {
            return redirect()->route('admin.voucher.index');
        } else {
            return redirect()->route('admin.voucher.create')->withInput($request->all());
        }
    }

    public function destroy(Request $request, $id)
    {
        $this->couponRepository->delete($id);

        return redirect()->route('admin.voucher.index');
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

                return redirect()->route('admin.voucher.index')->with('success', 'Selected items deleted');
            } else {
                return redirect()->route('admin.voucher.index')->with('failure', 'Please select an action')->withInput($request->all());
            }
        } else {
            return redirect()->route('admin.voucher.index')->with('failure', $validator->errors()->first())->withInput($request->all());
        }
    }

    public function csvExport(Request $request)
    {
        $data = Coupon::where('is_coupon', 0)->get()->toArray();

        if (count($data) > 0) {
            $delimiter = ","; 
            $filename = "onninternational-voucher-".date('Y-m-d').".csv"; 

            // Create a file pointer 
            $f = fopen('php://memory', 'w'); 

            // Set column headers 
            $fields = array('SR', 'VOUCHER CODE', 'VOUCHER DETAILS', 'DISCOUNT TYPE', 'DISCOUNT AMOUNT', 'START DATE', 'END DATE', 'DATETIME'); 
            fputcsv($f, $fields, $delimiter); 

            $count = 1;

            foreach($data as $row) {
                $datetime = date('j F, Y h:i A', strtotime($row['created_at']));

                $lineData = array(
                    $count,
                    $row['coupon_code'],
                    $row['name'],
                    $row['type'] == 1 ? 'Percentage discount' : 'Flat discount',
                    $row['type'] == 1 ? $row['amount'].'%' : 'Rs.'.$row['amount'],
                    $row['start_date'],
                    $row['end_date'],
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
        $data = Coupon::where('is_coupon', 0)->where('slug', $slug)->get()->toArray();

        if (count($data) > 0) {
            $delimiter = ","; 
            $filename = "onninternational-voucher-".$slug."-".date('Y-m-d').".csv"; 

            // Create a file pointer 
            $f = fopen('php://memory', 'w'); 

            // Set column headers 
            $fields = array('SR', 'VOUCHER CODE', 'VOUCHER DETAILS', 'DISCOUNT TYPE', 'DISCOUNT AMOUNT', 'START DATE', 'END DATE', 'DATETIME'); 
            fputcsv($f, $fields, $delimiter); 

            $count = 1;

            foreach($data as $row) {
                $datetime = date('j F, Y h:i A', strtotime($row['created_at']));

                $lineData = array(
                    $count,
                    $row['coupon_code'],
                    $row['name'],
                    $row['type'] == 1 ? 'Percentage discount' : 'Flat discount', 
                    $row['type'] == 1 ? $row['amount'] . '%' : 'Rs.' . $row['amount'],
                    $row['start_date'],
                    $row['end_date'],
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