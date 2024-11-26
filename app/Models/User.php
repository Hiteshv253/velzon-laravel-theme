<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use App\Role;

class User extends Authenticatable {

    use Notifiable,
        HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'profile_image', 'vendor_id', 'is_active'
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

    public function employee() {
        return $this->hasOne('App\Employee');
    }

    /*
      public function hasRole($role_name, string $guard = null) :bool
      {
      return $this->traithasRole($role_name,$guard);
      echo "<pre>";
      if(is_string($role_name)){
      echo $role_name;
      }
      return true;
      $roles = Role::where('vendor_id',$this->vendor_id)->where('name',$role_row->name)->first();
      return true;
      if(!$roles){
      return false;
      }
      $check = DB::table('model_has_roles')->where('role_id',$roles->id)->where('model_id',$this->id)->get();
      if(!$check){
      return false;
      }
      return true;
      }
     */
}
