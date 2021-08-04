<?php

declare(strict_types=1);

namespace App\Presentation\Actions\Markers;

use App\Data\Protocols\Markers\Store\MarkerServiceStoreInterface;
use App\Infrastructure\DataTransference\Utils\FileNameConverter;
use App\Presentation\Actions\Protocols\Action;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\UploadedFileInterface;
use S3DataTransfer\Interfaces\Upload\UploadCollectorInterface;
use S3DataTransfer\Objects\UploadableObject;
use Slim\Psr7\Stream;

class UploadMarkerAction extends Action
{
    public function __construct(
        private MarkerServiceStoreInterface $markerStore,
        private UploadCollectorInterface $uploader
    ) {
    }

    public function action(): Response
    {
        $id = (int) $this->resolveArg('id');
        $bucket = 'artchier-markers';
        $this->uploader->uploadObjects($bucket);

        /**
         * @var UploadedFileInterface[]
         */
        $files = $this->request->getUploadedFiles();
        /**
         * @var UploadableObjectInterface[]
         */
        $objects = [];

        foreach ($files as $file) {
            $objects[] = new UploadableObject(FileNameConverter::convertFileName($file), new Stream($file->getStream()));
        }
        $result = $this->uploader->uploadObjects($bucket, ...$objects);

        return $this->respondWithData($result);
    }
}
