<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FranchisePartner extends Model
{
    protected $fillable = ['id', 'name', 'phone', 'email', 'city', 'business_nature', 'region', 'property_type', 'capital', 'source', 'comment'];
}
