<?php

declare(strict_types=1);

namespace Tests\Domain\UseCases\Auth;

use App\Data\Protocols\Auth\LoginServiceInterface;
use App\Data\UseCases\Authentication\Login;
use App\Domain\Models\Account;
use App\Domain\Repositories\AccountRepository;
use Prophecy\PhpUnit\ProphecyTrait;
use Tests\TestCase;

/**
 * 
 * @param AccountRepository $repository
 *
 */
function makeService($repository): LoginServiceInterface
{
    return new Login($repository);
}

function makeAccountStub()
{
    return new Account(email: '@mail.com', password: 'password', username: 'chet');
}

class LoginTest extends TestCase
{
    use ProphecyTrait;

    public function testShouldCallRepositoryWithCorrectEmail()
    {
        $mock = $this->getMockBuilder(AccountRepository::class)
            ->onlyMethods(['findByMail'])
            ->disableOriginalConstructor()
            ->getMock();
        $loginService = makeService($mock);
        $mock->expects($this->once())->method('findByMail')->with('@mail.com');
        $accountStub = makeAccountStub();
        $loginService->auth($accountStub);
    }
}
