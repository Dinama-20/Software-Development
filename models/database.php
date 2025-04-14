<?php

namespace Models;

use mysqli;

class Database {
    private $host = 'localhost'; // Database host
    private $dbname = 'onate_store'; // Database name
    private $username = 'root'; // Default username for XAMPP
    private $password = ''; // Default password for XAMPP (empty)
    private $connection;

    public function getConnection() {
        if ($this->connection === null) {
            // Create a new database connection
            $this->connection = new mysqli($this->host, $this->username, $this->password, $this->dbname);

            // Check for connection errors
            if ($this->connection->connect_error) {
                die("Connection failed: " . $this->connection->connect_error);
            }
        }
        return $this->connection;
    }
}
?>
