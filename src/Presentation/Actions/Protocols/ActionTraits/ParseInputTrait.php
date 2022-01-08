<?php

namespace App\Presentation\Actions\Protocols\ActionTraits;

use Slim\Exception\HttpBadRequestException;

trait ParseInputTrait
{
    /**
     * @throws HttpBadRequestException
     *
     * @return array|object
     */
    protected function getFormData()
    {
        $input = json_decode(file_get_contents('php://input'));

        if (JSON_ERROR_NONE !== json_last_error()) {
            throw new HttpBadRequestException($this->request, 'Malformed JSON input.');
        }

        return $input;
    }
}
