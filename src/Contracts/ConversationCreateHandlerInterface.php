<?php

namespace Stilldesign\Messenger\Contracts;

use Stilldesign\Messenger\Http\Requests\ConversationRequest;

interface ConversationCreateHandlerInterface
{
    public function handle($senderId, ConversationRequest $request);
}
