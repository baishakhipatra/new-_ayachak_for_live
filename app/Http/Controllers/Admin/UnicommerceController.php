<?php

namespace App\Http\Controllers\Admin;

use App\Models\SyncRfq;
use App\Models\SyncReport;
use App\Models\Product;
use App\Models\ProductColorSize;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Session;

class UnicommerceController extends Controller
{
    // sync all product inside a product
    /*
    public function sync(Request $request, $id)
    {
        $productId = $id;
        $productvariation = ProductColorSize::where('product_id', $id)->get();
        foreach ($productvariation as $key => $value) {
            if (empty($value->code)) {
                return redirect()->back()->with('failure', 'SKU code missing for '.$value->sizeDetails->name);
            }

            $data = [];

            $data = [
                'id' => $value->id,
                'code' => $value->code,
            ];

            return $this->feedUnicommerce($data, $productId);
        }
    }
    */

    public function syncSingle(Request $request, $id)
    {
        // dd($id, $request->all());

        $productvariation = ProductColorSize::findOrFail($id);
        $productId = $productvariation->product_id;
        if (empty($productvariation->code)) {
            return redirect()->back()->with('failure', 'SKU code missing for '.$productvariation->colorDetails->name.' - '.$productvariation->sizeDetails->name);
        }

        // check for combo sku (1pc/ 2 pc)
        $checkPcs = substr($productvariation->code, -3);
        if($checkPcs != "1pc") {
            $code = substr($productvariation->code, 0, -3).'1PC';
        } else {
            $code = $productvariation->code;
        }

        $data = [];
        $data = [
            'id' => $productvariation->id,
            'code' => $code,
            'orgSku' => $productvariation->code
        ];

        return $this->feedUnicommerce($data, $productId);
    }

    // unicommerce
    public function feedUnicommerce($data, $productId)
    {
        $loginCred = unicommerceLogin();

        $loginResp = json_decode($loginCred);

        // if (!isset($loginResp->successful)) {
        if (!isset($loginResp->successful) || $loginResp->successful == "true") {
            $url = 'https://cozyworld.unicommerce.com/services/rest/v1/channel/createChannelItemType';
            $headers = array(
                'Authorization: Bearer '.$loginResp->access_token,
                'Content-Type: application/json'
            );

            $channelProductId = mt_rand();

            $body2['channelItemType'] = [];
            $body2['channelItemType']['channelCode'] = "ONNINTERNATIONAL";
            // $body2['channelItemType']['channelProductId'] = "$channelProductId";
            $body2['channelItemType']['channelProductId'] = $data['code'];
            $body2['channelItemType']['sellerSkuCode'] = $data['code'];
            $body2['channelItemType']['skuCode'] = $data['code'];
            $body2['channelItemType']['blockedInventory'] = 0;
            $body2['channelItemType']['live'] = "true";
            $body2['channelItemType']['disabled'] = "false";

            // dd(json_encode($body2));

            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => json_encode($body2),
                CURLOPT_HTTPHEADER => $headers,
            ));

            $response = curl_exec($curl);
            // dd($response);

            curl_close($curl);
            $decoded_response = json_decode($response);

            // inventory map
            $inventoryUpdate = unicommerceInventory($data['code'], $loginResp->access_token, $data['orgSku']);

            // dd($inventoryUpdate);

            // dd($decoded_response);

