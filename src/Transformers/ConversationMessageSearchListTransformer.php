<?php

namespace Stilldesign\Messenger\Transformers;

use Illuminate\Database\Eloquent\Collection;
use Stilldesign\Messenger\Contracts\ConversationMessageSearchListTransformerInterface;
use Stilldesign\Messenger\Contracts\MessageTransformerInterface;

class ConversationMessageSearchListTransformer implements ConversationMessageSearchListTransformerInterface
{
    protected $messageTransformer;

    /**
     * ConversationMessageSearchListTransformer constructor.
     * @param MessageTransformerInterface $messageTransformer
     */
    public function __construct(MessageTransformerInterface $messageTransformer)
    {
        $this->messageTransformer = $messageTransformer;
    }

    public function transform(Collection $messages, $loggedInUserId)
    {
        return $messages->transform(function ($message) use ($loggedInUserId) {
            return $this->messageTransformer->transform($message, $loggedInUserId);
        });
    }
}
