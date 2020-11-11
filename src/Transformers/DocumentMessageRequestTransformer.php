<?php

namespace Stilldesign\Messenger\Transformers;

use Stilldesign\Messenger\Contracts\DocumentMessageRequestTransformerInterface as MessageRequestTransformer;
use Stilldesign\Messenger\Http\Requests\DocumentMessageRequest;

class DocumentMessageRequestTransformer extends FileMessageRequestTransformer implements MessageRequestTransformer
{
    public function transform(DocumentMessageRequest $request)
    {
        $messageData = $this->baseTransform($request, $this->getPath($request, 'documents'));

        $messageData['is_document'] = true;

        return $messageData;
    }
}
