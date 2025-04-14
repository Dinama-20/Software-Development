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

        // Hash the password before storing it securely
        $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);

        // Prepare the query to insert the new user's data
        $query = 'INSERT INTO users (username, first_name, last_name, email, password) 
                  VALUES (:username, :first_name, :last_name, :email, :password)';
        $stmt = $this->db->prepare($query);

        // Bind the parameters to the query
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':first_name', $data['first_name']);
        $stmt->bindParam(':last_name', $data['last_name']);
        $stmt->bindParam(':email', $data['email']);
        $stmt->bindParam(':password', $hashedPassword);

        // Try to execute the query and insert the user into the database
        try {
            if ($stmt->execute()) {
                return true; // Successful registration
            } else {
                return 'There was an error registering the user.'; // If execution fails
            }
        } catch (\PDOException $e) {
            return 'Database error: ' . $e->getMessage(); // If a database error occurs
        }
    }

    // Check if the email is available
    public function isEmailAvailable($email)
    {
        $query = 'SELECT COUNT(*) FROM users WHERE email = :email';
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        
        return $stmt->fetchColumn() == 0; // Returns true if no user has this email
    }

    // Check if the username is available
    public function isUsernameAvailable($username)
    {
        $query = 'SELECT COUNT(*) FROM users WHERE username = :username';
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        
        return $stmt->fetchColumn() == 0; // Returns true if no user has this username
    }

    // Login the user
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
    
            // Check if the entered password matches the stored password
            if (password_verify($credentials['password'], $user['password'])) {
                return $user; // Successfully authenticated user
            } else {
                return false; // Incorrect password
            }
        } else {
            return false; // User not found
        }
    }    

    // Get user information by ID
    public function getUserById($userId)
    {
        $query = 'SELECT id, username, first_name, last_name, email FROM users WHERE id = :id';
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $userId);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC); // Returns the user data as an associative array
    }

    // Change user password
    public function changePassword($userId, $newPassword)
    {
        // Hash the new password before storing it
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

        $query = 'UPDATE users SET password = :password WHERE id = :id';
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->bindParam(':id', $userId);

        return $stmt->execute(); // Returns true if the update was successful
    }
}