            if ($inventoryUpdate) {
                if ($decoded_response->successful == true) {
                    $payload = DB::table('third_party_payloads')->insert([
                        "type" => "unicommerce",
                        "status" => "success",
                        "order_id" => "",
                        "request_body" => json_encode($body2),
                        "payload" => $response
                    ]);
                    // return redirect()->route('admin.product.edit', $productId)->with('success', 'Inventory synced');
                    return redirect()->route('admin.product.sku_list')->with('success', 'Inventory synced');
                } else {
                    $payload = DB::table('third_party_payloads')->insert([
                        "type" => "unicommerce",
                        "status" => "failure",
                        "order_id" => "",
                        "request_body" => json_encode($body2),
                        "payload" => $response
                    ]);
                    // return redirect()->route('admin.product.edit', $productId)->with('failure', 'Something happened');
                    return redirect()->route('admin.product.sku_list')->with('failure', 'Something happened');
                }
            } else {
                $payload = DB::table('third_party_payloads')->insert([
                    "type" => "unicommerce",
                    "status" => "failure",
                    "order_id" => "",
                    "request_body" => json_encode($body2),
                    "payload" => json_encode($loginResp)
                ]);
                // return redirect()->back()->with('failure', 'Invalid login credentials');
                return redirect()->route('admin.product.sku_list')->with('failure', 'Something happened');
            }
        } else {
            $payload = DB::table('third_party_payloads')->insert([
                "type" => "unicommerce",
                "status" => "failure",
                "order_id" => "",
                "request_body" => json_encode($body2),
                "payload" => json_encode($loginResp)
            ]);
            // return redirect()->back()->with('failure', 'Invalid login credentials');
            return redirect()->route('admin.product.sku_list')->with('failure', 'Invalid login credentials');
        }
    }

    public function syncAllStart(Request $request)
    {
        // 1 get all SKUs
        $skus = ProductColorSize::select('code')->where('code', '!=', '')->where('code', '!=', NULL)->get();

        // 2 rfq store
        $rfq = new SyncRfq();
        $rfq->ip = $_SERVER['REMOTE_ADDR'];
        $rfq->start_time = date('Y-m-d H:i:s');
        $rfq->total_skus = count($skus);
        $rfq->save();

        // 3 unicommerce login
        $loginCred = unicommerceLogin();
        $loginResp = json_decode($loginCred);
        $uniInventoryUrl = 'https://cozyworld.unicommerce.com/services/rest/v1/inventory/inventorySnapshot/get';

        // if token found
        if (!empty($loginResp->access_token)) {
            $headers = array(
                'Authorization: Bearer '.$loginResp->access_token,
                'Content-Type: application/json',
                'Facility: cozyworld',
            );

            $scanCount = 0;
            foreach($skus as $key => $item) {
                // check for combo sku (1pc/ 2 pc)
                $checkPcs = substr($item->code, -3);
                if($checkPcs != "1pc") {
                    $code = substr($item->code, 0, -3).'1PC';
                } else {
                    $code = $item->code;
                }


                $body2['itemTypeSKUs'] = [$code];
                $body2['updatedSinceInMinutes'] = 1440;

                $curl = curl_init();
                curl_setopt_array($curl, array(
                    CURLOPT_URL => $uniInventoryUrl,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'POST',
                    CURLOPT_POSTFIELDS => json_encode($body2),
                    CURLOPT_HTTPHEADER => $headers,
                ));
                $response = curl_exec($curl);
                curl_close($curl);

                $decodedResponse = json_decode($response);

                // dd($decodedResponse);
                if (!empty($decodedResponse->inventorySnapshots) && count($decodedResponse->inventorySnapshots) > 0) {
                    $inventory = $decodedResponse->inventorySnapshots[0]->inventory;

                    // check for combo sku (1pc/ 2 pc)
                    if ($item->code != $code) {
                        $checkPcs = (int) substr($item->code, -3, 1);
                        $inventory = (int) floor($inventory / $checkPcs);
                    }
                } else {
                    $inventory = 0;
                }

                // 4 report generate for every sku
                $report = new SyncReport();
                $report->rfq_id = $rfq->id;
                $report->sku_code = $item->code;
                $report->inventory = $inventory;
                $report->status = $decodedResponse->successful;
                $report->api_resp = $response;
                $report->save();

                // 5 update product variation table
                ProductColorSize::where('code', $item->code)
                ->update([
                    "stock" => $inventory,
                    "last_stock_synched" => date('Y-m-d H:i:s')
                ]);

                $scanCount++;

                // if($key == 500) {
                //     break;
                // }
            }

            // update rfq close time
            $rfqUpdate = SyncRfq::findOrFail($rfq->id);
            $rfqUpdate->skus_scanned = $scanCount;
            $rfqUpdate->end_time = date('Y-m-d H:i:s');
            $rfqUpdate->save();

            return response()->json([
                'status' => 200,
                'message' => 'Inventory Sync Complete',
                'description' => 'Started at '.$rfqUpdate->start_time.' & finished at '.$rfqUpdate->end_time,
                'report' => route('admin.product.sku_list.sync.all.report.detail', $rfq->id)
            ]);
        }
    }

    public function syncAllreport(Request $request)
    {
        $data = SyncRfq::latest('id')->get();
        return view('admin.product.product-sku-all-report', compact('data'));
    }

    public function syncAllreportDetail(Request $request, $id)
    {
        if (!empty($request->keyword) || !empty($request->order) || !empty($request->type)) {
            $type = $request->input('type');

            $data = SyncReport::select('*')
            ->where('sku_code', 'like', '%'.$request->keyword.'%')
            ->where('rfq_id', $id)
            ->when(($type != "all"), function ($query) use ($type) {
                if ($type == "success") {
                    return $query->where('status', 1);
                } else {
                    return $query->where('status', 0);
                }
            })
            // ->where('status', $request->type)
            ->orderBy($request->order, 'desc')
            ->paginate(50);
        } else {
            $data = SyncReport::where('rfq_id', $id)->orderBy('inventory', 'desc')->paginate(50);
        }
        return view('admin.product.product-sku-all-report-detail', compact('data', 'id'));
    }

    public function syncAllreportDetailExport(Request $request, $id)
    {
        $data = SyncReport::where('rfq_id', $id)->orderBy('inventory', 'desc')->get()->toArray();

        // dd($data);

        if (count($data) > 0) {
            $delimiter = ",";
            $filename = "onninternational-sku-sync-report-detail-" . date('Y-m-d-H-i-s') . ".csv";

            // Create a file pointer 
            $f = fopen('php://memory', 'w');

            // Set column headers 
            $fields = array('SR', 'SKU CODE', 'INVENTORY', 'STATUS');
            fputcsv($f, $fields, $delimiter);

            $count = 1;

            foreach ($data as $row) {
                $lineData = array(
                    $count,
                    $row['sku_code'],
                    $row['inventory'],
                    $row['status']
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
