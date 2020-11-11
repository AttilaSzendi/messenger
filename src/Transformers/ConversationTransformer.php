<?php

namespace Stilldesign\Messenger\Transformers;

use Stilldesign\Messenger\Contracts\ConversationTransformerInterface;
use Stilldesign\Messenger\Contracts\MessageTransformerInterface;
use Stilldesign\Messenger\Models\Conversation;

class ConversationTransformer implements ConversationTransformerInterface
{
    protected $messageTransformer;

    public function __construct(MessageTransformerInterface $messageTransformer)
    {
        $this->messageTransformer = $messageTransformer;
    }

    public function transform(Conversation $conversation, $loggedInUserId)
    {
        return [
            "id" => $conversation->id,
            "private" => $conversation->private,
            "data" => $conversation->data,
            "lastMessage" => $this->messageTransformer->transform($conversation->lastMessage, $loggedInUserId),
            "users" => $conversation->users,
            "unreadCount" => $conversation->users->where('id', $loggedInUserId)->first()->pivot->unread_count ?? 0
        ];
    }
}
