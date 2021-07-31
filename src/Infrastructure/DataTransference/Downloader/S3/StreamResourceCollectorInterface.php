<?php

namespace App\Infrastructure\DataTransference\Downloader\S3;

interface StreamResourceCollectorInterface
{
    public function streamCollect(string $bucketName, ResourceObject $resourceObjects);

    public function checkForObjectExistence(): void;
}
