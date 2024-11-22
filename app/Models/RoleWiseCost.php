<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class RoleWiseCost extends Model{
    protected $table='role_wise_cost';
    protected $fillable=[
        'role',
        'cost',
    ];

}
