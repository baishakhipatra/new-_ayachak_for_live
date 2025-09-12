<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductVariation extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'product_variation';

    protected $fillable = [
        'product_id',
        'weight',
        'code',
        'position',
        'price',
        'offer_price',
        'stock',
        'status',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    public function images()
    {
        return $this->hasMany(ProductVariationImage::class, 'product_variation_id');
    }

}
