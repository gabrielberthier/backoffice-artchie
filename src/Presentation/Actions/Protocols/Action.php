<?php

declare(strict_types=1);

namespace App\Presentation\Actions\Protocols;

use App\Domain\Exceptions\Protocols\HttpSpecializedAdapter;
use App\Presentation\Actions\Protocols\ActionTraits\ParseInputTrait;
use App\Presentation\Actions\Protocols\ActionTraits\ResponderTrait;
use App\Presentation\Actions\Protocols\ActionTraits\ValidationTrait;
use Core\Http\Interfaces\ActionInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Log\LoggerInterface;
use Slim\Exception\HttpBadRequestException;
use Slim\Exception\HttpException;

abstract class Action implements ActionInterface
{
    use ValidationTrait;
    use ParseInputTrait;
    use ResponderTrait;

    protected Request $request;

    protected Response $response;

    protected array $args;

    public function __construct(protected LoggerInterface $logger)
    {
    }

    /**
     * @throws HttpException
     */
    public function __invoke(Request $request, Response $response, array $args): Response
    {
        $this->response = $response;
        $this->args = $args;

        try {
            $this->validate($request);

            return $this->action($request);
        } catch (HttpSpecializedAdapter $httpSpecializedAdapter) {
            $adaptedError = $httpSpecializedAdapter->wire($request);

            throw $adaptedError;
        }
    }

    /**
     * @throws HttpBadRequestException
     *
     * @return mixed
     */
    protected function resolveArg(string $name)
    {
        if (!isset($this->args[$name])) {
            $unresolvedArgumentException = new class ($name) extends HttpSpecializedAdapter {
                public function __construct(private string $name)
                {
                }

                public function wire(Request $request): HttpException
                {
                    return new HttpBadRequestException($request, sprintf('Could not resolve argument `%s`.', $this->name));
                }
            };
            throw $unresolvedArgumentException;
        }

        return $this->args[$name];
    }
}