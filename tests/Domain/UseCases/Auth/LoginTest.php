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


class SutTypes
{
    public LoginServiceInterface $sut;

    public function __construct(
        public $repository,
        public $comparer,
    ) {
        $this->sut = new Login($repository, $comparer);
    }
}



class LoginTest extends TestCase
{
    use ProphecyTrait;

    private SutTypes $sut;

    protected function setUp(): void
    {
        $this->sut = new SutTypes($this->mockRepository(), $this->makeComparer());
    }


    function makeCredentials()
    {
        return new Credentials(email: '@mail.com', password: 'password', username: 'chet');
    }

    /**
     * 
     * @param AccountRepository $repository
     * @param ComparerInterface $comparer
     * 
     * @return LoginServiceInterface
     *
     */
    function makeService($repository, $comparer): LoginServiceInterface
    {
        return new Login($repository, $comparer);
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
        return $mock;
    }

    public function testShouldCallRepositoryWithCorrectEmail()
    {
        $mock = $this->sut->repository;
        $loginService = $this->sut->sut;
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
        $mock = $this->sut->repository;
        $loginService =  $this->sut->sut;
        $mock->expects($this->once())->method('findByMail')->willReturn(null);
        $accountStub = $this->makeCredentials();
        $loginService->auth($accountStub);
    }

    // Test compare account hash
    public function testShouldCallHashComparerWithCorrectValues()
    {
        $mock = $this->sut->comparer;
        $credentialsStub = $this->makeCredentials();
        $mock->expects($this->once())
            ->method("compare")
            ->with('password', 'hashed_password');
        $repository = $this->sut->repository;
        $repository->method('findByMail')->willReturn(
            new Account(password: 'hashed_password', email: 'mail.com', username: 'user')
        );
        $loginService =  $this->sut->sut;
        $loginService->auth($credentialsStub);
    }

    // Test should throw if password provided differs from retrieved by repository

    // Test success return TokenLoginResponse
}
