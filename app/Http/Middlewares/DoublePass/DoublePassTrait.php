<?php

namespace Core\Http\Middlewares\DoublePass;


use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

trait DoublePassTrait
{
    /**
     * Execute as PSR-7 double pass middleware.
     */
    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response,
        callable $next
    ): ResponseInterface {
        return $this->process($request, new CallableHandler($next, $response));
    }
}