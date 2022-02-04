<?php

declare(strict_types=1);

namespace Tests\Domain\UseCases\Auth;

use App\Data\Protocols\Auth\LoginServiceInterface;
use App\Data\Protocols\Cryptography\ComparerInterface;
use App\Data\UseCases\Authentication\Errors\IncorrectPasswordException;
use App\Data\UseCases\Authentication\Login;
use App\Domain\DTO\Credentials;
use App\Domain\DTO\TokenLoginResponse;
use App\Domain\Exceptions\NoAccountFoundException;
use App\Domain\Models\Account;
use App\Domain\Repositories\AccountRepository;
use function PHPUnit\Framework\assertTrue;
use PHPUnit\Framework\MockObject\MockObject;
use Prophecy\PhpUnit\ProphecyTrait;
use Tests\TestCase;

class SutTypes
{
    public LoginServiceInterface $service;

    public function __construct(
        public $repository,
        public $comparer
    ) {
        $this->service = new Login($repository, $comparer);
    }
}

/**
 * @internal
 * @coversNothing
 */
class LoginTest extends TestCase
{
    use ProphecyTrait;

    private SutTypes $sut;

    protected function setUp(): void
    {
        $this->sut = new SutTypes($this->mockRepository(), $this->makeComparer());
    }

    public function makeCredentials()
    {
        return new Credentials(access: '@mail.com', password: 'password');
    }

    /**
     * @param AccountRepository $repository
     * @param ComparerInterface $comparer
     */
    public function makeService($repository, $comparer): LoginServiceInterface
    {
        return new Login($repository, $comparer);
    }

    /**
     * Create a mocked repository.
     *
     * @return MockObject
     */
    public function mockRepository()
    {
        return $this->getMockBuilder(AccountRepository::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    /**
     * Create a mocked comparer object.
     *
     * @return MockObject
     */
    public function makeComparer()
    {
        $mock = $this->getMockBuilder(ComparerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $mock->method('compare')->willReturn(true);

        return $mock;
    }

    public function testShouldCallRepositoryWithCorrectEmail()
    {
        $mock = $this->sut->repository;
        $loginService = $this->sut->service;
        $mock->expects($this->once())->method('findByAccess')->with('@mail.com')->willReturn(new Account(null, '', '', ''));
        $accountStub = $this->makeCredentials();
        $loginService->auth($accountStub);
    }

    // Test should throw error if no account is found

    /**
     * @expectedException \NoAccountFoundException
     */
    public function testShouldThrowErrorIfNoAccountIsFound()
    {
        $this->expectException(NoAccountFoundException::class);
        $mock = $this->sut->repository;
        $loginService = $this->sut->service;
        $mock->expects($this->once())->method('findByAccess')->willReturn(null);
        $accountStub = $this->makeCredentials();
        $loginService->auth($accountStub);
    }

    // Test compare account hash
    public function testShouldCallHashComparerWithCorrectValues()
    {
        $mock = $this->sut->comparer;
        $credentialsStub = $this->makeCredentials();
        $mock->expects($this->once())
            ->method('compare')
            ->with('password', 'hashed_password');
        $repository = $this->sut->repository;
        $repository->method('findByAccess')->willReturn(
            new Account(id: 2, password: 'hashed_password', email: 'mail.com', username: 'user')
        );
        $loginService = $this->sut->service;
        $loginService->auth($credentialsStub);
    }

    // Test should throw if password provided differs from retrieved by repository
    public function testShouldThrowIfPasswordDiffersFromRetrievedOne()
    {
        $repository = $this->mockRepository();
        $repository->method('findByAccess')->willReturn(
            new Account(2, password: 'hashed_password', email: 'mail.com', username: 'user')
        );

        $this->expectException(IncorrectPasswordException::class);
        /**
         * @var MockObject
         */
        $mock = $this->getMockBuilder(ComparerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $mock->expects($this->once())->method('compare')->willReturn(false);

        $loginService = $this->makeService($repository, $mock);

        $credentialsStub = $this->makeCredentials();
        $loginService->auth($credentialsStub);
    }

    // Test success return TokenLoginResponse

    public function testSuccessCase()
    {
        $sut = $this->sut->service;
        $repository = $this->sut->repository;
        $repository->method('findByAccess')->willReturn(
            new Account(2, password: 'hashed_password', email: 'mail.com', username: 'user')
        );
        $credentialsStub = $this->makeCredentials();
        $response = $sut->auth($credentialsStub);
        assertTrue($response instanceof TokenLoginResponse);
    }
}
