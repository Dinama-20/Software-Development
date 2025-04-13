<?php

namespace App\Controllers;

use App\Models\User;

class UserController
{
    public function register()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Handle user registration logic
            $user = new User();
            $result = $user->register($_POST);
            
            if ($result) {
                header("Location: /login");
                exit;
            } else {
                // Show registration error message
            }
        }

        // Show the registration form
        include 'app/views/register.php';
    }

    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Handle user login logic
            $user = new User();
            $result = $user->login($_POST);
            
            if ($result) {
                header("Location: /dashboard");
                exit;
            } else {
                // Show login error message
            }
        }

        // Show the login form
        include 'app/views/login.php';
    }
}
