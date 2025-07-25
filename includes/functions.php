<?php
// This file contains general-purpose PHP functions used across different parts of the ByteKit Infotech website, such as input sanitization and redirection.
/**
 * Validates and sanitizes string input.
 * @param string $data The input string.
 * @return string The sanitized string.
 */
function sanitize_input($data) {
    $data = trim($data); 
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

/**
 * Verifies if a user is logged in.
 * @return bool True if logged in, false otherwise.
 */
function is_logged_in() {
    return isset($_SESSION['user_id']);
}

/**
 * Redirects the user to a specified URL.
 * @param string $url The URL to redirect to.
 */
function redirect($url) {
    header("Location: " . $url);
    exit(); // Always exit after a header redirect
}
?>