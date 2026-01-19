<?php
/**
 * Database Connection Configuration
 * 
 * This file handles the connection to MySQL database
 * Contains error handling and connection verification
 */

// Database configuration
$db_host = 'localhost';
$db_user = 'root';
$db_password = '';
$db_name = 'gestion_etudiants';

// Create connection
$connection = mysqli_connect($db_host, $db_user, $db_password, $db_name);

// Check connection
if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}

// Set charset to UTF-8
mysqli_set_charset($connection, "utf8mb4");

?>
