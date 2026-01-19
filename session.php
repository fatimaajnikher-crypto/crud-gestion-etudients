<?php
/**
 * Session Management
 * 
 * This file handles session initialization and admin verification
 * Include this file at the top of every protected page
 */

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    // Redirect to login page
    header("Location: login.php");
    exit();
}

?>
