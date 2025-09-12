<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use Illuminate\Database\Eloquent\SoftDeletes;
class Coupon extends Model
{
    use SoftDeletes;
    protected $fillable = ['name', 'coupon_code', 'type', 'amount', 'max_time_of_use', 'max_time_one_can_use', 'start_date', 'end_date'];
	
	
	 public static function insertData($data, $count) {

        // dd($data['title']);
        $value = DB::table('coupons')->where('coupon_code', $data['coupon_code'])->get();
        //dd($value);
        if($value->count() == 0) {
         DB::table('coupons')->insert($data);
         $count++;
        } else {
            
        }
        $resp = [
            "successCount" => $count
        
        ];

        return $resp;
        
    }
}
