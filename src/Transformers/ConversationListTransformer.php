<?php

namespace Stilldesign\Messenger\Transformers;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Stilldesign\Messenger\Contracts\ConversationListTransformerInterface;
use Stilldesign\Messenger\Contracts\ConversationTransformerInterface;

class ConversationListTransformer implements ConversationListTransformerInterface
{
    protected $conversationTransformer;

    public function __construct(ConversationTransformerInterface $conversationTransformer)
    {
        $this->conversationTransformer = $conversationTransformer;
    }

    public function transform(LengthAwarePaginator $conversations, $loggedInUserId)
    {
        $conversations->getCollection()->transform(function ($conversation) use ($loggedInUserId) {
            return $this->conversationTransformer->transform($conversation, $loggedInUserId);
        });

        return $conversations;
    }
}
