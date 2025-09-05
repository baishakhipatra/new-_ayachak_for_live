<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ThirdPartyPayload extends Model
{
    protected $table = 'third_party_payload';

    protected $fillable = ['type', 'order_id', 'payload'];
}
