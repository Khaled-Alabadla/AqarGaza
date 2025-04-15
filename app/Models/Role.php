<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $fillable = ['name'];

    public function users()
    {
        return $this->belongsToMany(User::class, 'role_user');
    }

    public function abilities()
    {
        return $this->hasMany(RoleAbility::class, 'role_id', 'id');
    }
}
