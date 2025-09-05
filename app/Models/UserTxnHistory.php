<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserTxnHistory extends Model
{
    
	
	public function users(){
    	return $this->belongsTo('App\Models\Customer', 'customer_id', 'id');
	}
}
