<?php

namespace App\Presentation\Helpers\Validation\Exceptions;

use Respect\Validation\Exceptions\ValidationException;

class FileNotPermittedException extends ValidationException
{
    protected $defaultTemplates = [
        self::MODE_DEFAULT => [
            self::STANDARD => 'File provided is not in the correct format.',
        ],
        self::MODE_NEGATIVE => [
            self::STANDARD => 'Validation message if the negative of Something is called and fails validation.',
        ],
    ];
}
