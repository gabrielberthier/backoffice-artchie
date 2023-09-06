<?php
namespace Core\Http\Interfaces;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

interface ActionInterface
{
    function action(Request $request): Response;
}