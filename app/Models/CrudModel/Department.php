<?php

namespace App\Models\CrudModel;

use Illuminate\Database\Eloquent\Model;

class Department extends Model {

    protected $guarded = [];

    public function employees() {
        return $this->belongsToMany('App\Employee');
    }
}
