<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoleAbility extends Model
{
    protected $guarded = [];

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id', 'id');
    }
}
