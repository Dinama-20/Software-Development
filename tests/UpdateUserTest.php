<?php

use PHPUnit\Framework\TestCase;
use Models\User;
use Models\Database;

class UpdateUserTest extends TestCase
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
    public function itUpdatesUserSuccessfully()
    {
        $userId = 1;
        $username = 'newusername';
        $email = 'newemail@example.com';
        $password = password_hash('newpassword', PASSWORD_BCRYPT);

        $result = $this->user->updateUser($userId, $username, $email, $password);
        $this->assertTrue($result, "User update failed.");
    }

    /**
     * @test
     */
    public function itDetectsUsernameConflict()
    {
        $userId = 1;
        $username = 'existingusername';
        $email = 'newemail@example.com';
        $password = password_hash('newpassword', PASSWORD_BCRYPT);

        $this->assertTrue($this->user->isUsernameTaken($username, $userId), "Username conflict not detected.");
    }

    /**
     * @test
     */
    public function itDetectsEmailConflict()
    {
        $userId = 1;
        $username = 'newusername';
        $email = 'existingemail@example.com';
        $password = password_hash('newpassword', PASSWORD_BCRYPT);

        $this->assertTrue($this->user->isEmailTaken($email, $userId), "Email conflict not detected.");
    }
}
