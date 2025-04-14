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
        $query = "INSERT INTO {$this->table} (first_name, last_name, username, email, password, created_at)
                  VALUES (:first_name, :last_name, :username, :email, :password, NOW())";

        $stmt = $this->conn->prepare($query);

        // Bind the data to the statement
        $stmt->bindParam(':first_name', $data['first_name']);
        $stmt->bindParam(':last_name', $data['last_name']);
        $stmt->bindParam(':username', $data['username']);
        $stmt->bindParam(':email', $data['email']);
        $stmt->bindParam(':password', $data['password']);

        // Execute the statement and return true if successful, false otherwise
        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    // Method to check if the username already exists in the database
    public function isUsernameAvailable($username) {
        $query = "SELECT id FROM {$this->table} WHERE username = :username";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->execute();

        // If there is a result, the username already exists
        if ($stmt->rowCount() > 0) {
            return false;  // Username is taken
        }

        return true;  // Username is available
    }

    // Method to check if the email already exists in the database
    public function isEmailAvailable($email) {
        $query = "SELECT id FROM {$this->table} WHERE email = :email";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        // If there is a result, the email already exists
        if ($stmt->rowCount() > 0) {
            return false;  // Email is taken
        }

        return true;  // Email is available
    }

    // Method to authenticate the user during login
    public function authenticate($username, $password) {
        // Prepare query to get user details based on username
        $query = "SELECT id, username, password, first_name, last_name FROM {$this->table} WHERE username = :username";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        
        // If the user exists, check if the password matches
        if ($stmt->rowCount() > 0) {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // Debugging: Output the fetched user data to check if username is correct
            error_log("Fetched user: " . print_r($user, true));
            
            // Verify the password with the stored hashed password
            if (password_verify($password, $user['password'])) {
                // Debugging: If password verification is successful, log this
                error_log("Password is correct. User authenticated.");
                return $user; // Authentication successful
            } else {
                // Debugging: If password verification fails
                error_log("Password mismatch for username: $username");
            }
        } else {
            // Debugging: If the username does not exist
            error_log("No user found with username: $username");
        }
        
        return false; // Authentication failed
    }
}
?>
