<?php

namespace Stilldesign\Messenger\Contracts;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface ConversationListTransformerInterface
{
    public function transform(LengthAwarePaginator $conversations, $userId);
}
