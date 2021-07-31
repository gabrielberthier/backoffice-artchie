<?php

namespace App\Infrastructure\DataTransference\Utils;

use Psr\Http\Message\UploadedFileInterface;

class FileMover
{
    public static function moveUploadedFile(string $directory, UploadedFileInterface $uploadedFile)
    {
        $extension = pathinfo($uploadedFile->getClientFilename(), PATHINFO_EXTENSION);

        // see http://php.net/manual/en/function.random-bytes.php
        $basename = bin2hex(random_bytes(8));
        $filename = sprintf('%s.%0.8s', $basename, $extension);

        $uploadedFile->moveTo($directory.DIRECTORY_SEPARATOR.$filename);

        return $filename;
    }
}
