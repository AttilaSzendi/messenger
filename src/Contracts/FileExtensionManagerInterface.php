<?php

namespace Stilldesign\Messenger\Contracts;

interface FileExtensionManagerInterface
{
    public function getAllowedMimes(): array;

    public function forRequestRule(): string;
}
