<?php

namespace App\Models\CrudModel;

use Illuminate\Database\Eloquent\Model;

class PermissionArrangement extends Model {

    protected $table = 'permission_arrangements';
    protected $fillable = [
        'module_id',
        'permission_label',
        'permission'
    ];
}
