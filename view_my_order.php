<?php
// This page displays the detailed contents of a specific order for a logged-in user, including items and shipping information.
session_start();
require_once 'includes/db_connect.php';
require_once 'includes/header.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$order = null;
$order_items = [];
$error_message = '';

// Check if order_id is provided in the URL
if (isset($_GET['order_id']) && !empty($_GET['order_id'])) {
    $order_id = intval($_GET['order_id']); // Ensure it's an integer

    try {
        // Fetch order details
        $stmt = $pdo->prepare("SELECT * FROM orders WHERE order_id = ? AND user_id = ?");
        $stmt->execute([$order_id, $user_id]);
        $order = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($order) {
            // Fetch order items if the order exists and belongs to the user
            $stmt_items = $pdo->prepare("
                SELECT oi.*, p.name AS product_name, p.price AS product_current_price
                FROM order_items oi
                JOIN products p ON oi.product_id = p.product_id
                WHERE oi.order_id = ?
            ");
            $stmt_items->execute([$order_id]);
            $order_items = $stmt_items->fetchAll(PDO::FETCH_ASSOC);
        } else {
            $error_message = "Order not found or you do not have permission to view this order.";
        }

    } catch (PDOException $e) {
        $error_message = "Database error: " . $e->getMessage();
    }
} else {
    $error_message = "No order ID provided.";
}
?>

<div class="container product-detail-container">
    <main>
        <?php if ($error_message): ?>
            <div class="error-messages">
                <p><?php echo $error_message; ?></p>
                <p><a href="my_orders.php" class="button back-to-products">Back to My Orders</a></p>
            </div>
        <?php elseif ($order): ?>
            <h2>Order Details for #<?php echo htmlspecialchars($order['order_id']); ?></h2>

            <div class="order-detail-grid">
                <div class="order-info-block">
                    <h3>Order Summary</h3>
                    <p><strong>Order ID:</strong> #<?php echo htmlspecialchars($order['order_id']); ?></p>
                    <p><strong>Order Date:</strong> <?php echo htmlspecialchars(date('F j, Y, g:i a', strtotime($order['order_date']))); ?></p>
                    <p><strong>Total Amount:</strong> $<?php echo htmlspecialchars(number_format($order['total_amount'], 2)); ?></p>
                    <p><strong>Status:</strong> <span class="status-<?php echo strtolower(htmlspecialchars($order['status'])); ?>"><?php echo htmlspecialchars($order['status']); ?></span></p>
                    </div>

                <div class="order-info-block">
                    <h3>Shipping Details</h3>
                    <p><strong>Customer Name:</strong> <?php echo htmlspecialchars($order['shipping_name'] ?? $_SESSION['username']); ?></p>
                    <p><strong>Address:</strong> <?php echo htmlspecialchars($order['shipping_address'] ?? 'N/A'); ?></p>
                    <p><strong>City:</strong> <?php echo htmlspecialchars($order['shipping_city'] ?? 'N/A'); ?></p>
                    <p><strong>Postal Code:</strong> <?php echo htmlspecialchars($order['shipping_postal_code'] ?? 'N/A'); ?></p>
                    <p><strong>Country:</strong> <?php echo htmlspecialchars($order['shipping_country'] ?? 'N/A'); ?></p>
                    </div>
            </div>

            <h3>Ordered Products</h3>
            <?php if (!empty($order_items)): ?>
                <div class="table-responsive">
                    <table class="orders-table"> <thead>
                            <tr>
                                <th>Product</th>
                                <th>Price at Purchase</th>
                                <th>Quantity</th>
                                <th>Item Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($order_items as $item): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($item['product_name']); ?></td>
                                    <td>$<?php echo htmlspecialchars(number_format($item['price_at_purchase'], 2)); ?></td>
                                    <td><?php echo htmlspecialchars($item['quantity']); ?></td>
                                    <td>$<?php echo htmlspecialchars(number_format($item['quantity'] * $item['price_at_purchase'], 2)); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <p>No items found for this order.</p>
            <?php endif; ?>

            <p class="text-right">
                <a href="my_orders.php" class="button back-to-products">Back to My Orders</a>
            </p>

        <?php else: ?>
            <div class="error-messages">
                <p>Unable to retrieve order details. Please try again or check your order ID.</p>
                <p><a href="my_orders.php" class="button back-to-products">Back to My Orders</a></p>
            </div>
        <?php endif; ?>
    </main>
</div>

<?php require_once 'includes/footer.php'; // Include your footer ?>