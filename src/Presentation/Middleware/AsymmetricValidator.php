<?php

declare(strict_types=1);

namespace App\Presentation\Middleware;

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
        private SignatureTokenRetrieverInterface $tokenRepository
    ) {
    }

    public function process(Request $request, RequestHandler $handler): Response
    {
        $headers = $request->getHeader('app-client-token');

        list($uuid, $token) = $this->filterHeader($request, $headers);

        $museum = $this->museumRepository->findByUUID($uuid);

        if ($museum instanceof \App\Domain\Models\Museum) {
            $signature = json_encode([
                'uuid' => $museum->uuid->toString(),
                'museum_name' => $museum->name,
            ]);

            $dbToken = $this->tokenRepository->findFromMuseum($museum);
            $raw_signature = base64_decode($dbToken->signature);
            $result = openssl_verify($signature, $raw_signature, $token, OPENSSL_ALGO_SHA256);

            if ($result > 0) {
                return $handler->handle(
                    $request
                        ->withAttribute(
                            'museum_id',
                            $museum->id
                        )
                );
            }
        }

        throw new HttpForbiddenException($request, 'Access forbidden');
    }

    private function filterHeader(Request $request, array $headers)
    {
        if ($headers !== []) {
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
