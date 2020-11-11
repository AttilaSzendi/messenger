<?php

namespace Stilldesign\Messenger\Observers;

use Illuminate\Contracts\Auth\Guard;
use Stilldesign\Messenger\Contracts\ConversationRepositoryInterface;
use Stilldesign\Messenger\Models\Message;

class MessageObserver
{
    protected $conversationRepository;
    protected $guard;

    public function __construct(
        ConversationRepositoryInterface $conversationRepository,
        Guard $guard
    ) {
        $this->conversationRepository = $conversationRepository;
        $this->guard = $guard;
    }

    public function created(Message $message)
    {
        $conversation = $message->conversation;

        $recipients = $this->conversationRepository
            ->messageRecipients($conversation, $message->sender->id)
            ->pluck('id')
            ->toArray();

        $this->conversationRepository->markAsUnReadForRecipients($recipients, $conversation);
    }
}
