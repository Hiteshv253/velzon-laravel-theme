<?php

namespace App\Models\CrudModel;

use Illuminate\Database\Eloquent\Model;

class SaveFilter extends Model {

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'module_name', 'link',
    ];
}
