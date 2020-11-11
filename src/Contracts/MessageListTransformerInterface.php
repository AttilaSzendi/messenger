<?php

namespace Stilldesign\Messenger\Contracts;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface MessageListTransformerInterface
{
    public function transform(LengthAwarePaginator $messages, $loggedInUserId);
}
