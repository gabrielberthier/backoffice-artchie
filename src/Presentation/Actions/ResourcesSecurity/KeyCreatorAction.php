<?php

declare(strict_types=1);

namespace App\Presentation\Actions\ResourcesSecurity;

use App\Data\Protocols\AsymCrypto\SignerInterface;
use App\Presentation\Actions\Protocols\Action;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Ramsey\Uuid\Uuid;
use Respect\Validation\Validator;

class KeyCreatorAction extends Action
{
    public function __construct(
        private SignerInterface $signerService
    ) {
    }

    public function action(Request $request): Response
    {
        $parsedBody = json_decode((string) $request->getBody());

        $uuid = Uuid::fromString($parsedBody->uuid);

        $publicKey = $this->signerService->sign($uuid);

        return $this->respondWithData(['token' => $publicKey]);
    }

    public function rules(Request $request)
    {
        return [
            'uuid' => Validator::uuid(),
        ];
    }
}