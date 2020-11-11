<?php

namespace Stilldesign\Messenger\Contracts;

use Stilldesign\Messenger\Models\Conversation;
use Stilldesign\Messenger\Models\Message;

interface MessageCreateHandlerInterface
{
    public function handle(Conversation $conversation, array $request): Message;
}
