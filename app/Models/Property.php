<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Property extends Model
{
    use SoftDeletes;

    protected $casts = [
        'created_at' => 'date'
    ];

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class)->withDefault();
    }

    public function category()
    {
        return $this->belongsTo(Category::class)->withDefault();
    }

    // Tamam
    public function images()
    {
        return $this->hasMany(Image::class);
    }

    public function favorites()
    {
        return $this->belongsToMany(User::class, 'favorites');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function views()
    {
        return $this->hasMany(PropertyView::class);
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function zone()
    {
        return $this->belongsTo(Zone::class);
    }

    public function scopeAvailable($query)
    {
        return $query->where('status', 'available');
    }
}
