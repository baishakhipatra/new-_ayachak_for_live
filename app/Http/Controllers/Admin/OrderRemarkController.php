<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

use App\Models\OrderRemark;

class OrderRemarkController extends Controller
{
    public function fetch(Request $request, $orderId)
    {
        $data = OrderRemark::where('order_id', $orderId)->latest('id')->get();

        $resp = [];
        foreach($data as $val) {
            $resp[] = [
                'comment' => $val->comment,
                // 'time' => $val->created_at->diffForHumans()
                'created_at' => \Carbon\Carbon::createFromTimeStamp(strtotime($val->created_at))->diffForHumans()
            ];
        }

        if (count($data) > 0) {
            return response()->json([
                'status' => 200,
                'message' => 'Order remark found',
                'data' => $resp
            ]);
        } else {
            return response()->json([
                'status' => 400,
                'message' => 'Order remark not found'
            ]);
        }
    }

    public function add(Request $request)
    {
        // dd($request->all());

        $validate = Validator::make($request->all(), [
            'order_id' => 'required|integer|min:1',
            'comment' => 'required|string|min:2'
        ]);

        if (!$validate->fails()) {
            $data = new OrderRemark();
            $data->order_id = $request->order_id;
            $data->comment = $request->comment;
            $data->comment_by = Auth::guard('admin')->user()->id;
            $data->created_at = date('Y-m-d H:i:s');
            $data->updated_at = date('Y-m-d H:i:s');
            $data->save();

            return response()->json([
                'status' => 200,
                'message' => 'Order remark added',
            ]);
        } else {
            return response()->json([
                'status' => 400,
                'message' => $validate->errors()->first()
            ]);
        }
    }
}