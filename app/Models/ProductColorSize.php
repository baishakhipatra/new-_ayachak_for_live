<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ProductColorSize extends Model
{
    protected $fillable = ['id', 'product_id', 'color', 'size', 'stock', 'code', 'price', 'offer_price', 'position'];

    public function colorDetails() {
        return $this->belongsTo('App\Models\Color', 'color', 'id');
    }

    public function sizeDetails() {
        return $this->belongsTo('App\Models\Size', 'size', 'id');
    }

    public function productDetails() {
        return $this->belongsTo('App\Models\Product', 'product_id', 'id');
    }

    // csv upload
    public static function insertData($data, $successCount) {
        $resp = [];
       
        $productCheck = DB::table('products')->select('id')->where('id', $data['PRODUCT_ID'])->first();
        $color = DB::table('colors')->select('id', 'name')->where('name', $data['COLOR_MASTER'])->first();
         
        $ColorsExistCheck = DB::table('colors')->where('name', $data['COLOR_MASTER'])->first();
        $SizeExistCheck = DB::table('sizes')->select('id', 'name')->where('name', $data['SIZE'])->first();
        $colorId = null;
        $sizeId = null;
        if ($ColorsExistCheck) {
            $colorId = $ColorsExistCheck->id;
        } else {
                DB::table('colors')->insert([
                    'name' => $data['COLOR_MASTER'],
                ]);
                $color = DB::table('colors')->select('id', 'name')->where('name', $data['COLOR_MASTER'])->first();
                $colorId = $color->id;
                
            }
        if ($SizeExistCheck) {
            $sizeId = $SizeExistCheck->id;
        } else {
                DB::table('sizes')->insert([
                    'name' => $data['SIZE'],
                ]);
               
                $SizeExistCheck = DB::table('sizes')->select('id', 'name')->where('name', $data['SIZE'])->first();
                $sizeId = $SizeExistCheck->id;
                
            }
        // $code = DB::table('product_color_sizes')->select('id')->where('code', $data['SKU_CODE'])->get();

        // check if size already exists
        $sizeChk = DB::table('product_color_sizes')
        ->select('id', 'code')
        ->where('product_id', $data['PRODUCT_ID'])
        ->where('color', $colorId)
        ->where('size', $sizeId)
        ->where('code', $data['SKU_CODE'])
        ->first();
        if (!isset($sizeChk)) {
            // check if SKU exists
            DB::table('product_color_sizes')->insert([
                'product_id' => $data['PRODUCT_ID'],
                'product_style_no' => $data['PRODUCT_STYLE_NO'],
                'color' => $colorId,
                'color_name' => $color->name,
                'color_fabric' => null,
                'size' => $sizeId,
                'size_name' => $SizeExistCheck->name,
                'size_details' => null,
                'assorted_flag' => 0,
                'price' => $data['PRICE'],
                'offer_price' => $data['OFFER_PRICE'],
                'stock' => $data['STOCK'],
                'code' => $data['SKU_CODE'],
                // 'position' => $data['COLOR_POSITION'],
                'status' => $data['STATUS']
            ]);
            $successCount++;
    
            $csvStatus = 'success';
            $csvDesc = 'All correct data provided';
        } else {
            // if (!empty($sizeChk->code)) {
            //     $csvStatus = 'failure';
            //     $csvDesc = 'This Size & code already exists for this product';
            // } else {
                // update sku code
                DB::table('product_color_sizes')
                ->where('id', $sizeChk->id)
                ->update([
                    // 'code' => $data['SKU_CODE'],
                    'price' => $data['PRICE'],
                    'offer_price' => $data['OFFER_PRICE'],
                ]);
                $successCount++;
        
                $csvStatus = 'success';
                $csvDesc = 'Size exists & SKU code updated';
            // }
        }

        // csv status upload
        DB::table('csv_upload_logs')->insert([
            'type' => 'bulk product variation upload',
            'product_style_no' => $data['PRODUCT_STYLE_NO'],
            'sku_code' => $data['SKU_CODE'],
            'color' => $data['COLOR_MASTER'],
            'size' => $data['SIZE'],
            'status' => $csvStatus,
            'desc' => $csvDesc,
        ]);

        $resp = [
            "successCount" => $successCount,    
        ];

        return $resp;
    }
}
