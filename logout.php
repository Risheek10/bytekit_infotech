<?php
// This script handles logging out a user by clearing their session and redirecting them to the homepage or login page.

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Unset all session variables
$_SESSION = array();

// Destroy the session
session_destroy();

// Redirect to the homepage
header("Location: index.php");
exit;
?>