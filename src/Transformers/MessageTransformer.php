<?php

namespace Stilldesign\Messenger\Transformers;

use Illuminate\Contracts\Filesystem\Cloud;
use Stilldesign\Messenger\Contracts\MessageTransformerInterface;
use Stilldesign\Messenger\Models\Message;

class MessageTransformer implements MessageTransformerInterface
{
    protected $cloud;
    protected $isFile;

    /**
     * MessageTransformer constructor.
     * @param $cloud
     */
    public function __construct(Cloud $cloud)
    {
        $this->cloud = $cloud;
    }

    public function transform(Message $message, $loggedInUserId): array
    {
        $this->isFile($message);

        return [
            'id' => $message->id,
            'createdAt' => $message->created_at->toDateTimeString(),
            'body' => $this->getBody($message),
            'conversationId' => (int)$message->conversation_id,
            'isImage' => !!$message->is_image,
            'isDocument' => !!$message->is_document,
            'attachmentOriginalName' => $message->attachment_original_name,
            'ipAddress' => $message->ip_address,
            'sender' => [
                'name' => $message->sender->name,
                'isMe' => $message->sender->id == $loggedInUserId
            ],
        ];
    }

    protected function getBody($message)
    {
        return $this->isFile ? $this->cloud->url($message->body) : $message->body;
    }

    public function setIsFile(bool $value)
    {
        $this->isFile = $value;
    }

    protected function isFile($message)
    {
        $this->isFile = $message->is_image || $message->is_document;
    }
}
