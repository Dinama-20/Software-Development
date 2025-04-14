<?php
namespace Models;

use PDO;
use PDOException;

class Database {
    // Database connection details
    private $host = 'localhost';
    private $db_name = 'onate_store';
    private $username = 'root';
    private $password = '';
    private $conn;

    // Method to establish and return a database connection
    public function getConnection() {
        // If there is no existing connection, create one
        if ($this->conn === null) {
            try {
                // Set up the PDO connection
                $this->conn = new PDO(
                    "mysql:host={$this->host};dbname={$this->db_name}",
                    $this->username,
                    $this->password
                );
                // Set PDO attributes for error handling
                $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                // If there's an error during connection, stop and show the error message
                die("Connection failed: " . $e->getMessage());
            }
        }
        // Return the established connection
        return $this->conn;
    }
}
