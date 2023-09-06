<?php

declare(strict_types=1);

namespace Tests\Domain\User;

use App\Domain\Models\User;
use Tests\TestCase;

class UserTest extends TestCase
{
    public static function userProvider()
    {
        return [
            [1, 'bill.gates', 'Bill', 'Gates'],
            [2, 'steve.jobs', 'Steve', 'Jobs'],
            [3, 'mark.zuckerberg', 'Mark', 'Zuckerberg'],
            [4, 'evan.spiegel', 'Evan', 'Spiegel'],
            [5, 'jack.dorsey', 'Jack', 'Dorsey'],
        ];
    }

    /**
     * @dataProvider userProvider
     * @param $id
     * @param $username
     * @param $firstName
     * @param $lastName
     */
    public function testGetters($id, $username, $firstName, $lastName)
    {
        $user = new User($id, $username, $firstName, $lastName);

        $this->assertEquals($id, $user->id);
        $this->assertEquals($username, $user->username);
        $this->assertEquals($firstName, $user->firstName);
        $this->assertEquals($lastName, $user->lastName);
    }

    /**
     * @dataProvider userProvider
     * @param $id
     * @param $username
     * @param $firstName
     * @param $lastName
     */
    public function testJsonSerialize($id, $username, $firstName, $lastName)
    {
        $user = new User($id, $username, $firstName, $lastName);

        $expectedPayload = json_encode([
            'id' => $id,
            'username' => $username,
            'firstName' => $firstName,
            'lastName' => $lastName,
        ]);

        $this->assertEquals($expectedPayload, json_encode($user));
    }
}