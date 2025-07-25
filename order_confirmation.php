<?php
// This page confirms to the user that their order has been successfully placed, providing an order ID.

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include_once 'includes/functions.php';
include 'includes/header.php';

$order_id = $_GET['order_id'] ?? 'N/A';
?>

<section class="confirmation-container">
    <h2>Order Placed Successfully!</h2>
    <p>Thank you for your purchase from ByteKit Infotech.</p>
    <p>Your order number is: <strong>#<?php echo htmlspecialchars($order_id); ?></strong></p>
    <p>You will receive an email confirmation shortly (in a real system).</p>
    <div class="confirmation-actions">
        <a href="products.php" class="button">Continue Shopping</a>
        </div>
</section>

<?php
include 'includes/footer.php';
?>