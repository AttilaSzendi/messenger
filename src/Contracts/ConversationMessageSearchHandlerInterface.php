<?php

namespace Stilldesign\Messenger\Contracts;

use Stilldesign\Messenger\Models\Conversation;

interface ConversationMessageSearchHandlerInterface
{
    public function handle(Conversation $conversation, string $searchString, $messageId = null);
}
