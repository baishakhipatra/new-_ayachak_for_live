<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WinnerProductDispatch extends Model
{
	protected $table='winner_product_dispatchs';
    public function userDetails() {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }

    
}