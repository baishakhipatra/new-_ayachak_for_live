<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use SoftDeletes;
    protected $table = 'customers';
	public function giftDetails() {
        return $this->belongsTo('App\Models\Gift', 'gift_id', 'id');
    }
}