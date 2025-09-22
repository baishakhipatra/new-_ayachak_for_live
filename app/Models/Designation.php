<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Designation extends Model
{
    use HasFactory;
    protected $table = 'designations';
    
    protected $fillable = ['name', 'status'];
    
     public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'designation_permissions');
    }
    public function admins()
    {
        return $this->hasMany(Admin::class, 'designation_id');
    }
}
