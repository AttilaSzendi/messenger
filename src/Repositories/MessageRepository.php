<?php

namespace Stilldesign\Messenger\Repositories;

use Stilldesign\Messenger\Contracts\MessageRepositoryInterface;
use Stilldesign\Messenger\Models\Message;

class MessageRepository implements MessageRepositoryInterface
{
    protected $model;

    public function __construct(Message $model)
    {
        $this->model = $model;
    }

    public function delete($messageId)
    {
        return $this->model->destroy($messageId);
    }
}
