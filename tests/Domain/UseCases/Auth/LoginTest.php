<?php

declare(strict_types=1);

namespace Tests\Domain\UseCases\Auth;

use App\Data\Protocols\Auth\LoginServiceInterface;
use App\Data\Protocols\Cryptography\ComparerInterface;
use App\Data\UseCases\Authentication\Login;
use App\Domain\Exceptions\NoAccountFoundException;
use App\Domain\Models\Account;
use App\Domain\Models\DTO\Credentials;
use App\Domain\Repositories\AccountRepository;
use PHPUnit\Framework\MockObject\MockObject;
use Prophecy\PhpUnit\ProphecyTrait;
use Tests\TestCase;





class LoginTest extends TestCase
{
    use ProphecyTrait;

    function makeCredentials()
    {
        return new Credentials(email: '@mail.com', password: 'password', username: 'chet');
    }

    /**
     * 
     * @param AccountRepository $repository
     *
     */
    function makeService($repository): LoginServiceInterface
    {
        return new Login($repository);
    }

    /**
     * Create a mocked repository
     *
     * @return MockObject 
     */
    function mockRepository()
    {
        $mock = $this->getMockBuilder(AccountRepository::class)
            ->onlyMethods(['findByMail'])
            ->disableOriginalConstructor()
            ->getMock();
        return $mock;
    }

    /**
     * Create a mocked comparer object
     *
     * @return MockObject
     */
    function makeComparer()
    {
        $mock = $this->getMockBuilder(ComparerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    public function testShouldCallRepositoryWithCorrectEmail()
    {
        $mock = $this->mockRepository();
        $loginService = $this->makeService($mock);
        $mock->expects($this->once())->method('findByMail')->with('@mail.com')->willReturn(new Account(null, '', '', ''));
        $accountStub = $this->makeCredentials();
        $loginService->auth($accountStub);
    }

    // Test should throw error if no account is found
    /**
     * @expectedException NoAccountFoundException
     */
    public function testShouldThrowErrorIfNoAccountIsFound()
    {
        $this->expectException(NoAccountFoundException::class);
        $mock = $this->mockRepository();
        $loginService =  $this->makeService($mock);
        $mock->expects($this->once())->method('findByMail')->willReturn(null);
        $accountStub = $this->makeCredentials();
        $loginService->auth($accountStub);
    }

    // Test compare account hash
    public function testShouldCallHashComparerWithCorrectValues()
    {
        $mock = $this->makeComparer();
        $credentials = $this->makeCredentials();
        $mock->expects($this->once())->method("compare")->with("password");
    }

    // Test should throw if password provided differs from retrieved by repository

    // Test success return TokenLoginResponse
}
