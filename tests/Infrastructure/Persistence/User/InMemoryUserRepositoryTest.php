<?php

declare(strict_types=1);

namespace Tests\Infrastructure\Persistence\User;

use App\Domain\Exceptions\UserNotFoundException;
use App\Domain\Models\User;
use App\Infrastructure\Persistence\MemoryRepositories\InMemoryUserRepository;
use Tests\TestCase;

/**
 * @internal
 * @coversNothing
 */
class InMemoryUserRepositoryTest extends TestCase
{
    public function testFindAll()
    {
        $user = new User(1, 'bill.gates', 'Bill', 'Gates');

        $userRepository = new InMemoryUserRepository([1 => $user]);

        $this->assertEquals([$user], $userRepository->findAll());
    }

    public function testFindUserOfId()
    {
        $user = new User(1, 'bill.gates', 'Bill', 'Gates');

        $userRepository = new InMemoryUserRepository([1 => $user]);

        $this->assertEquals($user, $userRepository->findUserOfId(1));
    }

    /**
     * @expectedException \App\Domain\User\UserNotFoundException
     */
    public function testFindUserOfIdThrowsNotFoundException()
    {
        $userRepository = new InMemoryUserRepository([]);
        $this->expectException(UserNotFoundException::class);
        $userRepository->findUserOfId(1);
    }
}
