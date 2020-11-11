<?php

namespace Stilldesign\Messenger\Handlers;

use Stilldesign\Messenger\Contracts\ConversationRepositoryInterface;
use Stilldesign\Messenger\Contracts\ConversationTransformerInterface;
use Stilldesign\Messenger\Contracts\MessageCreateHandlerInterface;
use Stilldesign\Messenger\Contracts\MessageCreateResponseHandlerInterface;
use Stilldesign\Messenger\Contracts\MessageEventsHandlerInterface;
use Stilldesign\Messenger\Contracts\MessageTransformerInterface;

class MessageCreateResponseHandler implements MessageCreateResponseHandlerInterface
{

    protected $messageTransformer;
    protected $conversationTransformer;
    protected $messageCreateHandler;
    protected $messageEventsHandler;
    protected $conversationRepository;

    public function __construct(
        MessageTransformerInterface $messageTransformer,
        ConversationTransformerInterface $conversationTransformer,
        MessageCreateHandlerInterface $messageCreateHandler,
        MessageEventsHandlerInterface $messageEventsHandler,
        ConversationRepositoryInterface $conversationRepository
    ) {
        $this->messageTransformer = $messageTransformer;
        $this->conversationTransformer = $conversationTransformer;
        $this->messageCreateHandler = $messageCreateHandler;
        $this->messageEventsHandler = $messageEventsHandler;
        $this->conversationRepository = $conversationRepository;
    }

    public function makeResponse($request)
    {
        $conversation = $this->getConversation($request);

        $messageResponse = $this->messageTransformer->transform(
            $this->messageCreateHandler->handle($conversation, $request),
            $request['user_id']
        );

        $transformedConversation = $this->conversationTransformer->transform($conversation, $request['user_id']);

        $transformedConversation['messageCount'] = count($conversation->messages);

        $this->messageEventsHandler->broadcastEvents($transformedConversation, $messageResponse);

        return response()->json($messageResponse);
    }

    protected function getConversation($request)
    {
        return $this->conversationRepository->findById($request['conversationId']);
    }
}
