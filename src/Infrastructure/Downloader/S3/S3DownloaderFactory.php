<?php

namespace App\Infrastructure\Downloader\S3;

use GuzzleHttp\Client as HttpClient;

class S3DownloaderFactory
{
    public static function create()
    {
        $httpClientAdapter = new HttpClientAdapter(new HttpClient());
        $s3Key = $_ENV['S3KEY'];
        $s3Secret = $_ENV['S3SECRET'];
        $s3Region = $_ENV['S3REGION'];
        $s3Version = $_ENV['S3VERSION'];

        $s3Options = new S3Options(new S3Credentials($s3Key, $s3Secret), $s3Region, $s3Version);

        // //Listing all S3 Bucket
        // $buckets = $s3Options->createS3Client()->listBuckets();

        // foreach ($buckets['Buckets'] as $bucket) {
        //     echo $bucket['Name']."\n";
        // }

        // exit;

        return new StreamResourceCollector($httpClientAdapter, $s3Options->createS3Client());
    }
}
