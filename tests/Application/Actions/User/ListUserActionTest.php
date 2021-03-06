<?php

declare(strict_types=1);

namespace Tests\Application\Actions\User;

use App\Domain\Models\User;
use App\Domain\Repositories\UserRepository;
use App\Presentation\Actions\Protocols\ActionPayload;
use DI\Container;
use Prophecy\PhpUnit\ProphecyTrait;
use Tests\TestCase;

/**
 * @internal
 * @coversNothing
 */
class ListUserActionTest extends TestCase
{
    use ProphecyTrait;

    public function testAction()
    {
        $app = $this->createAppInstance();

        /** @var Container $container */
        $container = $app->getContainer();

        $user = new User(1, 'bill.gates', 'Bill', 'Gates');

        $userRepositoryProphecy = $this->prophesize(UserRepository::class);
        $userRepositoryProphecy
            ->findAll()
            ->willReturn([$user])
            ->shouldBeCalledOnce()
        ;

        $container->set(UserRepository::class, $userRepositoryProphecy->reveal());

        $request = $this->createRequest('GET', '/users');
        $response = $app->handle($request);

        $payload = (string) $response->getBody();
        $expectedPayload = new ActionPayload(200, [$user]);
        $serializedPayload = json_encode($expectedPayload);

        $this->assertEquals($serializedPayload, $payload);
    }
}
