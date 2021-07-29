<?php

namespace App\Infrastructure\Downloader\S3;

use App\Infrastructure\Downloader\S3\Exceptions\InvalidParamsException;
use Aws\S3\Exception\S3Exception;
use Aws\S3\S3Client;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;

class StreamResourceCollector implements StreamResourceCollectorInterface
{
    private bool $checkObjectExist = false;

    public function __construct(private ClientInterface $clientInterface, private S3Client $s3client)
    {
        $this->s3client->registerStreamWrapper();
    }

    public function checkForObjectExistence(): void
    {
        $this->checkObjectExist = true;
    }

    public function streamCollect(string $bucketName, ResourceObject $resourceObject)
    {
        $this->prepareBucketHead($bucketName);

        return $this->getStreamsFromResourcesArray($bucketName, $resourceObject);
    }

    private function getStreamsFromResourcesArray(string $bucket, ResourceObject $resourceObject)
    {
        $this->validateResourceObjects($bucket, $resourceObject, $this->checkObjectExist);

        $context = stream_context_create([
            's3' => ['seekable' => true],
        ]);

        $request = $this->mountRequestS3Request($bucket, $resourceObject->getPath());

        $tmpfile = tempnam(sys_get_temp_dir(), crc32(time()));

        $request->getBody()->write($tmpfile);

        $this->clientInterface->sendRequest($request);

        if ($stream = fopen($tmpfile, 'r', false, $context)) {
            return $stream;
        }

        return null;
    }

    private function validateResourceObjects(string $bucket, ResourceObject $obj, $checkObjectExist)
    {
        if (!($obj instanceof ResourceObject)) {
            throw new InvalidParamsException('Resources to download must be composed of ResourceObject instances only.');
        }

        if ($checkObjectExist) {
            S3FileVerifier::verifyFileExistence($bucket, $obj->getPath());
        }
    }

    private function mountRequestS3Request(string $bucket, string $path): RequestInterface
    {
        return $this->s3client->createPresignedRequest(
            $this->s3client->getCommand('GetObject', [
                'Key' => $path,
                'Bucket' => $bucket,
            ]),
            '+1 day'
        );
    }

    // @docs http://docs.aws.amazon.com/aws-sdk-php/v3/api/api-s3-2006-03-01.html#headbucket
    private function prepareBucketHead(string $bucketName)
    {
        if (empty($bucketName)) {
            throw new InvalidParamsException('The parameter `bucket` is required and cannot be an empty string.');
        }

        try {
            $this->s3client->headBucket([
                'Bucket' => $bucketName,
            ]);
        } catch (S3Exception) {
            throw new InvalidParamsException("Bucket `{$bucketName}` does not exists and/or you have not permission to access it.");
        }
    }
}
