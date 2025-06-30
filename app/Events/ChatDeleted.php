<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;

class ChatDeleted implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets;

    public $chat_id;
    public $user_id;
    public $receiver_id;

    public function __construct($chat_id, $user_id, $receiver_id)
    {
        $this->chat_id = $chat_id;
        $this->user_id = $user_id;
        $this->receiver_id = $receiver_id;
    }

    public function broadcastOn()
    {
        return [
            new PrivateChannel('Messenger.' . $this->user_id),
            new PrivateChannel('Messenger.' . $this->receiver_id),
        ];
    }

    public function broadcastWith()
    {
        return ['chat_id' => $this->chat_id];
    }

    public function broadcastAs()
    {
        return 'chat.deleted';
    }
}
