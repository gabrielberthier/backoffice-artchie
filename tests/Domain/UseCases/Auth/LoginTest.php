<?php

declare(strict_types=1);

namespace Tests\Domain\UseCases\Auth;

use App\Data\Protocols\Auth\LoginServiceInterface;
use App\Data\Protocols\Cryptography\ComparerInterface;
use App\Data\UseCases\Authentication\Errors\IncorrectPasswordException;
use App\Data\UseCases\Authentication\Login;
use App\Domain\Dto\Credentials;
use App\Domain\Dto\TokenLoginResponse;
use App\Domain\Exceptions\NoAccountFoundException;
use App\Domain\Models\Account;
use App\Domain\Repositories\AccountRepository;
use Ramsey\Uuid\Uuid;
use function PHPUnit\Framework\assertTrue;
use PHPUnit\Framework\MockObject\MockObject;
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


    public function mockRepository(): AccountRepository|MockObject
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
        $uuid = Uuid::fromString('5a4bd710-aab8-4ebc-b65d-0c059a960cfb');
        $account = new Account(null, '', '', '', '', $uuid);
        $mock->expects($this->once())->method('findByAccess')->with('@mail.com')->willReturn($account);
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
        $uuid = Uuid::fromString('5a4bd710-aab8-4ebc-b65d-0c059a960cfb');
        $account = new Account(id: 2, password: 'hashed_password', email: 'mail.com', username: 'user', authType: '', uuid: $uuid);
        $repository->method('findByAccess')->willReturn(
            $account
        );
        $loginService = $this->sut->service;
        $loginService->auth($credentialsStub);
    }

    // Test should throw if password provided differs from retrieved by repository
    public function testShouldThrowIfPasswordDiffersFromRetrievedOne()
    {
        $mockRepository = $this->mockRepository();
        $mockRepository->method('findByAccess')->willReturn(
            new Account(
                2,
                password: 'hashed_password',
                email: 'mail.com',
                username: 'user',
                authType: ''
            )
        );

        $this->expectException(IncorrectPasswordException::class);

        $mock = $this->getMockBuilder(ComparerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $mock->expects($this->once())->method('compare')->willReturn(false);

        /** @var AccountRepository */
        $repository = $mockRepository;

        $loginService = $this->makeService($repository, $mock);

        $credentialsStub = $this->makeCredentials();
        $loginService->auth($credentialsStub);
    }

    // Test success return TokenLoginResponse

    public function testSuccessCase()
    {
        $sut = $this->sut->service;
        $repository = $this->sut->repository;
        $uuid = Uuid::fromString('5a4bd710-aab8-4ebc-b65d-0c059a960cfb');

        $account = new Account(2, password: 'hashed_password', email: 'mail.com', username: 'user', authType: '', uuid: $uuid);

        $repository->method('findByAccess')->willReturn(
            $account
        );
        $credentialsStub = $this->makeCredentials();
        $response = $sut->auth($credentialsStub);
        assertTrue($response instanceof TokenLoginResponse);
    }
}
