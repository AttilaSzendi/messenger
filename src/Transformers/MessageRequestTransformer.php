<?php

namespace Stilldesign\Messenger\Transformers;

use Illuminate\Contracts\Auth\Guard;
use Stilldesign\Messenger\Contracts\MessageRequestTransformerInterface;
use Stilldesign\Messenger\Http\Requests\MessageRequest;

class MessageRequestTransformer implements MessageRequestTransformerInterface
{
    protected $guard;

    public function __construct(Guard $guard)
    {
        $this->guard = $guard;
    }

    public function transform(MessageRequest $request): array
    {
        $request['ip_address'] = $request->ip();
        $request['user_id'] = $this->guard->user()->id;

        return $request->input();
    }
}
