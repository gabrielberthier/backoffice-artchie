<?php

namespace App\Presentation\Actions\Protocols\ActionTraits;

use App\Presentation\Actions\Protocols\HttpErrors\UnprocessableEntityException;
use App\Presentation\Helpers\Validation\Validators\Facade\ValidationFacade;

trait ValidationTrait
{
    public function rules()
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
    protected function validate(null | array | object $body)
    {
        $rules = $this->rules() ?? [];
        $messages = $this->messages() ?? [];
        $body = $body ?? [];
        $validationFacade = new ValidationFacade($rules, $messages);
        $validator = $validationFacade->createValidations();
        $result = $validator->validate($body);

        if ($result) {
            throw new UnprocessableEntityException($this->request, $result->getMessage(), $result);
        }
    }
}
