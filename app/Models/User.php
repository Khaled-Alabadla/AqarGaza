<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Crypt;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, SoftDeletes, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $guarded = [];


    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'provider_token'
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Tamam
    public function properties()
    {
        return $this->hasMany(Property::class);
    }

    public function favorites()
    {
        return $this->belongsToMany(Property::class, 'favorites', 'user_id', 'property_id')->withTimestamps();
    }

    public function participatedChats()
    {
        return Chat::where(function ($query) {
            $query->where('user_id', $this->getKey())
                ->orWhere('chats.receiver_id', $this->getKey());
        })->join('messages', 'chats.id', '=', 'messages.chat_id')
            ->select('chats.*')
            ->distinct();
    }

    public function sentMessages()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    public function receivedMessages()
    {
        return $this->hasMany(Message::class, 'receiver_id');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function views()
    {
        return $this->hasMany(PropertyView::class);
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_user');
    }

    public function blogs()
    {
        return $this->hasMany(Blog::class);
    }

    public function setProviderTokenAttribute($token)
    {
        $this->attributes['provider_token'] = Crypt::encryptString($token);
    }

    public function getProviderTokenAttribute($token)
    {
        return Crypt::decryptString($token);
    }

    public function scopeAdmins(Builder $builder)
    {
        return $builder->where('role', '<>', 'user');
    }
    public function scopeUsers(Builder $builder)
    {
        return $builder->where('role', '=', 'user');
    }
}
