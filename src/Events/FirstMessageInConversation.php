<?php

namespace Stilldesign\Messenger\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class FirstMessageInConversation implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $conversation;

    public function __construct(array $conversation)
    {
        $this->conversation = $conversation;
    }

    public function broadcastAs()
    {
        return 'first-message-in-conversation';
    }

    public function broadcastOn()
    {
        $channels = [];

        foreach ($this->conversation['users'] as $user) {
            array_push($channels, new PrivateChannel('users.' . $user['id']));
        }

        return $channels;
    }
}
