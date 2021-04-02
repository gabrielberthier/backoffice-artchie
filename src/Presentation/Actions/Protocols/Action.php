<?php

declare(strict_types=1);

namespace App\Presentation\Actions\Protocols;

use App\Domain\Exceptions\Protocols\DomainRecordNotFoundException;
use App\Presentation\Actions\Protocols\ActionTraits\ParseInputTrait;
use App\Presentation\Actions\Protocols\ActionTraits\ResponderTrait;
use App\Presentation\Actions\Protocols\ActionTraits\ValidationTrait;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Log\LoggerInterface;
use Slim\Exception\HttpBadRequestException;
use Slim\Exception\HttpNotFoundException;

abstract class Action
{
    use ValidationTrait;
    use ParseInputTrait;
    use ResponderTrait;

    protected Request $request;

    protected Response $response;

    protected array $args;

    /**
     * @param LoggerInterface $logger
     */
    public function __construct(protected LoggerInterface $logger)
    {
    }

    /**
     * @throws HttpNotFoundException
     * @throws HttpBadRequestException
     */
    public function __invoke(Request $request, Response $response, array $args): Response
    {
        $this->request = $request;
        $this->response = $response;
        $this->args = $args;

        try {
            $parsedBody = $request->getParsedBody();

            $this->validate($parsedBody);

            return $this->action();
        } catch (DomainRecordNotFoundException $e) {
            throw new HttpNotFoundException($this->request, $e->getMessage());
        }
    }

    /**
     * @throws DomainRecordNotFoundException
     * @throws HttpBadRequestException
     */
    abstract protected function action(): Response;

    /**
     * @throws HttpBadRequestException
     *
     * @return mixed
     */
    protected function resolveArg(string $name)
    {
        if (!isset($this->args[$name])) {
            throw new HttpBadRequestException($this->request, "Could not resolve argument `{$name}`.");
        }

        return $this->args[$name];
    }
}
