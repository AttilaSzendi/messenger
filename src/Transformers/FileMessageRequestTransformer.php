<?php

namespace Stilldesign\Messenger\Transformers;

use Illuminate\Contracts\Auth\Guard;
use Stilldesign\Messenger\Contracts\FileUploadHandlerInterface;

class FileMessageRequestTransformer
{
    protected $guard;
    protected $fileUploadHandler;

    public function __construct(Guard $guard, FileUploadHandlerInterface $fileUploadHandler)
    {
        $this->guard = $guard;
        $this->fileUploadHandler = $fileUploadHandler;
    }

    public function baseTransform($request, $path)
    {
        $request['user_id'] = $this->guard->user()->id;
        $request['body'] = $path;
        $request['ip_address'] = $request->ip();
        $request['attachment_original_name'] = $request->file('file')->getClientOriginalName();

        return $request->input();
    }

    public function getPath($request, $type)
    {
        return $this->fileUploadHandler
            ->setFolder("messenger/{$request->input('conversationId')}/{$type}")
            ->upload($request->file('file'));
    }
}
