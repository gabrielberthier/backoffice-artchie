<?php

namespace App\Infrastructure\Downloader\S3;

use App\Infrastructure\Downloader\Exceptions\InvalidParamsException;
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

    public function streamCollect(string $bucketName, ResourceObject ...$resourceObjects): array
    {
        if (empty($resourceObjects)) {
            throw new InvalidParamsException('The parameter `objects` is required.');
        }

        $this->prepareBucketHead($bucketName);

        return $this->getStreamsFromResourcesArray(...$resourceObjects);
    }

    private function getStreamsFromResourcesArray(ResourceObject ...$resourceObjects): array
    {
        $resultingArray = [];

        foreach ($resourceObjects as $obj) {
            $this->validateResourceObjects($obj, $this->checkObjectExist);

            $objectName = $obj->getName() ?? basename($obj->getPath());

            $context = stream_context_create([
                's3' => ['seekable' => true],
            ]);

            $request = $this->mountRequestS3Request($obj->getPath());

            $tmpfile = tempnam(sys_get_temp_dir(), crc32(time()));

            $request->getBody()->write($tmpfile);

            $this->clientInterface->sendRequest($request);

            if ($stream = fopen($tmpfile, 'r', false, $context)) {
                $resultingArray[$objectName] = $stream;
            }
        }

        return $resultingArray;
    }

    private function validateResourceObjects(ResourceObject $obj, $checkObjectExist)
    {
        if (!($obj instanceof ResourceObject)) {
            throw new InvalidParamsException('Resources to download must be composed of ResourceObject instances only.');
        }

        if ($checkObjectExist) {
            S3FileVerifier::verifyFileExistence($this->bucket->getBucketName(), $obj->getPath());
        }
    }

    private function mountRequestS3Request(string $path): RequestInterface
    {
        return $this->s3client->createPresignedRequest(
            $this->s3client->getCommand('GetObject', [
                'Key' => $path,
                'Bucket' => $this->bucket->getBucketName(),
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
            $this->s3Client->headBucket([
                'Bucket' => $bucketName,
            ]);
        } catch (S3Exception) {
            throw new InvalidParamsException("Bucket `{$bucketName}` does not exists and/or you have not permission to access it.");
        }
    }
}
