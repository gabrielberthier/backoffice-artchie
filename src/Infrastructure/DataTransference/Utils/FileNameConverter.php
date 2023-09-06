<?php

namespace App\Infrastructure\DataTransference\Utils;

use Psr\Http\Message\UploadedFileInterface;

class FileNameConverter
{
    public static function convertFileName(UploadedFileInterface $uploadedFile): string
    {
        $extension = pathinfo($uploadedFile->getClientFilename(), PATHINFO_EXTENSION);

        // see http://php.net/manual/en/function.random-bytes.php
        $basename = bin2hex(random_bytes(8));

        return sprintf('%s.%0.8s', $basename, $extension);
    }
}
