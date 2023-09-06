<?php

declare(strict_types=1);

namespace App\Presentation\Actions\CycleTest;

use App\Presentation\Actions\Protocols\Action;
use App\Data\Entities\Cycle\CycleAccount;
use Cycle\ORM\ORM;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class AccountGet extends Action
{
    public function __construct(
        private ORM $orm
    ) {
    }

    public function action(Request $request): Response
    {
        $data = $this->orm->getRepository(CycleAccount::class)->findAll();

        return $this->respondWithData($data);
    }
}