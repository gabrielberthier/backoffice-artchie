<?php

namespace Core\HTTP\Routing\RouteMiddlewares;

use App\Data\Protocols\AsymCrypto\AsymmetricVerifier;
use App\Domain\Repositories\MuseumRepository;
use App\Domain\Repositories\SignatureTokenRetrieverInterface;
use App\Presentation\Middleware\AsymmetricValidator;
use Psr\Container\ContainerInterface;

class AsymetricValidatorFactory
{
    public static function createMiddleware(ContainerInterface $container): AsymmetricValidator
    {
        $repository = $container->get(MuseumRepository::class);
        $verifier = $container->get(AsymmetricVerifier::class);
        $tokenRepo = $container->get(SignatureTokenRetrieverInterface::class);

        return new AsymmetricValidator($repository, $verifier, $tokenRepo);
    }
}
