<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected $fillable = ['user_id', 'address', 'landmark', 'lat', 'lng', 'state', 'city', 'pin', 'type'];

    public function user() {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }
}
