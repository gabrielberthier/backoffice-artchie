<?php

declare(strict_types=1);

namespace App\Presentation\Actions\Protocols;

use App\Domain\Exceptions\Protocols\DomainRecordNotFoundException;
use App\Presentation\Helpers\Validation\ValidationFacade;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Log\LoggerInterface;
use Slim\Exception\HttpBadRequestException;
use Slim\Exception\HttpNotFoundException;
use UnprocessableEntityException;

abstract class Action
{
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
            $this->validate($this->request->getParsedBody());

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
     * @return array|object
     */
    protected function getFormData()
    {
        $input = json_decode(file_get_contents('php://input'));

        if (JSON_ERROR_NONE !== json_last_error()) {
            throw new HttpBadRequestException($this->request, 'Malformed JSON input.');
        }

        return $input;
    }

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

    /**
     * @param null|array|object $data
     */
    protected function respondWithData($data = null): Response
    {
        $payload = new ActionPayload(200, $data);

        return $this->respond($payload);
    }

    protected function respond(ActionPayload $payload): Response
    {
        $json = json_encode($payload, JSON_PRETTY_PRINT);
        $this->response->getBody()->write($json);

        return $this->response->withHeader('Content-Type', 'application/json');
    }

    protected function rules(): ?array
    {
        return null;
    }

    protected function messages(): ?array
    {
        return null;
    }

    /**
     * @throws UnprocessableEntityException
     */
    private function validate(null | array | object $body)
    {
        $rules = $this->rules() ?? [];
        $messages = $this->messages() ?? [];
        $validationFacade = new ValidationFacade($rules, $messages);
        $validator = $validationFacade->createValidations();
        $result = $validator->validate($body);
        if ($result) {
            throw $result;
        }
    }
}
