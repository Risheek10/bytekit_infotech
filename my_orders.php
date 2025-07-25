<?php
// This page allows logged-in users to view a list of all their past orders placed on the ByteKit Infotech website.

session_start();

include_once 'includes/db_connect.php';
include_once 'includes/functions.php';

// Check if the user is logged in
if (!is_logged_in()) {
    $_SESSION['error'] = "You must be logged in to view your orders.";
    redirect('login.php'); // Redirect to login page if not logged in
}

$user_id = $_SESSION['user_id'];
$orders = [];

try {
    // Fetch all orders for the current user, ordered by date (newest first)
    $stmt = $pdo->prepare("SELECT order_id, order_date, total_amount, status FROM orders WHERE user_id = :user_id ORDER BY order_date DESC");
    $stmt->execute(['user_id' => $user_id]);
    $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    error_log("Error fetching user orders: " . $e->getMessage());
    $_SESSION['error'] = "Could not retrieve your orders at this time. Please try again later.";
}

include 'includes/header.php';
?>

<main class="container">
    <section class="my-orders">
        <h2>My Orders</h2>

        <?php
        // Display any session messages (e.g., from a redirect)
        if (isset($_SESSION['message'])) {
            echo '<div class="session-message success-message"><p>' . htmlspecialchars($_SESSION['message']) . '</p></div>';
            unset($_SESSION['message']); // Clear the message after displaying it
        }
        if (isset($_SESSION['error'])) {
            echo '<div class="session-message error-message"><p>' . htmlspecialchars($_SESSION['error']) . '</p></div>';
            unset($_SESSION['error']); // Clear the error after displaying it
        }
        ?>

        <?php if (empty($orders)): ?>
            <p>You haven't placed any orders yet. <a href="products.php">Start shopping now!</a></p>
        <?php else: ?>
            <div class="table-responsive">
                <table class="orders-table">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Order Date</th>
                            <th>Total Amount</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($orders as $order): ?>
                            <tr>
                                <td>#<?php echo htmlspecialchars($order['order_id']); ?></td>
                                <td><?php echo htmlspecialchars(date('Y-m-d H:i', strtotime($order['order_date']))); ?></td>
                                <td>$<?php echo htmlspecialchars(number_format($order['total_amount'], 2)); ?></td>
                                <td><span class="status-<?php echo strtolower(htmlspecialchars($order['status'])); ?>"><?php echo htmlspecialchars($order['status']); ?></span></td>
                                <td>
                                    <a href="view_my_order.php?order_id=<?php echo htmlspecialchars($order['order_id']); ?>" class="button view-details-small">View Details</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </section>
</main>

<?php
// Include the footer
include 'includes/footer.php';
?>