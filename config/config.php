<?php
// Database configuration
$host = 'localhost';
$dbname = 'your_database_name';
$username = 'your_username';
$password = 'your_password';

// Create a new database connection
$db = new mysqli($host, $username, $password, $dbname);

// Check for connection errors
if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}
?>
