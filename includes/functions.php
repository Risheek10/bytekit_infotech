<?php
/**
 * Validates and sanitizes string input.
 * @param string $data The input string.
 * @return string The sanitized string.
 */
function sanitize_input($data) {
    $data = trim($data); // Remove whitespace from both ends
    $data = stripslashes($data); // Remove backslashes
    $data = htmlspecialchars($data); // Convert special characters to HTML entities
    return $data;
}

/**
 * Verifies if a user is logged in.
 * @return bool True if logged in, false otherwise.
 */
function is_logged_in() {
    // session_start() should be called at the very top of the main script, not here.
    // This function assumes session is already active.
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

// You can add more general-purpose functions here as your project grows.
// For example: is_admin(), get_product_by_id(), etc.
?>