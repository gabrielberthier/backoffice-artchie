<?php

declare(strict_types=1);

namespace App\Presentation\Actions\Protocols;

use JsonSerializable;

class ActionPayload implements JsonSerializable
{
    public function __construct(
        private int $statusCode = 200,
        private array|object|null $data = null,
        private ?ActionError $error = null
    ) {
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    /**
     * @return array|null|object
     */
    public function getData()
    {
        return $this->data;
    }

    public function getError(): ?ActionError
    {
        return $this->error;
    }

    /**
     * @return array
     */
    public function jsonSerialize(): mixed
    {
        $payload = [
            'statusCode' => $this->statusCode,
        ];

        if ($this->data !== null) {
            $payload['data'] = $this->data;
        } elseif ($this->error instanceof \App\Presentation\Actions\Protocols\ActionError) {
            $payload['error'] = $this->error;
        }

        return $payload;
    }
}
