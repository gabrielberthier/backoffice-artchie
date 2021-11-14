<?php

namespace App\Presentation\Actions\Auth;

use App\Presentation\Actions\Protocols\Action;
use App\Presentation\Factories\RefreshTokenHandlerFactory;
use Psr\Http\Message\ResponseInterface;

class RefreshTokenAction extends Action
{
    public function __construct(private RefreshTokenHandlerFactory $refreshTokenHandlerFactory)
    {
    }

    public function action(): ResponseInterface
    {
        $refreshTokenHandler = $this->refreshTokenHandlerFactory->create($this->request);

        return $refreshTokenHandler($this->response, []);
    }
}
