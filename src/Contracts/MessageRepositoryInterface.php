<?php

namespace Stilldesign\Messenger\Contracts;

interface MessageRepositoryInterface
{
    public function delete($messageId);
}
