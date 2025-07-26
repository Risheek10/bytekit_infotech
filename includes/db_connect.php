<?php
// This file is responsible for establishing and managing the connection to the MySQL database.
// It creates a PDO object for database interactions.

// IMPORTANT: Update these credentials with your own database details.
// You will get these from your hosting provider or local server setup (e.g., XAMPP/WAMP/MAMP).

$host = 'localhost'; // Database host (usually 'localhost' for local or shared hosting)
$db = 'your_database_name'; // The name of the database you created (e.g., 'bytekit_infotech' or 'yourusername_bytekit_infotech')
$user = 'your_database_user'; // The database username (e.g., 'root' for XAMPP, or 'yourusername_dbuser' for shared hosting)
$pass = 'your_database_password'; // The password for the database user
$charset = 'utf8mb4'; // Standard character set for modern applications

// Data Source Name (DSN) string for PDO connection
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE              => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES     => false,
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