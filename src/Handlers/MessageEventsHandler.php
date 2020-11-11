<?php

namespace Stilldesign\Messenger\Handlers;

use Stilldesign\Messenger\Contracts\MessageEventsHandlerInterface;
use Stilldesign\Messenger\Events\FirstMessageInConversation;
use Stilldesign\Messenger\Events\MessageHasSent;

class MessageEventsHandler implements MessageEventsHandlerInterface
{
    public function broadcastEvents(array $conversation, $messageResponse): void
    {
        if ($conversation['messageCount'] === 1) {
            broadcast(new FirstMessageInConversation($conversation))->toOthers();
        }

        broadcast(new MessageHasSent($messageResponse, $conversation))->toOthers();
    }
}
