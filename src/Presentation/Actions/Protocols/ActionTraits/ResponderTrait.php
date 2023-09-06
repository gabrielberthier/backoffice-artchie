<?php

namespace App\Presentation\Actions\Protocols\ActionTraits;

use App\Presentation\Actions\Protocols\ActionPayload;
use Psr\Http\Message\ResponseInterface as Response;

trait ResponderTrait
{
    /**
     * @param null|array|object $data
     */
    protected function respondWithData($data = null): Response
    {
        $payload = new ActionPayload(200, $data);

        return $this->respond($payload);
    }

    protected function respond(ActionPayload $payload): Response
    {
        $json = json_encode($payload);
        $this->response->getBody()->write($json);

        return $this->response->withHeader('Content-Type', 'application/json');
    }
}