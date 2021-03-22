<?php

declare(strict_types=1);

namespace Tests\Presentation\Auth;

use App\Data\Protocols\Auth\LoginServiceInterface;
use App\Domain\Models\User;
use App\Domain\Repositories\UserRepository;
use App\Presentation\Actions\Protocols\ActionPayload;
use DI\Container;
use Tests\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Tests\Application\Actions\User\LoginController;

class LoginControllerTest extends TestCase
{
    use ProphecyTrait;

    public function testShouldCallAuthenticationWithCorrectValues()
    {
        /**
         * @param LoginServiceInterface
         */
        $controller = new LoginController($loginService);
    }

    public function testShouldReturn400IfNoUsernameIsProvided()
    {
    }
}
