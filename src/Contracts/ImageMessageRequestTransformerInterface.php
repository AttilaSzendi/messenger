<?php

namespace Stilldesign\Messenger\Contracts;

use Stilldesign\Messenger\Http\Requests\ImageMessageRequest;

interface ImageMessageRequestTransformerInterface
{
    public function transform(ImageMessageRequest $request);
}
