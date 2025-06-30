<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Message extends Model
{
    use SoftDeletes;

    protected $fillable = ['chat_id', 'sender_id', 'message', 'type', 'receiver_id'];

    public function chat()
    {
        return $this->belongsTo(Chat::class);
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id')->withDefault([
            'name' => 'حساب محذوف'
        ]);
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }
}
