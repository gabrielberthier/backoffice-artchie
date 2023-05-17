<?php

namespace App\Presentation\Actions\Protocols\ActionTraits;

use Slim\Exception\HttpBadRequestException;
use Psr\Http\Message\ServerRequestInterface as Request;

trait ParseInputTrait
{
    /**
     * @throws HttpBadRequestException
     *
     * @return array|object
     */
    protected function getFormData(Request $request)
    {
        $input = json_decode(file_get_contents('php://input'));

        if (JSON_ERROR_NONE !== json_last_error()) {
            throw new HttpBadRequestException($request, 'Malformed JSON input.');
        }

        return $input;
    }
}
