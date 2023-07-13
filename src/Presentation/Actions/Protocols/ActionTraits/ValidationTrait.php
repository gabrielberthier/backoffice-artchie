<?php

namespace App\Presentation\Actions\Protocols\ActionTraits;

use App\Presentation\Actions\Protocols\HttpErrors\UnprocessableEntityException;
use App\Presentation\Helpers\Validation\Validators\Facade\ValidationFacade;
use App\Presentation\Helpers\Validation\Validators\ValidationExceptions\ValidationError;
use Psr\Http\Message\ServerRequestInterface;

trait ValidationTrait
{
    public function rules(ServerRequestInterface $request)
    {
        return null;
    }

    public function messages()
    {
        return null;
    }

    /**
     * @throws UnprocessableEntityException
     */
    protected function validate(ServerRequestInterface $request)
    {
        $rawBody = $request->getBody()->__toString();
        $body = json_decode($rawBody, true);

        $rules = $this->rules($request) ?? [];
        $messages = $this->messages() ?? [];
        $body = $body ?? [];
        $validationFacade = new ValidationFacade($rules, $messages);
        $validator = $validationFacade->createValidations();
        $result = $validator->validate($body);

        if ($result instanceof ValidationError) {
            throw new UnprocessableEntityException($request, $result->getMessage(), $result);
        }
    }
}