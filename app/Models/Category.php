<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
// use Illuminate\Notifications\Notifiable;

class Category extends Model
{
    // use Notifiable;

    protected $fillable = ['name', 'description', 'image_path', 'banner_image', 'slug'];

    public function ProductDetails(string $orderBy = 'position', string $order = 'asc') {
        return $this->hasMany('App\Models\Product', 'cat_id', 'id')->orderBy($orderBy, $order);
    }

    public function parentCatDetails() {
        return $this->belongsTo('App\Models\CategoryParent', 'parent', 'id');
    }
}
