<?php

namespace App\Presentation\Actions\Auth;

use App\Presentation\Actions\Protocols\Action;
use App\Presentation\Factories\RefreshTokenHandlerFactory;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class RefreshTokenAction extends Action
{
    public function __construct(private RefreshTokenHandlerFactory $refreshTokenHandlerFactory)
    {
    }

    public function action(Request $request): Response
    {
        $refreshTokenHandler = $this->refreshTokenHandlerFactory->create($request);

        return $refreshTokenHandler($this->response);
    }
}