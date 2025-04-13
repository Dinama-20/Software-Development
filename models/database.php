<?php

namespace Models;

use PDO;

class Database {
    private $host = 'localhost';
    private $dbname = 'your_database_name'; // Make sure this name is correct
    private $username = 'root'; // Or your database username
    private $password = ''; // Or your password if you have one
    private $conn;

    // Get the database connection
    public function getConnection() {
        if ($this->conn === null) {
            try {
                $this->conn = new PDO("mysql:host={$this->host};dbname={$this->dbname}", $this->username, $this->password);
                $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                die("Connection error: " . $e->getMessage());
            }
        }
        return $this->conn;
    }
}
