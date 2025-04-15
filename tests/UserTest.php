<?php

use PHPUnit\Framework\TestCase;
use Models\User;
use Models\Database;

class UserTest extends TestCase
{
    private $db;
    private $user;

    /**
     * @before
     */
    public function setUpDatabase(): void
    {
        $this->db = (new Database())->getConnection();
        $this->user = new User($this->db);
    }

    /**
     * @test
     */
    public function itRegistersAUser()
    {
        $userData = [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'username' => 'johndoe',
            'email' => 'johndoe@example.com',
            'password' => password_hash('password123', PASSWORD_BCRYPT),
        ];

        $result = $this->user->register($userData);
        $this->assertTrue($result, "User registration failed.");
    }

    /**
     * @test
     * @depends itRegistersAUser
     */
    public function itChecksUsernameAvailability()
    {
        $result = $this->user->isUsernameAvailable('nonexistentuser');
        $this->assertTrue($result, "Username should be available.");
    }

    /**
     * @test
     * @dataProvider authenticationProvider
     */
    public function itAuthenticatesAUser($username, $password, $expected)
    {
        $result = $this->user->authenticate($username, $password);
        $this->assertEquals($expected, is_array($result), "Authentication result mismatch.");
    }

    public function authenticationProvider()
    {
        return [
            ['johndoe', 'password123', true],
            ['johndoe', 'wrongpassword', false],
        ];
    }

    /**
     * @test
     */
    public function itThrowsExceptionForInvalidUser()
    {
        $this->expectException(\PDOException::class);
        $this->user->authenticate(null, null);
    }
}
