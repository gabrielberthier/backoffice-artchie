<?php

namespace Core\Http\Middlewares;


use App\Domain\Repositories\MuseumRepository;
use App\Domain\Repositories\SignatureTokenRetrieverInterface;
use App\Presentation\Middleware\AsymmetricValidator;
use Psr\Container\ContainerInterface;

class AsymetricValidatorFactory
{
    public static function createMiddleware(ContainerInterface $container): AsymmetricValidator
    {
        $repository = $container->get(MuseumRepository::class);
        $tokenRetriever = $container->get(SignatureTokenRetrieverInterface::class);

        return new AsymmetricValidator($repository, $tokenRetriever);
    }
}