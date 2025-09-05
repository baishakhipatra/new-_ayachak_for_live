<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $table = 'customers';
	public function giftDetails() {
        return $this->belongsTo('App\Models\Gift', 'gift_id', 'id');
    }
}