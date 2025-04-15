<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable = ['user_id', 'property_id', 'rating'];

    public function user()
    {
        return $this->belongsTo(User::class)->withDefault();
    }

    public function property()
    {
        return $this->belongsTo(Property::class)->withDefault();
    }
}
