<?php

namespace Stilldesign\Messenger\Contracts;

use Stilldesign\Messenger\Models\Message;

interface MessageTransformerInterface
{
    public function transform(Message $message, $loggedInUserId): array;
}
