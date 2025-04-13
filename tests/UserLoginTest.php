<?php

use PHPUnit\Framework\TestCase;
use App\User;  // Asumimos que la clase User está en la carpeta /app/models

class UserLoginTest extends TestCase
{
    public function testValidLogin()
    {
        $credentials = ['email' => 'test@example.com', 'password' => 'password123'];
        $user = new User();
        $loginResult = $user->login($credentials);

        $this->assertTrue($loginResult);  // Check if login was successful
    }

    public function testInvalidLogin()
    {
        $credentials = ['email' => 'wrong@example.com', 'password' => 'wrongpassword'];
        $user = new User();
        $loginResult = $user->login($credentials);

        $this->assertFalse($loginResult);  // Check if login fails
    }
}
