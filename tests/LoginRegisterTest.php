<?php

use PHPUnit\Framework\TestCase;
use Models\User;
use Models\Database;

class LoginRegisterTest extends TestCase
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
    public function itRegistersUserSuccessfully()
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
     */
    public function itAuthenticatesUserSuccessfully()
    {
        $username = 'johndoe';
        $password = 'password123';

        $result = $this->user->authenticate($username, $password);
        $this->assertIsArray($result, "User authentication failed.");
    }

    /**
     * @test
     */
    public function itFailsAuthenticationWithInvalidCredentials()
    {
        $username = 'invaliduser';
        $password = 'wrongpassword';

        $result = $this->user->authenticate($username, $password);
        $this->assertFalse($result, "Authentication should fail with invalid credentials.");
    }
}
