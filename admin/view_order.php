<?php
// This page in the admin panel allows administrators to view the detailed contents and update the status of a specific customer order.

include 'includes/admin_header.php'; // Handles session, access control, layout

$order_id = filter_var($_GET['id'] ?? null, FILTER_VALIDATE_INT);
$order_details = [];
$order_items = [];
$customer_details = [];
$errors = [];
$success_message = '';

if (!$order_id) {
    $_SESSION['error'] = "No order ID specified.";
    redirect('orders.php');
    exit;
}

// Handle Order Status Update
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_status'])) {
    $new_status = sanitize_input($_POST['new_status']);
    
    // Validate new_status against allowed ENUM values
    $allowed_statuses = ['pending', 'processing', 'shipped', 'delivered', 'cancelled'];
    if (!in_array($new_status, $allowed_statuses)) {
        $errors[] = "Invalid status provided.";
    } else {
        try {
            $stmt = $pdo->prepare("UPDATE orders SET status = ?, updated_at = CURRENT_TIMESTAMP WHERE order_id = ?");
            $stmt->execute([$new_status, $order_id]);
            $_SESSION['message'] = "Order #{$order_id} status updated to '" . htmlspecialchars($new_status) . "'.";
            // Redirect to refresh the page and show updated status
            redirect('view_order.php?id=' . $order_id);
            exit;
        } catch (PDOException $e) {
            $errors[] = "Database error updating order status: " . htmlspecialchars($e->getMessage());
            error_log("Admin Order Status Update DB Error: " . $e->getMessage());
        }
    }
}


// Fetch Order Details
try {
    $stmt = $pdo->prepare("SELECT
                            o.*, -- Select all columns from orders
                            u.first_name,
                            u.last_name,
                            u.email,
                            u.phone_number,
                            u.username -- <<< ADD THIS LINE
                          FROM
                            orders o
                          JOIN
                            users u ON o.user_id = u.user_id
                          WHERE
                            o.order_id = ?");
    $stmt->execute([$order_id]);
    $order_details = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$order_details) {
        $_SESSION['error'] = "Order not found.";
        redirect('orders.php');
        exit;
    }

    // Fetch Order Items
    $stmt_items = $pdo->prepare("SELECT
                                oi.*, -- Select all columns from order_items
                                p.name AS product_name,
                                p.image_url -- Get product name and image from products table
                              FROM
                                order_items oi
                              JOIN
                                products p ON oi.product_id = p.product_id
                              WHERE
                                oi.order_id = ?");
    $stmt_items->execute([$order_id]);
    $order_items = $stmt_items->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    $errors[] = "Error fetching order details: " . htmlspecialchars($e->getMessage());
    error_log("Admin View Order DB Error: " . $e->getMessage());
}

// Prepare customer details for display (handle cases where names might be empty)
$customer_full_name = htmlspecialchars($order_details['first_name'] . ' ' . $order_details['last_name']);
if (trim($customer_full_name) === '') {
    $customer_full_name = htmlspecialchars($order_details['username']);
}
?>

<section class="admin-content">
    <div class="action-bar" style="margin-bottom: 25px;">
        <a href="orders.php" class="button" style="background-color:#6c757d;">&larr; Back to Orders</a>
    </div>

    <h2>Order Details - #<?php echo htmlspecialchars($order_details['order_id']); ?></h2>

    <?php
    // Display session messages (from order updates etc.)
    if (isset($_SESSION['message'])) {
        echo '<div class="session-message success-message"><p>' . htmlspecialchars($_SESSION['message']) . '</p></div>';
        unset($_SESSION['message']);
    }
    // Display errors
    if (!empty($errors)) {
        echo '<div class="error-messages">';
        foreach ($errors as $error) {
            echo '<p>' . htmlspecialchars($error) . '</p>';
        }
        echo '</div>';
    }
    ?>

    <div class="order-detail-grid">
        <div class="order-info-block">
            <h3>Order Information</h3>
            <p><strong>Order ID:</strong> #<?php echo htmlspecialchars($order_details['order_id']); ?></p>
            <p><strong>Order Date:</strong> <?php echo htmlspecialchars($order_details['order_date']); ?></p>
            <p><strong>Total Amount:</strong> $<?php echo htmlspecialchars(number_format($order_details['total_amount'], 2)); ?></p>
            <p><strong>Current Status:</strong> <span class="order-status-<?php echo htmlspecialchars($order_details['status']); ?>"><?php echo ucfirst(htmlspecialchars($order_details['status'])); ?></span></p>

            <h4>Update Status</h4>
            <form action="view_order.php?id=<?php echo htmlspecialchars($order_id); ?>" method="POST" style="display: flex; align-items: center; gap: 10px;">
                <select name="new_status" id="new_status" style="padding: 8px; border-radius: 4px; border: 1px solid #ccc;">
                    <?php
                    $statuses = ['pending', 'processing', 'shipped', 'delivered', 'cancelled'];
                    foreach ($statuses as $status):
                    ?>
                        <option value="<?php echo $status; ?>" <?php echo ($order_details['status'] == $status) ? 'selected' : ''; ?>>
                            <?php echo ucfirst($status); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <button type="submit" name="update_status" class="button edit-button" style="margin: 0;">Update</button>
            </form>
        </div>

        <div class="order-info-block">
            <h3>Customer Information</h3>
            <p><strong>Name:</strong> <?php echo $customer_full_name; ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($order_details['email']); ?></p>
            <p><strong>Phone:</strong> <?php echo htmlspecialchars($order_details['phone_number'] ?: 'N/A'); ?></p>
        </div>

        <div class="order-info-block">
            <h3>Shipping Address</h3>
            <p><?php echo htmlspecialchars($order_details['shipping_address']); ?></p>
            <p><?php echo htmlspecialchars($order_details['city']); ?>, <?php echo htmlspecialchars($order_details['province']); ?> <?php echo htmlspecialchars($order_details['postal_code']); ?></p>
        </div>
    </div>

    <h3>Order Items</h3>
    <?php if (empty($order_items)): ?>
        <p>No items found for this order.</p>
    <?php else: ?>
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Image</th>
                    <th>Quantity</th>
                    <th>Price at Purchase</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($order_items as $item): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($item['product_name']); ?></td>
                        <td>
                            <?php if (!empty($item['image_url'])): ?>
                                <img src="<?php echo htmlspecialchars($item['image_url']); ?>" alt="<?php echo htmlspecialchars($item['product_name']); ?>" style="width: 50px; height: auto;">
                            <?php else: ?>
                                No Image
                            <?php endif; ?>
                        </td>
                        <td><?php echo htmlspecialchars($item['quantity']); ?></td>
                        <td>$<?php echo htmlspecialchars(number_format($item['price_at_purchase'], 2)); ?></td>
                        <td>$<?php echo htmlspecialchars(number_format($item['quantity'] * $item['price_at_purchase'], 2)); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</section>

<?php
include 'includes/admin_footer.php';
?>