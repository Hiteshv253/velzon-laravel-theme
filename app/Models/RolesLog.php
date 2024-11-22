<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RolesLog extends Model {

    protected $table = 'roles_logs';
    protected $fillable = [
        'user_name',
        'action',
        'action_by',
        'role_name',
        'resion'
    ];

    const ADD = 1;
    const REMOVE = 2;
    const ASSIGN = 1;
    const UNASSIGN = 2;
    const USERREMOVE = 3;
}
