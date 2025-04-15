<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PropertyView extends Model
{
    protected $fillable = ['property_id', 'user_id', 'ip'];

    public function property()
    {
        return $this->belongsTo(Property::class)->withDefault();
    }

    public function user()
    {
        return $this->belongsTo(User::class)->withDefault();
    }
}
