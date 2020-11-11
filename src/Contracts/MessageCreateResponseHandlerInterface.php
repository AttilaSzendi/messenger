<?php

namespace Stilldesign\Messenger\Contracts;

interface MessageCreateResponseHandlerInterface
{
    public function makeResponse($request);
}
