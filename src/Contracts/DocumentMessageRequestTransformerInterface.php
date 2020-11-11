<?php

namespace Stilldesign\Messenger\Contracts;

use Stilldesign\Messenger\Http\Requests\DocumentMessageRequest;

interface DocumentMessageRequestTransformerInterface
{
    public function transform(DocumentMessageRequest $request);
}
