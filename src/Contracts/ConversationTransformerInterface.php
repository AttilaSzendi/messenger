<?php

namespace Stilldesign\Messenger\Contracts;

use Stilldesign\Messenger\Models\Conversation;

interface ConversationTransformerInterface
{
    public function transform(Conversation $conversation, $loggedInUserId);
}
