<?php

namespace App\Infrastructure\Downloader;

use Aws\S3\S3Client;

class S3Options
{
    public function __construct(
        private S3Credentials $s3Credentials,
        private string $region,
        private string $version = 'latest|version'
    ) {
    }

    public function createS3Client(): S3Client
    {
        return new S3Client($this->createConfiguration());
    }

    private function createConfiguration(): array
    {
        return [
            'credentials' => $this->s3Credentials->retrieveCredentials(),
            'region' => $this->region,
            'version' => $this->version,
        ];
    }
}
