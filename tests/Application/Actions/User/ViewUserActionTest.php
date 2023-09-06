<?php

declare(strict_types=1);

namespace Tests\Application\Actions\User;

use App\Domain\Exceptions\UserNotFoundException;
use App\Domain\Models\User;
use App\Domain\Repositories\UserRepository;
use App\Presentation\Actions\Protocols\ActionError;
use App\Presentation\Actions\Protocols\ActionPayload;
use App\Presentation\Actions\Protocols\ErrorsEnum;
use DI\Container;
use Prophecy\Prophet;
use Tests\TestCase;

/**
 * @internal
 * @coversNothing
 */
class ViewUserActionTest extends TestCase
{
    private Prophet $prophet;


    function setUp(): void
    {
        $this->prophet = new Prophet();
    }

    public function testAction()
    {
        $app = $this->createAppInstance();

        /** @var Container $container */
        $container = $app->getContainer();

        $user = new User(1, 'bill.gates', 'Bill', 'Gates');

        $userRepositoryProphecy = $this->prophet->prophesize(UserRepository::class);
        $userRepositoryProphecy
            ->findUserOfId(1)
            ->willReturn($user)
            ->shouldBeCalledOnce()
        ;

        $container->set(UserRepository::class, $userRepositoryProphecy->reveal());

        $request = $this->createRequest('GET', '/users/1');
        $response = $app->handle($request);

        $payload = (string) $response->getBody();
        $expectedPayload = new ActionPayload(200, $user);
        $serializedPayload = json_encode($expectedPayload);

        $this->assertEquals($serializedPayload, $payload);
    }

    public function testActionThrowsUserNotFoundException()
    {
        $app = $this->createAppInstance();

        $this->setUpErrorHandler($app);

        /** @var Container $container */
        $container = $app->getContainer();

        $userRepositoryProphecy = $this->prophet->prophesize(UserRepository::class);
        $userRepositoryProphecy
            ->findUserOfId(1)
            ->willThrow(new UserNotFoundException())
            ->shouldBeCalledOnce()
        ;

        $container->set(UserRepository::class, $userRepositoryProphecy->reveal());

        $request = $this->createRequest('GET', '/users/1');
        $response = $app->handle($request);

        $payload = (string) $response->getBody();
        $expectedError = new ActionError(ErrorsEnum::RESOURCE_NOT_FOUND->value, 'The user you requested does not exist.');
        $expectedPayload = new ActionPayload(404, null, $expectedError);
        $serializedPayload = json_encode($expectedPayload, JSON_PRETTY_PRINT);

        $this->assertEquals($serializedPayload, $payload);
    }
}