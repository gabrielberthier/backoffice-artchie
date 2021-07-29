<?php

namespace App\Infrastructure\Downloader\S3;

use ZipStream\Option\Archive as ArchiveOptions;
use ZipStream\ZipStream;

class S3StreamObjectsZipDownloader
{
    protected $opt;

    public function __construct(private StreamResourceCollectorInterface $streamResourceCollector)
    {
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
        string $bucketName,
        array $resourceObjects,
        string $zipname = 'resources.zip',
        bool $checkObjectExist = false
    ) {
        $zip = new ZipStream($zipname, $this->opt);

        if ($checkObjectExist) {
            $this->streamResourceCollector->checkForObjectExistence();
        }

        $streams = $this->streamResourceCollector->streamCollect($bucketName, ...$resourceObjects);

        foreach ($streams as $key => $value) {
            $zip->addFileFromStream($key, $value);
        }

        $zip->finish();
    }
}
