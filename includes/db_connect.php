<?php
// This file is responsible for establishing and managing the connection to the MySQL database. It creates a PDO object for database interactions.

$host = 'localhost'; // Usually 'localhost' for XAMPP/WAMP/MAMP

$db = 'bytekit_infotech'; // The name of the database you created in phpMyAdmin

$user = 'root'; // Default XAMPP MySQL username
$pass = ''; // Default XAMPP MySQL password
$charset = 'utf8mb4'; // Standard character set for modern applications

// Data Source Name (DSN) string for PDO connection
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

// Attempt to establish the database connection
try {
    $pdo = new PDO($dsn, $user, $pass, $options);

} catch (\PDOException $e) {
    // This block catches any errors during the connection attempt (e.g., wrong credentials, MySQL not running)
    echo "<h1>Database Connection Error!</h1>";
    echo "<p>Please ensure MySQL is running in XAMPP and your credentials are correct in includes/db_connect.php.</p>";
    echo "<p>Error: " . $e->getMessage() . "</p>";
    error_log("Database connection error: " . $e->getMessage()); // Logs to Apache's error log
    die("We are experiencing technical difficulties. Please try again later."); // Stops script execution
}
?>