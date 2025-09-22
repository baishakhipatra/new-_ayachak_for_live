<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Admin extends Authenticatable
{
    use Notifiable;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'phone','password','status','designation_id',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

      public function designation()
    {
        return $this->belongsTo(Designation::class, 'designation_id', 'id');
    }

     public function hasPermissionByRoute($route)
    {
        if (!$this->designation) {
            return false; // Ensure user has a designation
        }

        return $this->designation->permissions()->where('route', $route)->exists();
    }

    // public function hasPermission($route)
    // {
    //     return $this->designation
    //                 ->permissions()
    //                 ->where('route', $route)
    //                 ->exists();
    // }
}
