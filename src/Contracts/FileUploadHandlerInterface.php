<?php

namespace Stilldesign\Messenger\Contracts;

use Illuminate\Http\UploadedFile;

interface FileUploadHandlerInterface
{
    public function upload(UploadedFile $file): string;

    public function setFolder(string $folder): FileUploadHandlerInterface;

    public function getFolder();
}
