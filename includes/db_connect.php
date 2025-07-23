<?php
// bytekit_infotech/includes/db_connect.php

// !!! IMPORTANT !!!
// These are your LOCAL XAMPP MySQL credentials.
// For the live UWindsor server, you MUST change these values!
// (Refer to "Part 2: Deployment to myweb.cs.uwindsor.ca" in previous instructions)

$host = 'localhost'; // Usually 'localhost' for XAMPP/WAMP/MAMP

// Make sure this EXACTLY matches the database name you created in phpMyAdmin.
// If you named it 'bytekit_infotech', use that. If 'bytekit_infotech_db', use that.
// I'm assuming 'bytekit_infotech' as per previous discussions.
$db = 'bytekit_infotech'; // The name of the database you created in phpMyAdmin

$user = 'root'; // Default XAMPP MySQL username
$pass = ''; // Default XAMPP MySQL password (empty for XAMPP root user)
$charset = 'utf8mb4'; // Standard character set for modern applications

// Data Source Name (DSN) string for PDO connection
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

// Options for PDO:
// ATTR_ERRMODE: How PDO handles errors (EXCEPTION is good for development, throws readable errors)
// ATTR_DEFAULT_FETCH_MODE: Default way to fetch results (ASSOC means associative array, e.g., $row['column_name'])
// EMULATE_PREPARES: Disable emulation for security and better performance with prepared statements
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

// Attempt to establish the database connection
try {
    $pdo = new PDO($dsn, $user, $pass, $options);
    // You can add a temporary echo here for debugging if this file is accessed directly,
    // but typically it just sets up $pdo and doesn't output anything.
    // echo "Database connection successful from db_connect.php (included)!<br>";

} catch (\PDOException $e) {
    // This block catches any errors during the connection attempt (e.g., wrong credentials, MySQL not running)

    // For development, you can display the error:
    echo "<h1>Database Connection Error!</h1>";
    echo "<p>Please ensure MySQL is running in XAMPP and your credentials are correct in includes/db_connect.php.</p>";
    echo "<p>Error: " . $e->getMessage() . "</p>";

    // For production, you should log the error and show a generic message:
    error_log("Database connection error: " . $e->getMessage()); // Logs to Apache's error log
    die("We are experiencing technical difficulties. Please try again later."); // Stops script execution
}
?>