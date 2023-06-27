<?php

namespace Tests\Application\Actions\User;

use App\Domain\Models\User;
use App\Domain\Repositories\UserRepository;
use App\Presentation\Actions\Protocols\ActionPayload;
use DI\Container;
use Prophecy\Prophet;
use Tests\TestCase;

/**
 * @internal
 * @coversNothing
 */
class ListUserActionTest extends TestCase
{
    private Prophet $prophet;

    function setUp(): void
    {
        $this->prophet = new Prophet();
    }

    function tearDown(): void
    {
        $this->prophet->checkPredictions();
    }

    public function testShouldCallActionSuccessfully()
    {
        $app = $this->createAppInstance();

        /** @var Container $container */
        $container = $app->getContainer();

        $user = new User(1, 'bill.gates', 'Bill', 'Gates');

        $userRepositoryProphecy = $this->prophet->prophesize()->willImplement(UserRepository::class);
        $userRepositoryProphecy
            ->findAll()
            ->willReturn([$user])
            ->shouldBeCalledOnce();

        $container->set(UserRepository::class, $userRepositoryProphecy->reveal());

        $request = $this->createRequest('GET', '/users');
        $response = $app->handle($request);

        $payload = (string) $response->getBody();
        $expectedPayload = new ActionPayload(200, [$user]);
        $serializedPayload = json_encode($expectedPayload);

        $this->assertEquals($serializedPayload, $payload);
    }
}