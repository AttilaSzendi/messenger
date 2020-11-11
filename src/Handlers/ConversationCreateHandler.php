<?php

namespace Stilldesign\Messenger\Handlers;

use Stilldesign\Messenger\Contracts\ConversationCreateHandlerInterface;
use Stilldesign\Messenger\Contracts\ConversationRepositoryInterface;
use Stilldesign\Messenger\Events\ConversationHasCreated;
use Stilldesign\Messenger\Http\Requests\ConversationRequest;
use Stilldesign\Messenger\Models\Conversation;

class ConversationCreateHandler implements ConversationCreateHandlerInterface
{
    protected $conversationRepository;

    public function __construct(ConversationRepositoryInterface $conversationRepository)
    {
        $this->conversationRepository = $conversationRepository;
    }

    public function handle($senderId, ConversationRequest $request)
    {
        return $this->findOrCreateConversation(
            [$senderId, $request->input('addresseeId')]
        );
    }

    public function findOrCreateConversation(array $participantIds): Conversation
    {
        $conversation = $this->conversationRepository->findParticipantsConversation($participantIds);
        if (!$conversation) {
            $conversation = $this->conversationRepository->createConversation($participantIds);
            broadcast(new ConversationHasCreated($conversation))->toOthers();
        }
        return $conversation;
    }
}
