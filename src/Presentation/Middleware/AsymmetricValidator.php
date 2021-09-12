<?php

declare(strict_types=1);

namespace App\Presentation\Middleware;

use App\Data\Protocols\AsymCrypto\AsymmetricVerifier;
use App\Domain\Repositories\MuseumRepository;
use App\Domain\Repositories\SignatureTokenRetrieverInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface as Middleware;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Exception\HttpBadRequestException;
use Slim\Exception\HttpForbiddenException;

class AsymmetricValidator implements Middleware
{
    public function __construct(
        private MuseumRepository $museumRepository,
        private AsymmetricVerifier $asymmetricVerifier,
        private SignatureTokenRetrieverInterface $tokenRepository
    ) {
    }

    public function process(Request $request, RequestHandler $handler): Response
    {
        $headers = $request->getHeader('app-client-token');
        if (count($headers)) {
            list($headerValue) = $headers;
            list($uuid, $token) = explode('.', $headerValue);
            $uuidDecoded = base64_decode($uuid, true);
            $museum = $this->museumRepository->findByUUID($uuidDecoded);
            if ($museum) {
                $tokenDecoded = base64_decode($token, true);
                $signature = json_encode([
                    'uuid' => $museum->getUuid()->toString(),
                    'museum_name' => $museum->getName(),
                ]);

                $dbToken = $this->tokenRepository->findFromMuseum($museum);
                if (openssl_verify($signature, $dbToken->getSignature(), $tokenDecoded, OPENSSL_ALGO_SHA256)) {
                    $request->withAttribute('museum_id', $museum->getId());

                    return $handler->handle($request);
                }

                throw new HttpForbiddenException($request);
            }
        }

        throw new HttpBadRequestException($request);
    }
}
