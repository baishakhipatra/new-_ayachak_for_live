<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Address extends Model
{
    use SoftDeletes;
    protected $fillable = ['user_id', 'address', 'landmark', 'lat', 'lng', 'state', 'city', 'pin', 'type'];

    public function user() {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }
}
