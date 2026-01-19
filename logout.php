<?php
/**
 * Logout Handler
 * 
 * Destroys admin session and redirects to login page
 */

// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Destroy all session data
session_unset();
session_destroy();

// Redirect to login page
header("Location: login.php?logged_out=true");
exit();

?>
