<?php
// admin monitoring file

require_once '../includes/db_connect.php';
// require_once 'admin_auth.php';

// --- Data Collection ---

// PHP Version
$phpVersion = phpversion();

// Server Software
$serverSoftware = isset($_SERVER['SERVER_SOFTWARE']) ? $_SERVER['SERVER_SOFTWARE'] : 'N/A';

// Database Connection Status
$dbStatus = 'Connected';
try {
    // Attempt to run a simple query to confirm connection is active
    $pdo->query('SELECT 1');
} catch (PDOException $e) {
    $dbStatus = 'Error: ' . $e->getMessage();
}

// Get Database Metrics (Example queries - adapt to your specific needs)
$userCount = 0;
$productCount = 0;
$categoryCount = 0;
$orderCount = 0;
$pendingOrders = 0;
$shippedOrders = 0;

try {
    $userCount = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
    $productCount = $pdo->query("SELECT COUNT(*) FROM products")->fetchColumn();
    $categoryCount = $pdo->query("SELECT COUNT(*) FROM categories")->fetchColumn();
    $orderCount = $pdo->query("SELECT COUNT(*) FROM orders")->fetchColumn();
    $pendingOrders = $pdo->query("SELECT COUNT(*) FROM orders WHERE status = 'pending'")->fetchColumn();
    $shippedOrders = $pdo->query("SELECT COUNT(*) FROM orders WHERE status = 'shipped'")->fetchColumn();
    // Add more counts as needed (e.g., distinct customers, low stock products)

} catch (PDOException $e) {
    // Handle database query errors if any, perhaps display a warning
    error_log("Monitor page DB query error: " . $e->getMessage());
    $userCount = $productCount = $categoryCount = $orderCount = 'Error';
    $pendingOrders = $shippedOrders = 'Error';
}

// --- HTML Output ---
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Monitoring</title>
    <link rel="stylesheet" href="../css/admin_style.css"> 
    <style>
        /* Basic inline styles for readability, replace with actual CSS */
        body { font-family: Arial, sans-serif; margin: 20px; background-color: #f4f4f4; }
        .container { background-color: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); max-width: 800px; margin: auto; }
        h1 { color: #333; }
        h2 { color: #555; border-bottom: 1px solid #eee; padding-bottom: 5px; margin-top: 20px; }
        .status-item { margin-bottom: 10px; }
        .status-item strong { display: inline-block; width: 150px; }
        .status-ok { color: green; font-weight: bold; }
        .status-error { color: red; font-weight: bold; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Admin Monitoring Page</h1>
        <p>Current time: <?php echo date('Y-m-d H:i:s T'); ?></p>

        <h2>System Information</h2>
        <div class="status-item">
            <strong>PHP Version:</strong> <?php echo htmlspecialchars($phpVersion); ?>
        </div>
        <div class="status-item">
            <strong>Server Software:</strong> <?php echo htmlspecialchars($serverSoftware); ?>
        </div>
        <div class="status-item">
            <strong>Database Status:</strong> 
            <span class="<?php echo (strpos($dbStatus, 'Error') === false) ? 'status-ok' : 'status-error'; ?>">
                <?php echo htmlspecialchars($dbStatus); ?>
            </span>
        </div>
        <h2>Application Metrics</h2>
        <div class="status-item">
            <strong>Total Users:</strong> <?php echo htmlspecialchars($userCount); ?>
        </div>
        <div class="status-item">
            <strong>Total Products:</strong> <?php echo htmlspecialchars($productCount); ?>
        </div>
        <div class="status-item">
            <strong>Total Categories:</strong> <?php echo htmlspecialchars($categoryCount); ?>
        </div>
        <div class="status-item">
            <strong>Total Orders:</strong> <?php echo htmlspecialchars($orderCount); ?>
        </div>
        <div class="status-item">
            <strong>Pending Orders:</strong> <?php echo htmlspecialchars($pendingOrders); ?>
        </div>
        <div class="status-item">
            <strong>Shipped Orders:</strong> <?php echo htmlspecialchars($shippedOrders); ?>
        </div>
        <h2>Error Log Indicators (if applicable)</h2>
        <div class="status-item">
            <strong>Custom Error Log:</strong> [Implement check for your custom error log file here if you have one, e.g., "Exists", "Last modified: X", "Last 5 lines"].
            <br>
            *Note: Full server error logs are usually accessed via DirectAdmin's "Site Summary / Statistics / Logs" or "Error Logs" section.*
        </div>

        <p style="margin-top: 30px;">
            <a href="index.php">Back to Admin Dashboard</a>
        </p>
    </div>
</body>
</html>