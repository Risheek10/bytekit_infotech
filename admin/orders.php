<?php
// bytekit_infotech/admin/orders.php

include 'includes/admin_header.php'; // Handles session, access control, layout

$orders = [];
$error_message = '';

try {
    // Fetch all orders, joining with the users table to get customer's name
    $stmt = $pdo->query("SELECT
                            o.order_id,
                            o.user_id,
                            u.first_name,
                            u.last_name,
                            u.username, -- Fallback if first/last name not set
                            o.order_date,
                            o.total_amount,
                            o.status
                          FROM
                            orders o
                          JOIN
                            users u ON o.user_id = u.user_id
                          ORDER BY
                            o.order_date DESC");
    $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error_message = 'Error fetching orders: ' . htmlspecialchars($e->getMessage());
    error_log("Admin Orders Page DB Error: " . $e->getMessage());
}
?>

<section class="admin-content">
    <h2>Manage Orders</h2>

    <?php
    // Display session messages (e.g., from order updates)
    if (isset($_SESSION['message'])) {
        echo '<div class="session-message success-message"><p>' . htmlspecialchars($_SESSION['message']) . '</p></div>';
        unset($_SESSION['message']);
    }
    if (isset($_SESSION['error'])) {
        echo '<div class="session-message error-message"><p>' . htmlspecialchars($_SESSION['error']) . '</p></div>';
        unset($_SESSION['error']);
    }
    if ($error_message) {
        echo '<div class="error-messages"><p>' . $error_message . '</p></div>';
    }
    ?>

    <?php if (empty($orders)): ?>
        <p>No orders found yet.</p>
    <?php else: ?>
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Customer</th>
                    <th>Order Date</th>
                    <th>Total Amount</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orders as $order): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($order['order_id']); ?></td>
                        <td>
                            <?php 
                            // Display full name if available, otherwise username
                            echo htmlspecialchars($order['first_name'] . ' ' . $order['last_name'] ?: $order['username']); 
                            ?>
                        </td>
                        <td><?php echo htmlspecialchars($order['order_date']); ?></td>
                        <td>$<?php echo htmlspecialchars(number_format($order['total_amount'], 2)); ?></td>
                        <td><span class="order-status-<?php echo htmlspecialchars($order['status']); ?>"><?php echo ucfirst(htmlspecialchars($order['status'])); ?></span></td>
                        <td class="admin-actions">
                            <a href="view_order.php?id=<?php echo htmlspecialchars($order['order_id']); ?>" class="button edit-button">View Details</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</section>

<?php
include 'includes/admin_footer.php';
?>