<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Collection extends Model
{
    protected $fillable = ['name', 'description', 'image_path', 'slug'];

    public function ProductDetails() {
        return $this->hasMany('App\Models\Product', 'collection_id', 'id')->orderBy('position_collection', 'asc');
    }
}
