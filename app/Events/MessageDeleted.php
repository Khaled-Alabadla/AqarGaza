<?php

namespace App\Events;

use App\Models\Message;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;

class MessageDeleted implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets;

    public $message;

    public function __construct(Message $message)
    {
        $this->message = $message;
    }

    public function broadcastOn()
    {
        return [
            new PrivateChannel('Messenger.' . $this->message->sender_id),
            new PrivateChannel('Messenger.' . $this->message->receiver_id),
        ];
    }

    public function broadcastWith()
    {
        return [
            'message' => $this->message,
            'chat_id' => $this->message->chat_id,
        ];
    }

    public function broadcastAs()
    {
        return 'message.deleted';
    }
}
