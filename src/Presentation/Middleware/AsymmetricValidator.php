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
use Respect\Validation\Validator as v;
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

        list($uuid, $token) = $this->filterHeader($request, $headers);

        $museum = $this->museumRepository->findByUUID($uuid);

        if ($museum) {
            $signature = json_encode([
                'uuid' => $museum->getUuid()->toString(),
                'museum_name' => $museum->getName(),
            ]);

            $dbToken = $this->tokenRepository->findFromMuseum($museum);
            $raw_signature = base64_decode($dbToken->getSignature());
            $result = openssl_verify($signature, $raw_signature, $token, OPENSSL_ALGO_SHA256);

            if (1 === $result) {
                $request->withAttribute('museum_id', $museum->getId());

                return $handler->handle($request);
            }
        }

        throw new HttpForbiddenException($request, 'Access forbidden');
    }

    private function filterHeader(Request $request, array $headers)
    {
        if (count($headers)) {
            list($headerValue) = $headers;

            if (strpos($headerValue, '.')) {
                list($uuid, $token) = explode('.', $headerValue);
                $uuidDecoded = base64_decode($uuid, true);
                $tokenDecoded = base64_decode($token, true);

                if ($tokenDecoded && $uuidDecoded && v::uuid()->validate($uuidDecoded)) {
                    return [$uuidDecoded, $tokenDecoded];
                }
            }
        }

        throw new HttpBadRequestException($request, 'A valid token must be used in header');
    }
}
