<?php

namespace Stilldesign\Messenger\Handlers;

use Stilldesign\Messenger\Contracts\ConversationRepositoryInterface;
use Stilldesign\Messenger\Contracts\MessageCreateHandlerInterface;
use Stilldesign\Messenger\Contracts\MessageRequestTransformerInterface;
use Stilldesign\Messenger\Contracts\MessageTransformerInterface;
use Stilldesign\Messenger\Models\Conversation;
use Stilldesign\Messenger\Models\Message;

class MessageCreateHandler implements MessageCreateHandlerInterface
{
    protected $messageRequestTransformer;
    protected $messageTransformer;
    protected $conversationRepository;

    public function __construct(
        MessageRequestTransformerInterface $messageRequestTransformer,
        MessageTransformerInterface $messageTransformer,
        ConversationRepositoryInterface $conversationRepository
    ) {
        $this->messageRequestTransformer = $messageRequestTransformer;
        $this->messageTransformer = $messageTransformer;
        $this->conversationRepository = $conversationRepository;
    }

    public function handle(Conversation $conversation, array $request): Message
    {
        return $message = $this->conversationRepository->createMessage(
            $conversation,
            $request
        )->load('sender');
    }
}
