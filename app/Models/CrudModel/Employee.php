<?php

namespace App\Models\CrudModel;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model {

    protected $guarded = [];

    public function user() {
        return $this->belongsTo('App\User');
    }

    public function departments() {
        return $this->belongsToMany('App\Department');
    }

    public function teams() {
        return $this->belongsToMany('App\Team');
    }

    public function designations() {
        return $this->belongsToMany('App\Designation');
    }
}
