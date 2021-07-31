<?php

namespace App\Infrastructure\DataTransference\Downloader\S3;

class S3Credentials
{
    public function __construct(
        private string $key,
        private string $secret
    ) {
    }

    public function retrieveCredentials(): array
    {
        return [
            'key' => $this->key,
            'secret' => $this->secret,
        ];
    }
}
