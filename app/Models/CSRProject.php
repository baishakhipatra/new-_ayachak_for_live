<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CSRProject extends Model
{
    use HasFactory;
    protected $fillable = ['title', 'description', 'image', 'file'];

    public function tags()
    {
        return $this->belongsToMany(
            Tag::class,
            'c_s_r_project_tag',   
            'csr_project_id',     
            'tag_id'               
        );
    }
}
