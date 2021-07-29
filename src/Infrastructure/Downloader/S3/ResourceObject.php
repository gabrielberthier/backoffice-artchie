<?php

namespace App\Infrastructure\Downloader\S3;

use App\Infrastructure\Downloader\S3\Exceptions\InvalidParamsException;

class ResourceObject
{
    public function __construct(private string $path, private ?string $name = '')
    {
        if (empty($path)) {
            throw new InvalidParamsException('The `path` cannot be an empty string.');
        }
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function getName(): ?string
    {
        return $this->name;
    }
}
