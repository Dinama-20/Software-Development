<?php
// Include the Database class
require_once '../models/database.php';

// Create a new database connection using the Database class
use Models\Database;
$db = (new Database())->getConnection();
?>
