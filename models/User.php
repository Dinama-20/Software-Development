<?php
namespace Models;

require_once 'database.php';

class User {
    private $conn;

    // Constructor: connect to the database
    public function __construct() {
        $db = new \Database(); // Use global namespace for Database
        $this->conn = $db->getConnection();
    }

    // Register a new user
    public function register($name, $email, $password) {
        // Check if the email is already registered
        $stmt = $this->conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);

        if ($stmt->rowCount() > 0) {
            return ['success' => false, 'message' => 'Email already registered'];
        }

        // Hash the password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Insert the new user into the database
        $stmt = $this->conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
        $result = $stmt->execute([$name, $email, $hashedPassword]);

        if ($result) {
            return ['success' => true, 'message' => 'User registered successfully'];
        } else {
            return ['success' => false, 'message' => 'Registration failed'];
        }
    }

    // Authenticate a user during login
    public function login($email, $password) {
        // Retrieve user by email
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);

        if ($stmt->rowCount() > 0) {
            $user = $stmt->fetch();

            // Verify the hashed password
            if (password_verify($password, $user['password'])) {
                return ['success' => true, 'user' => $user];
            }
        }

        return ['success' => false, 'message' => 'Invalid email or password'];
    }

    // Get a user by their ID
    public function getUserById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$id]);

        return $stmt->fetch();
    }
}
