<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BannerTitle extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'sub_title',
        'description',
        'banner_videos',
        'banner_image'
    ];
}
