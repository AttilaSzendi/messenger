<?php

namespace Stilldesign\Messenger\Handlers;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Stilldesign\Messenger\Contracts\ConversationMessageSearchHandlerInterface;
use Stilldesign\Messenger\Contracts\ConversationRepositoryInterface;
use Stilldesign\Messenger\Models\Conversation;

class ConversationMessageSearchHandler implements ConversationMessageSearchHandlerInterface
{
    protected $repository;
    protected $messageCountBeforeOccurrence = 5;
    protected $messageCountAfterOccurrence = 5;

    /**
     * ConversationMessageSearchHandler constructor.
     * @param ConversationRepositoryInterface $repository
     */
    public function __construct(ConversationRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function handle(Conversation $conversation, string $searchString, $messageId = null)
    {
        $searchInfo = $this->findMessageId($conversation, $searchString, $messageId);

        if (!$searchInfo['messageId']) {
            throw new ModelNotFoundException('Message not found exception');
        }

        $range = $this->getRange($searchInfo['messageId']);

        $results = $this->repository->findMessagesAroundSearchResult($conversation, $range);

        return [
            'currentMessageId' => $searchInfo['messageId'],
            'currentMessageResults' => $results,
            'occurrences' => $searchInfo['occurrenceIds']
        ];
    }

    protected function getRange($latestOccurrence)
    {
        return [
            $latestOccurrence - $this->messageCountBeforeOccurrence,
            $latestOccurrence + $this->messageCountAfterOccurrence
        ];
    }

    /**
     * @param Conversation $conversation
     * @param string $searchString
     * @param $messageId
     * @return mixed
     */
    protected function findMessageId(Conversation $conversation, string $searchString, $messageId)
    {
        $occurrenceIds = $this->repository->findWhereBodyLike($conversation, $searchString);

        if (!$messageId) {
            $messageId = $occurrenceIds[count($occurrenceIds) - 1] ?? false;
        }

        return [
            'messageId' => $messageId,
            'occurrenceIds' => $occurrenceIds ?? []
        ];
    }
}
