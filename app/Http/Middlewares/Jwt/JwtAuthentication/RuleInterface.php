<?php

declare(strict_types=1);

namespace Core\Http\Middlewares\Jwt\JwtAuthentication;


use Psr\Http\Message\ServerRequestInterface;

interface RuleInterface
{
    public function __invoke(ServerRequestInterface $request): bool;
}