<?php
namespace Models;

use mysqli;

class User {
    private $db;

    public function __construct($db) {
        $this->db = $db; // Inject the database connection
    }

    // Registers a new user in the database
    public function register($data) {
        $query = "INSERT INTO users (first_name, last_name, username, email, password, created_at)
                  VALUES (?, ?, ?, ?, ?, NOW())";
        $stmt = $this->db->prepare($query);

        if (!$stmt) {
            throw new \Exception("Failed to prepare statement: " . $this->db->error);
        }

        // Bind the data to the statement
        $stmt->bind_param('sssss', $data['first_name'], $data['last_name'], $data['username'], $data['email'], $data['password']);

        // Execute the statement and return true if successful, false otherwise
        return $stmt->execute();
    }

    // Checks if a username is available
    public function isUsernameAvailable($username) {
        $query = "SELECT id FROM users WHERE username = ?";
        $stmt = $this->db->prepare($query);

        if (!$stmt) {
            throw new \Exception("Failed to prepare statement: " . $this->db->error);
        }

        $stmt->bind_param('s', $username);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->num_rows === 0; // Returns true if no rows are found
    }

    // Checks if an email is available
    public function isEmailAvailable($email) {
        $query = "SELECT id FROM users WHERE email = ?";
        $stmt = $this->db->prepare($query);

        if (!$stmt) {
            throw new \Exception("Failed to prepare statement: " . $this->db->error);
        }

        $stmt->bind_param('s', $email);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->num_rows === 0; // Returns true if no rows are found
    }

    // Authenticates a user during login
    public function authenticate($username, $password) {
        // Use positional placeholders for mysqli
        $query = "SELECT id, username, password FROM users WHERE username = ?";

        // Prepare the SQL statement
        $stmt = $this->db->prepare($query);

        if (!$stmt) {
            throw new \Exception("Failed to prepare statement: " . $this->db->error);
        }

        // Bind the username parameter
        $stmt->bind_param('s', $username);

        // Execute the query
        $stmt->execute();

        // Get the result
        $result = $stmt->get_result();

        // Check if a user was found
        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();

            // Verify the password
            if (password_verify($password, $user['password'])) {
                return $user; // Return user data if authentication is successful
            } else {
                throw new \Exception("Invalid password.");
            }
        } else {
            throw new \Exception("User not found.");
        }
    }

    // Updates user details in the database
    public function updateUser($id, $username, $email, $password) {
        $query = "UPDATE users SET username = ?, email = ?, password = ? WHERE id = ?";
        $stmt = $this->db->prepare($query);

        if (!$stmt) {
            throw new \Exception("Failed to prepare statement: " . $this->db->error);
        }

        $stmt->bind_param('sssi', $username, $email, $password, $id);
        return $stmt->execute();
    }

    // Checks if an email is taken by another user
    public function isEmailTaken($email, $userId) {
        $query = "SELECT id FROM users WHERE email = ? AND id != ?";
        $stmt = $this->db->prepare($query);

        if (!$stmt) {
            throw new \Exception("Failed to prepare statement: " . $this->db->error);
        }

        $stmt->bind_param('si', $email, $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->num_rows > 0;
    }

    // Checks if a username is taken by another user
    public function isUsernameTaken($username, $userId) {
        $query = "SELECT id FROM users WHERE username = ? AND id != ?";
        $stmt = $this->db->prepare($query);

        if (!$stmt) {
            throw new \Exception("Failed to prepare statement: " . $this->db->error);
        }

        $stmt->bind_param('si', $username, $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->num_rows > 0;
    }
}
?>
