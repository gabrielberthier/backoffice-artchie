<?php

declare(strict_types=1);

namespace App\Presentation\Actions\CycleTest;

use App\Data\Entities\Cycle\CycleAccount;
use App\Presentation\Actions\Protocols\Action;
use Cycle\ORM\EntityManager;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class AccountInsertion extends Action
{
    public function __construct(
        private EntityManager $em
    ) {
    }

    public function action(Request $request): Response
    {
        $u = new CycleAccount();
        $u->setEmail('gabsgabs@mail.com')->setUsername('elgabo')->setAuthType('common')->setPassword('hash');
        $this->em->persist(
            $u
        );
        $this->em->run();

        return $this->respondWithData($u);
    }
}
