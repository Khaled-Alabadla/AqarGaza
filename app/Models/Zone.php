<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Zone extends Model
{
    protected $fillable = ['city_id', 'name'];

    public function properties()
    {
        return $this->hasMany(Property::class);
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }
}
