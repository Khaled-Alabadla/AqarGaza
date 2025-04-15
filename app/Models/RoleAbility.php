<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoleAbility extends Model
{
    protected $fillable = ['role_id', 'ability'];

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id', 'id');
    }
}
