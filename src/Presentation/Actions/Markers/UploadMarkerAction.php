<?php

declare(strict_types=1);

namespace App\Presentation\Actions\Markers;

use App\Presentation\Actions\Protocols\Action;
use Aws\S3\S3Client;
use GuzzleHttp\Promise\Utils;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\UploadedFileInterface;

class UploadMarkerAction extends Action
{
    public function __construct(
        private S3Client $s3Client
    ) {
    }

    public function action(): Response
    {
        $id = (int) $this->resolveArg('id');

        /**
         * @var UploadedFileInterface[]
         */
        $files = $this->request->getUploadedFiles();

        $promises = [];
        foreach ($files as $file) {
        }
        // Construct a promise that will be fulfilled when all
        // of its constituent promises are fulfilled
        $allPromise = Utils::all($promises);
        $result = $allPromise->wait();

        return $this->respondWithData($result);
    }

    public function getPromiseFromFile(UploadedFileInterface $file)
    {
        if (is_countable($file)) {
        }
        $basename = bin2hex(random_bytes(8));
        $extension = pathinfo($file->getClientFilename(), PATHINFO_EXTENSION);
        $filename = sprintf('%s.%0.8s', $basename, $extension);
        $promises[] = $this->s3Client->putObjectAsync([
            'Bucket' => '',
            'Key' => $filename,
            'SourceFile' => $file->getClientFilename(),
        ]);
    }
}
