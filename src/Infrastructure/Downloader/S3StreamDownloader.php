<?php

namespace WGenial\S3ObjectsStreamZip;

use App\Infrastructure\Downloader\BucketS3;
use App\Infrastructure\Downloader\Exceptions\InvalidParamsException;
use App\Infrastructure\Downloader\ResourceObject;
use Aws\S3\S3Client;
  use GuzzleHttp\Client as HttpClient;
  use ZipStream\Option\Archive as ArchiveOptions;
  use ZipStream\ZipStream;

  class S3ObjectsStreamZip
  {
      protected $opt;

      public function __construct(private S3Client $client, private BucketS3 $bucket)
      {
          $this->client->registerStreamWrapper();
          // https://github.com/maennchen/ZipStream-PHP/wiki/Available-options
          $this->opt = new ArchiveOptions();
          $this->opt->setContentType('application/zip');
      }

      /**
       * Returns a zip with all objects downloaded from S3.
       *
       * @param ResourceObject[] $resourceObjects
       */
      public function zipObjects(
          array $resourceObjects,
          string $zipname = 'resources.zip',
          bool $checkObjectExist = false
      ) {
          $this->validateResourceObjects($resourceObjects, $checkObjectExist);

          $this->bucket->prepareBucketHead($this->client);

          $zip = new ZipStream($zipname, $this->opt);
          $httpClient = new HttpClient();

          foreach ($resourceObjects as $object) {
              $objectName = $object->getName() ?? basename($object->getPath());

              $context = stream_context_create([
                  's3' => ['seekable' => true],
              ]);

              $request = $this->client->createPresignedRequest(
                  $this->client->getCommand('GetObject', [
                      'Key' => $object->getPath(),
                      'Bucket' => $this->bucket->getBucketName(),
                  ]),
                  '+1 day'
              );

              $tmpfile = tempnam(sys_get_temp_dir(), crc32(time()));

              $httpClient->request('GET', (string) $request->getUri(), [
                  'synchronous' => true,
                  'sink' => fopen($tmpfile, 'w+'),
              ]);

              if ($stream = fopen($tmpfile, 'r', false, $context)) {
                  $zip->addFileFromStream($objectName, $stream);
              }
          }

          $zip->finish();
      }

      protected function doesObjectExist(string $bucket, ResourceObject $object)
      {
          // https://docs.aws.amazon.com/aws-sdk-php/v3/guide/service/s3-stream-wrapper.html#other-object-functions
          $objectDir = 's3://'.$bucket.'/'.$object->getPath();

          if (!file_exists($objectDir)) {
              throw new InvalidParamsException('The object `$object["path"]` you have requested does not exist.');
          }
          if (!is_file($objectDir)) {
              throw new InvalidParamsException('The action cannot be completed because `$object["path"]` is not an object.');
          }
      }

      private function validateResourceObjects(array $resourceObjects, $checkObjectExist)
      {
          if (empty($resourceObjects)) {
              throw new InvalidParamsException('The parameter `objects` is required.');
          }

          foreach ($resourceObjects as $obj) {
              if (!($obj instanceof ResourceObject)) {
                  throw new InvalidParamsException('Resources to download must be composed of ResourceObject instances only.');
              }

              if ($checkObjectExist) {
                  $this->doesObjectExist($this->bucket->getBucketName(), $obj);
              }
          }
      }
  }
