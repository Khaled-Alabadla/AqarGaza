<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    protected $guarded = [];

    public function properties()
    {
        return $this->hasMany(Property::class);
    }

    public function zones()
    {
        return $this->hasMany(Zone::class);
    }
}
