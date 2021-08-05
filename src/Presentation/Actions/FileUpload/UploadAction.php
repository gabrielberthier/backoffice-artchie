<?php

declare(strict_types=1);

namespace App\Presentation\Actions\FileUpload;

use App\Infrastructure\DataTransference\Utils\FileNameConverter;
use App\Presentation\Actions\Protocols\Action;
use App\Presentation\Errors\UploadError;
use DateTime;
use GuzzleHttp\Psr7\MimeType;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\UploadedFileInterface;
use S3DataTransfer\Interfaces\Objects\UploadableObjectInterface;
use S3DataTransfer\Interfaces\Upload\UploadCollectorInterface;
use S3DataTransfer\Objects\UploadableObject;

class UploadAction extends Action
{
    public function __construct(
        private UploadCollectorInterface $uploader,
    ) {
    }

    public function action(): Response
    {
        $bucket = 'artchier-markers';
        /**
         * @var UploadedFileInterface[]
         */
        $files = $this->request->getUploadedFiles();
        /**
         * @var UploadableObjectInterface[]
         */
        $objects = [];

        foreach ($files as $file) {
            $objects[] = $this->createUploadableObject($file);
        }

        $results = $this->uploader->uploadObjects($bucket, ...$objects);

        $returnValues = array_map(fn ($result, UploadableObjectInterface $object) => [
            'URL' => $result['ObjectURL'],
            'fileName' => $object->key(),
            'created_at' => new DateTime(),
            'mimeType' => MimeType::fromFilename($object->key()),
        ], $results, $objects);

        return $this->respondWithData($returnValues);
    }

    private function createUploadableObject(UploadedFileInterface $uploadedFile): UploadableObjectInterface
    {
        if (!$uploadedFile->getError()) {
            $fileName = FileNameConverter::convertFileName($uploadedFile);

            $stream = $uploadedFile->getStream();

            return new UploadableObject($fileName, $stream);
        }

        throw new UploadError($this->request, $uploadedFile->getClientFilename());
    }

    /*
v::objectType()->attribute('file', v::oneOf(
    v::mimetype('application/pdf'),
    v::mimetype('image/png')
))->validate($uploadedFile);
    */
}
