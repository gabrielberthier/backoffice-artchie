<?php

namespace App\Presentation\Actions\Markers\Utils;

use App\Domain\Models\Assets\AbstractAsset;
use Aws\S3\S3Client;
use S3DataTransfer\S3\Factories\ClientProvider;

class PresignedUrlCreator
{
    private S3Client $s3Client;
    
    private string $bucket = 'artchier-markers';

    public function __construct()
    {
        $this->s3Client = ClientProvider::getS3Client(
            $_ENV['S3KEY'],
            $_ENV['S3SECRET'],
            $_ENV['S3REGION'],
            $_ENV['S3VERSION'],
        );
    }

    public function setPresignedUrl(AbstractAsset $asset): ?string
    {
        $object = $asset->getPath();

        if ($this->s3Client->doesObjectExist($this->bucket, $object)) {
            $cmd = $this->s3Client->getCommand('GetObject', [
                'Bucket' => $this->bucket,
                'Key' => $object,
            ]);

            $request = $this->s3Client->createPresignedRequest($cmd, '+20 minutes');

            // Get the actual presigned-url
            return (string) $request->getUri();
        }

        return null;
    }
}
