<?php
namespace Models;

use PDO;

class User {
    private $conn;
    private $table = 'users';

    // Constructor to initialize the database connection
    public function __construct($db) {
        $this->conn = $db;
    }

    // Method to register a new user
    public function register($data) {
        // Hash the password before storing it
        $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);

        $query = "INSERT INTO {$this->table} (first_name, last_name, username, email, password, created_at)
                  VALUES (:first_name, :last_name, :username, :email, :password, NOW())";

        $stmt = $this->conn->prepare($query);

        // Bind the data to the statement
        $stmt->bindParam(':first_name', $data['first_name']);
        $stmt->bindParam(':last_name', $data['last_name']);
        $stmt->bindParam(':username', $data['username']);
        $stmt->bindParam(':email', $data['email']);
        $stmt->bindParam(':password', $hashedPassword);  // Use the hashed password

        // Execute the statement and return true if successful, false otherwise
        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    // Method to check if the username is already taken in the database
    public function isUsernameAvailable($username) {
        $query = "SELECT id FROM {$this->table} WHERE username = :username";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->execute();

        // If a result is found, the username is already taken
        if ($stmt->rowCount() > 0) {
            return false;  // Username is taken
        }

        return true;  // Username is available
    }

    // Method to check if the email is already used in the database
    public function isEmailAvailable($email) {
        $query = "SELECT id FROM {$this->table} WHERE email = :email";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        // If a result is found, the email is already used
        if ($stmt->rowCount() > 0) {
            return false;  // Email is taken
        }

        return true;  // Email is available
    }

    // Method to authenticate the user during login
    public function authenticate($username, $password) {
        $query = "SELECT id, username, password, first_name, last_name FROM {$this->table} WHERE username = :username";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->execute();

        // If the user exists, verify if the password matches
        if ($stmt->rowCount() > 0) {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            // Verify the password
            if (password_verify($password, $user['password'])) {
                return $user;  // User is authenticated
            }
        }

        return false;  // Authentication failed
    }
}
