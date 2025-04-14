<?php
// File: models/User.php

require_once 'Database.php';

class User
{
    private $db;

    public function __construct()
    {
        // Create a new database connection
        $database = new Database();
        $this->db = $database->getConnection();
    }

    /**
     * Register a new user
     * @param array $data User details (username, email, password, etc.)
     * @return bool True on success, false on failure
     */
    public function register($data)
    {
        $query = 'INSERT INTO users (username, first_name, last_name, email, password)
                  VALUES (:username, :first_name, :last_name, :email, :password)';

        $stmt = $this->db->prepare($query);

        // Hash the password before storing it
        $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);

        $stmt->bindParam(':username', $data['username']);
        $stmt->bindParam(':first_name', $data['first_name']);
        $stmt->bindParam(':last_name', $data['last_name']);
        $stmt->bindParam(':email', $data['email']);
        $stmt->bindParam(':password', $hashedPassword);

        return $stmt->execute();
    }

    /**
     * Authenticate user with email and password
     * @param array $credentials Email and password provided by the user
     * @return array|false User data if login is successful, false otherwise
     */
    public function login($credentials)
    {
        $query = 'SELECT id, username, first_name, last_name, email, password
                  FROM users
                  WHERE email = :email';

        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':email', $credentials['email']);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            // Debug: log user data
            error_log("User found: " . json_encode($user));

            // Verify the password using password_verify()
            if (password_verify($credentials['password'], $user['password'])) {
                error_log("Password verified.");
                return $user;
            } else {
                error_log("Password does not match.");
                return false;
            }
        } else {
            error_log("User not found.");
            return false;
        }
    }
}
