<?php

namespace Stilldesign\Messenger\Contracts;

use Stilldesign\Messenger\Http\Requests\MessageRequest;

interface MessageRequestTransformerInterface
{
    public function transform(MessageRequest $request): array;
}
