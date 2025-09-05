<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;
    protected $fillable = ['name'];

    public function csrProjects()
    {
        return $this->belongsToMany(
            CSRProject::class,
            'c_s_r_project_tag',
            'tag_id',
            'csr_project_id'
        );
    }

}
