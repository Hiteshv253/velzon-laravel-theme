<?php

namespace App\Models;

use App\Permission;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class Role extends Model {

    protected $guarded = [];
    protected $fillable = [
        'vendor_id',
        'name',
        'guard_name'
    ];

    public function getNewPermission() {
        return DB::table('role_has_permissions')
                        ->join('permissions', 'permissions.id', 'role_has_permissions.permission_id')
                        ->where('role_has_permissions.role_id', $this->id)
                        ->pluck('permissions.name')
                        ->toArray();
    }
}
