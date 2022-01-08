<?php

declare(strict_types=1);

namespace App\Presentation\Actions\Home;

use App\Presentation\Actions\Protocols\Action;
use Psr\Http\Message\ResponseInterface as Response;
use stdClass;

class HomeController extends Action
{
    public function action(): Response
    {
        $data = new stdClass();
        $data->message = file_get_contents(__DIR__.'/welcome.txt');

        return $this->respondWithData($data);
    }
}
