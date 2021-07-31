<?php

namespace App\Infrastructure\DataTransference\Downloader\S3;

use App\Infrastructure\DataTransference\Downloader\S3\Exceptions\InvalidParamsException;

class S3FileVerifier
{
    /**
     * Verify if object exists in S3 buckets.
     *
     * @throws InvalidParamsException
     */
    public static function verifyFileExistence(string $bucket, string $object): void
    {
        // https://docs.aws.amazon.com/aws-sdk-php/v3/guide/service/s3-stream-wrapper.html#other-object-functions
        $objectDir = 's3://'.$bucket.'/'.$object;

        if (!file_exists($objectDir)) {
            throw new InvalidParamsException('The object `$object["path"]` you have requested does not exist.');
        }
        if (!is_file($objectDir)) {
            throw new InvalidParamsException('The action cannot be completed because `$object["path"]` is not an object.');
        }
    }
}
