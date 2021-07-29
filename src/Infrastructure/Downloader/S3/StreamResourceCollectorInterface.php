<?php

namespace App\Infrastructure\Downloader\S3;

interface StreamResourceCollectorInterface
{
    public function streamCollect(string $bucketName, ResourceObject ...$resourceObjects): array;

    public function checkForObjectExistence(): void;
}
