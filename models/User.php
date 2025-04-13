<?php

namespace App\Models;

use PDO;

class User
{
    private $db;

    // Constructor to initialize the database connection
    public function __construct()
    {
        // Get the database connection
        $this->db = (new Database())->getConnection();
    }

    // Register a new user
    public function register($data)
    {
        // Check if the email is already registered
        if (!$this->isEmailAvailable($data['email'])) {
            return 'The email is already registered.';
        }

        // Hash the password for secure storage
        $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);

        // Prepare the query to insert the new user data
        $query = 'INSERT INTO users (username, email, password) VALUES (:username, :email, :password)';
        $stmt = $this->db->prepare($query);

        // Bind the parameters to the query
        $stmt->bindParam(':username', $data['username']);
        $stmt->bindParam(':email', $data['email']);
        $stmt->bindParam(':password', $hashedPassword);

        // Execute the query and insert the user into the database
        try {
            if ($stmt->execute()) {
                return true; // Registration successful
            } else {
                return 'There was an error registering the user.'; // If the execution fails
            }
        } catch (\PDOException $e) {
            return 'Database error: ' . $e->getMessage(); // If a database error occurs
        }
    }

    // Log in the user with their credentials
    public function login($credentials)
    {
        // Search for the user in the database by email
        $query = 'SELECT id, username, password FROM users WHERE email = :email';
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':email', $credentials['email']);
        $stmt->execute();

        // Check if the user exists in the database
        if ($stmt->rowCount() > 0) {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            // Verify if the entered password matches the hashed password in the database
            if (password_verify($credentials['password'], $user['password'])) {
                return $user; // User authenticated successfully
            } else {
                return 'Incorrect password.'; // Password mismatch
            }
        } else {
            return 'User does not exist.'; // User not found in the database
        }
    }

    // Check if the email is available (not already registered)
    private function isEmailAvailable($email)
    {
        // Query the database to check if the email is already taken
        $query = 'SELECT id FROM users WHERE email = :email';
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        // Return true if the email is available (not in the database), false if it already exists
        return $stmt->rowCount() === 0;
    }
}
