<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use HasFactory;
    // protected $fillable = ['parent_name', 'name', 'route'];

    public function designations()
    {
        return $this->belongsToMany(Designation::class, 'designation_permissions');
    }
}
