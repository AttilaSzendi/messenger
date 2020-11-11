<?php

namespace Stilldesign\Messenger\Transformers;

use Stilldesign\Messenger\Contracts\ImageMessageRequestTransformerInterface as IImageMessageRequestTransformer;
use Stilldesign\Messenger\Http\Requests\ImageMessageRequest;

class ImageMessageRequestTransformer extends FileMessageRequestTransformer implements IImageMessageRequestTransformer
{
    public function transform(ImageMessageRequest $request)
    {
        $messageData = $this->baseTransform($request, $this->getPath($request, 'images'));

        $messageData['is_image'] = true;

        return $messageData;
    }
}
