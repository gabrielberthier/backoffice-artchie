<?php

namespace App\Infrastructure\Downloader\S3;

use ZipStream\Option\Archive as ArchiveOptions;
use ZipStream\ZipStream;

class S3StreamObjectsZipDownloader
{
    protected $opt;
    protected $stream;

    public function __construct(private StreamResourceCollectorInterface $streamResourceCollector)
    {
        // https://github.com/maennchen/ZipStream-PHP/wiki/Available-options
        $this->opt = new ArchiveOptions();
        $this->opt->setContentType('application/zip');
        $this->opt->setSendHttpHeaders(false);

        $this->stream = fopen('php://memory', 'r+');
        $this->opt->setOutputStream($this->stream);
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

        foreach ($resourceObjects as $obj) {
            $resource = $this->streamResourceCollector->streamCollect($bucketName, $obj);
            if (!is_resource($resource)) {
                continue;
            }
            $objectName = $obj->getName() ?? basename($obj->getPath());
            $zip->addFileFromStream($objectName, $resource);
        }

        $zip->finish();

        rewind($this->stream);

        return $this->stream;
    }
}
