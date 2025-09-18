<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;
    protected $fillable = ['cat_id', 'sub_cat_id', 'collection_id', 'name', 'short_desc', 'desc', 'price', 'offer_price', 'slug', 'meta_title', 'meta_desc', 'meta_keyword', 'style_no', 'image'];

    public function category() {
        return $this->belongsTo('App\Models\Category', 'cat_id', 'id');
    }

    public static function insertData($data, $count) {
        //dd($data)
        // echo "<pre>";
        // print_r($key);
        // exit;
        $id='';
        $value = DB::table('products')->where('style_no', $data['style_no'])->where('name', $data['name'])->get();
        //dd($value);
        if($value->count() == 0) {

         $id = DB::table('products')->insertGetId($data);
        // dd($id);
          //$id = Users::select('id')->where('name','sara')->first();
         // $id = DB::table('users')->select('id')->orderBy('id', 'desc')->first();

         $count++;
         $resp = [
            "count" => $count,
            "id" => $id,
        ];
       // dd($resp);
        return $resp;
        } else{
            $resp = [
            "count" => 0,
            "id" => $id,
        ];
       // dd($resp);
        return $resp;
        }
        // echo "<pre>";
        // print_r($id);
        // exit;
        // dd($id);
    }

    public static function insertProductData($data, $successCount){
        $resp = [];
        if (isset($data)) {
            // check if SKU exists
            // dd($data);
            DB::table('products')->insert([
                'cat_id' => $data['cat_id'],
                'style_no' => $data['style_no'],
                'name' => $data['name'],
                'fabric' => $data['fabric'],
                'price' => $data['price'],
                'offer_price' => $data['offer_price'],
                'gst' => $data['gst'],
                'short_desc' => $data['short_desc'],
                'brand' => $data['brand'],
                'pattern' => $data['pattern'],
                'image' => $data['image'],
                'slug' => $data['slug'],
                'wash_care' => $data['wash_care'],
                // 'position' => $data['COLOR_POSITION'],
                'status' => $data['status']
            ]);
            $successCount++;
    
            $csvStatus = 'success';
            $csvDesc = 'All correct data provided';
        }else{
            $csvStatus = 'error';
            $csvDesc = 'Failed to import';
        }
        
        $resp = [
            "successCount" => $successCount,    
        ];

        return $resp;
    }

    // New code
    public function variations()
    {
        return $this->hasMany(ProductVariation::class);
    }
    
}
