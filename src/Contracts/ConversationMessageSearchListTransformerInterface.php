<?php

namespace Stilldesign\Messenger\Contracts;

use Illuminate\Database\Eloquent\Collection;

interface ConversationMessageSearchListTransformerInterface
{
    public function transform(Collection $messages, $loggedInUserId);
}
