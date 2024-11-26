<?php

namespace App\Models\CrudModel;

use Illuminate\Database\Eloquent\Model;

class PermissionModule extends Model {

    protected $table = 'permission_modules';
    protected $fillable = [
        'priority',
        'module_name'
    ];
}
