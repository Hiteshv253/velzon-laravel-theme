<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoleWiseUser extends Model {
    protected $table = 'role_and_month_wise_user';
    protected $fillable = [
        'role_name',
        'user_name',
        'user_email',
        'date'
    ];

    
}
