<?php

namespace App\Infrastructure\DataTransference\Utils;

use Psr\Http\Message\UploadedFileInterface;

class FileMover
{
    public static function moveUploadedFile(string $directory, UploadedFileInterface $uploadedFile)
    {
        $fileName = FileNameConverter::convertFileName($uploadedFile);

        $uploadedFile->moveTo($directory.DIRECTORY_SEPARATOR.$fileName);

        return $fileName;
    }
}
