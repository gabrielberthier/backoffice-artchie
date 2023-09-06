<?php

declare(strict_types=1);

namespace Tests\Presentation\Middleware;

use App\Domain\Dto\AccountDto;
use App\Infrastructure\Cryptography\BodyTokenCreator;
use App\Infrastructure\Persistence\MemoryRepositories\InMemoryAccountRepository;
use App\Presentation\Middleware\JWTAuthMiddleware;
use Middlewares\Utils\RequestHandler;
use Nyholm\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerInterface;
use Tests\TestCase;

use function PHPUnit\Framework\assertNotNull;
use function PHPUnit\Framework\assertSame;

/**
 * @internal
 * @coversNothing
 */
class JwtAuthMiddlewareTest extends TestCase
{
    private \Prophecy\Prophet $prophet;
    private JWTAuthMiddleware $sut;

    public function setUp(): void
    {
        $container = $this->getContainer(true);
        $logger = $container->get(LoggerInterface::class);

        $this->sut = new JWTAuthMiddleware($logger);

    }

    public function testShouldPassWhenJwtIsProvidedAndReturnTokenInAttributes()
    {
        $dto = new AccountDto(email: 'mail.com', username: 'user', password: 'pass');
        $repository = new InMemoryAccountRepository();
        $account = $repository->insert($dto);

        $tokenCreator = new BodyTokenCreator($account);
        $token = $tokenCreator->createToken($_ENV['JWT_SECRET']);
        $request = $this->createRequestWithAuthentication($token);
        $response = $this->sut->process(
            $request,
            new RequestHandler(
                function (ServerRequestInterface $request): ResponseInterface {
                    $response = new Response();
                    $response->getBody()->write(json_encode($request->getAttribute('token')));

                    return $response;
                }
            )
        );

        assertNotNull($response);
        $decoded = json_decode($response->getBody()->__toString());
        $this->assertSame($decoded->data->role, 'common');
        assertSame(200, $response->getStatusCode());

    }

    private function createRequestWithAuthentication(string $token)
    {
        $bearer = 'Bearer ' . $token;

        return $this->createRequest(
            'GET',
            '/api/test-auth',
            [
                'HTTP_ACCEPT' => 'application/json',
                'Content-Type' => 'application/json',
                'Authorization' => $bearer,
            ],
        );
    }
}