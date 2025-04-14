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

    // Registers a new user in the database
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
        return $stmt->execute();
    }

    // Checks if a username is available
    public function isUsernameAvailable($username) {
        $query = "SELECT id FROM {$this->table} WHERE username = :username";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        return $stmt->rowCount() === 0; // Returns true if no rows are found
    }

    // Checks if an email is available
    public function isEmailAvailable($email) {
        $query = "SELECT id FROM {$this->table} WHERE email = :email";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return $stmt->rowCount() === 0; // Returns true if no rows are found
    }

    // Authenticates a user during login
    public function authenticate($username, $password) {
        $query = "SELECT id, username, email, password, first_name, last_name FROM {$this->table} WHERE username = :username";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            // Verifies the password with the stored hash
            if (password_verify($password, $user['password'])) {
                return $user; // Returns user data if authentication is successful
            }
        }
        return false; // Returns false if authentication fails
    }

    // Updates user details in the database
    public function updateUser($id, $username, $email, $password) {
        $query = "UPDATE {$this->table} SET username = :username, email = :email, password = :password WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $password);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    // Checks if an email is taken by another user
    public function isEmailTaken($email, $userId) {
        $query = "SELECT id FROM {$this->table} WHERE email = :email AND id != :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':id', $userId);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }

    // Checks if a username is taken by another user
    public function isUsernameTaken($username, $userId) {
        $query = "SELECT id FROM {$this->table} WHERE username = :username AND id != :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':id', $userId);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }
}
?>
