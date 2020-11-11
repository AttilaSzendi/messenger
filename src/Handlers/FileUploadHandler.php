<?php

namespace Stilldesign\Messenger\Handlers;

use Illuminate\Contracts\Filesystem\Cloud;
use Illuminate\Http\UploadedFile;
use Stilldesign\Messenger\Contracts\FileUploadHandlerInterface;

class FileUploadHandler implements FileUploadHandlerInterface
{
    protected $folder;
    protected $fileSystem;

    /**
     * FileUploadHandler constructor.
     * @param $fileSystem
     */
    public function __construct(Cloud $fileSystem)
    {
        $this->fileSystem = $fileSystem;
    }

    /**
     * @param UploadedFile $file
     * @return string
     * @throws \InvalidArgumentException
     */
    public function upload(UploadedFile $file): string
    {
        if ($this->getFolder() === null) {
            throw new \InvalidArgumentException('The folder name is not valid');
        }

        return $this->fileSystem->put($this->getFolder(), $file);
    }

    /**
     * @param string $folder
     * @return FileUploadHandlerInterface
     */
    public function setFolder(string $folder): FileUploadHandlerInterface
    {
        $this->folder = $folder;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getFolder()
    {
        return $this->folder;
    }
}
