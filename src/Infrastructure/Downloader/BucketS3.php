<?php

namespace App\Infrastructure\Downloader;

use App\Infrastructure\Downloader\Exceptions\InvalidParamsException;
use Aws\S3\Exception\S3Exception;
use Aws\S3\S3Client;

class BucketS3
{
    public function __construct(private string $bucketName)
    {
        if (empty($bucketName)) {
            throw new InvalidParamsException('The parameter `bucket` is required and cannot be an empty string.');
        }
    }

    public function getBucketName(): string
    {
        return $this->bucketName;
    }

    // @docs http://docs.aws.amazon.com/aws-sdk-php/v3/api/api-s3-2006-03-01.html#headbucket
    public function prepareBucketHead(S3Client $s3Client)
    {
        try {
            $s3Client->headBucket([
                'Bucket' => $this->bucketName,
            ]);
        } catch (S3Exception) {
            throw new InvalidParamsException("Bucket `{$this->bucketName}` does not exists and/or you have not permission to access it.");
        }
    }
}
