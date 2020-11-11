<?php

namespace Stilldesign\Messenger\Contracts;

interface MessageEventsHandlerInterface
{
    public function broadcastEvents(array $conversation, $messageResponse): void;
}
