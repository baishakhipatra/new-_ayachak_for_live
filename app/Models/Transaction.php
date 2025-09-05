<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = ['user_id', 'order_id', 'transaction', 'amount', 'currency', 'method', 'description', 'bank', 'upi'];

    public function userDetails() {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }

    public function orderDetails() {
        return $this->belongsTo('App\Models\Order', 'order_id', 'id');
    }
}
