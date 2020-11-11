<?php

namespace Stilldesign\Messenger\Utils\FileExtensions;

use Stilldesign\Messenger\Contracts\FileExtensionManagerInterface;

abstract class AbstractExtensionManager implements FileExtensionManagerInterface
{
    protected const CONFIG_KEY = null;

    public function getAllowedMimes(): array
    {
        return config(static::CONFIG_KEY);
    }

    public function forRequestRule(): string
    {
        return implode(',', array_keys($this->getAllowedMimes()));
    }
}
