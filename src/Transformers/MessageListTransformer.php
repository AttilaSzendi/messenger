<?php

namespace Stilldesign\Messenger\Transformers;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Stilldesign\Messenger\Contracts\MessageListTransformerInterface;
use Stilldesign\Messenger\Contracts\MessageTransformerInterface;

class MessageListTransformer implements MessageListTransformerInterface
{
    protected $messageTransformer;

    /**
     * MessageListTransformer constructor.
     * @param MessageTransformerInterface $messageTransformer
     */
    public function __construct(MessageTransformerInterface $messageTransformer)
    {
        $this->messageTransformer = $messageTransformer;
    }

    public function transform(LengthAwarePaginator $messages, $loggedInUserId)
    {
        $messages->getCollection()->transform(function ($message) use ($loggedInUserId) {
            return $this->messageTransformer->transform($message, $loggedInUserId);
        });

        return [
            "data" => array_reverse($messages->items()),
            "previousPageUrl" => $messages->previousPageUrl(),
            "nextPageUrl" => $messages->nextPageUrl(),
        ];
    }
}
