<?php

namespace App\Infrastructure\Downloader\S3;

interface StreamResourceCollectorInterface
{
    public function streamCollect(string $bucketName, ResourceObject $resourceObjects);

    public function checkForObjectExistence(): void;
}
