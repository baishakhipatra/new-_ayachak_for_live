<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
class Gift extends Model
{
    protected $table = 'gifts';
	
	public static function insertData($data, $count, $successArr, $failureArr) {

        // dd($data['title']);
       // $value = DB::table('gifts')->where('gift', $data['name'])->get();
        //dd($value);
      //  if($value->count() == 0) {
         DB::table('gifts')->insert($data);
         array_push($successArr, $data['gift_title']);
         $count++;
       // } else {
        //    array_push($failureArr, $data['name']);
       // }
        $resp = [
            "count" => $count,
            "successArr" => $successArr,
            "failureArr" => $failureArr
        ];

        return $resp;
        
    }
}