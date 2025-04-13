<?php

namespace Models;

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

    // Check if the email is available (not registered)
    public function isEmailAvailable($email)
    {
        $query = 'SELECT COUNT(*) FROM users WHERE email = :email';
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        return $stmt->fetchColumn() == 0;
    }

    // Check if the username is available (not taken)
    public function isUsernameAvailable($username)
    {
        $query = 'SELECT COUNT(*) FROM users WHERE username = :username';
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->execute();

        return $stmt->fetchColumn() == 0;
    }

    // Register a new user
    public function register($data)
    {
        // If no username is provided, generate one automatically
        $username = isset($data['username']) && !empty($data['username']) ? $data['username'] : strtolower($data['first_name'] . '.' . $data['last_name']);

        // Check if the email is already registered
        if (!$this->isEmailAvailable($data['email'])) {
            return 'The email is already registered.';
        }

        // Check if the username is already taken
        if (!$this->isUsernameAvailable($username)) {
            return 'The username is already taken. Please choose another one.';
        }

        // Hash the password for secure storage
        $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);

        // Prepare the query to insert the new user data
        $query = 'INSERT INTO users (username, first_name, last_name, email, password) 
                  VALUES (:username, :first_name, :last_name, :email, :password)';
        $stmt = $this->db->prepare($query);

        // Bind the parameters to the query
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':first_name', $data['first_name']);
        $stmt->bindParam(':last_name', $data['last_name']);
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

    public function login($credentials)
    {
        // Search for the user in the database by email
        $query = 'SELECT id, username, first_name, last_name, email, password FROM users WHERE email = :email';
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':email', $credentials['email']);
        $stmt->execute();
    
        // Check if the user exists in the database
        if ($stmt->rowCount() > 0) {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
            // Debugging - Show fetched data
            error_log("User found: " . print_r($user, true));  // Log the user data for verification
    
            // Verify if the entered password matches the stored hashed password
            if (password_verify($credentials['password'], $user['password'])) {
                return $user; // User authenticated successfully
            } else {
                // Debugging: Password mismatch
                error_log("Password mismatch for email: " . $credentials['email']);  // Log password mismatch
                return false; // Password mismatch
            }
        } else {
            // Debugging: User not found
            error_log("User not found for email: " . $credentials['email']);  // Log that user was not found
            return false; // User not found
        }
    }    
}
