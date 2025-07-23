<?php
// bytekit_infotech/checkout.php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include_once 'includes/functions.php';
include_once 'includes/db_connect.php'; // For database interaction
include 'includes/header.php';

// Redirect if not logged in
if (!is_logged_in()) {
    $_SESSION['message'] = "Please log in to proceed to checkout.";
    redirect('login.php?redirect=checkout.php'); // Pass redirect parameter
}

// Redirect if cart is empty
$cart = $_SESSION['cart'] ?? [];
if (empty($cart)) {
    $_SESSION['message'] = "Your cart is empty. Please add items before checking out.";
    redirect('products.php');
}

$errors = [];
$success_message = '';
$cart_total = 0;

// Calculate cart total for display
foreach ($cart as $product_id => $item) {
    $cart_total += $item['price'] * $item['quantity'];
}

// Fetch user's existing address details for pre-filling the form (optional but good UX)
$user_id = $_SESSION['user_id'];
$user_details = [];
try {
    $stmt = $pdo->prepare("SELECT first_name, last_name, email, address, city, province, postal_code, phone_number FROM users WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $user_details = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("Error fetching user details for checkout: " . $e->getMessage());
    // Continue without pre-filled details if there's a DB error
}


// Handle form submission (Placing the Order)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 1. Sanitize and validate input
    $shipping_address = sanitize_input($_POST['shipping_address']);
    $city = sanitize_input($_POST['city']);
    $province = sanitize_input($_POST['province']);
    $postal_code = sanitize_input($_POST['postal_code']);
    // You might add phone_number, full_name from form if not using session user_details

    if (empty($shipping_address)) $errors[] = "Shipping address is required.";
    if (empty($city)) $errors[] = "City is required.";
    if (empty($province)) $errors[] = "Province is required.";
    if (empty($postal_code)) $errors[] = "Postal Code is required.";

    // Basic regex for Canadian postal code (e.g., A1A 1A1)
    if (!preg_match("/^[A-Za-z]\d[A-Za-z][ -]?\d[A-Za-z]\d$/", $postal_code)) {
        $errors[] = "Invalid postal code format (e.g., A1A 1A1).";
    }

    // 2. Process order if no validation errors
    if (empty($errors)) {
        try {
            $pdo->beginTransaction(); // Start a transaction for atomicity

            // Insert into 'orders' table
            $stmt = $pdo->prepare("INSERT INTO orders (user_id, total_amount, shipping_address, city, province, postal_code, status) VALUES (?, ?, ?, ?, ?, ?, 'pending')");
            $stmt->execute([
                $_SESSION['user_id'],
                $cart_total,
                $shipping_address,
                $city,
                $province,
                $postal_code
            ]);
            $order_id = $pdo->lastInsertId(); // Get the ID of the newly inserted order

            // Insert into 'order_items' table for each item in the cart
            foreach ($cart as $product_id => $item) {
                $stmt = $pdo->prepare("INSERT INTO order_items (order_id, product_id, quantity, price_at_purchase) VALUES (?, ?, ?, ?)");
                $stmt->execute([
                    $order_id,
                    $item['id'], // Use the product ID stored in the cart item
                    $item['quantity'],
                    $item['price'] // Use the price stored in the cart item (price at time of add)
                ]);
            }

            // Commit the transaction
            $pdo->commit();

            // Clear the cart after successful order placement
            unset($_SESSION['cart']);
            $_SESSION['message'] = "Your order (#" . $order_id . ") has been placed successfully!";
            redirect('order_confirmation.php?order_id=' . $order_id); // Redirect to confirmation page

        } catch (PDOException $e) {
            $pdo->rollBack(); // Rollback transaction on error
            $errors[] = "Failed to place order due to a database error. Please try again.";
            error_log("Order placement error: " . $e->getMessage());
        }
    }
}
?>

<section class="checkout-container">
    <h2>Checkout</h2>

    <?php
    if (!empty($errors)) {
        echo '<div class="error-messages">';
        foreach ($errors as $error) {
            echo '<p>' . htmlspecialchars($error) . '</p>';
        }
        echo '</div>';
    }
    if ($success_message) {
        echo '<div class="success-message">';
        echo '<p>' . htmlspecialchars($success_message) . '</p>';
        echo '</div>';
    }
    ?>

    <div class="checkout-summary">
        <h3>Order Summary</h3>
        <table class="cart-table">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($cart as $product_id => $item): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($item['name']); ?></td>
                        <td><?php echo htmlspecialchars($item['quantity']); ?></td>
                        <td>$<?php echo htmlspecialchars(number_format($item['price'], 2)); ?></td>
                        <td>$<?php echo htmlspecialchars(number_format($item['price'] * $item['quantity'], 2)); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3" class="text-right"><strong>Grand Total:</strong></td>
                    <td><strong>$<?php echo htmlspecialchars(number_format($cart_total, 2)); ?></strong></td>
                </tr>
            </tfoot>
        </table>
    </div>

    <div class="shipping-details">
        <h3>Shipping Details</h3>
        <form action="checkout.php" method="POST" class="auth-form">
            <div class="form-group">
                <label for="shipping_address">Shipping Address:</label>
                <input type="text" id="shipping_address" name="shipping_address" 
                       value="<?php echo htmlspecialchars($_POST['shipping_address'] ?? $user_details['address'] ?? ''); ?>" required>
            </div>
            <div class="form-group">
                <label for="city">City:</label>
                <input type="text" id="city" name="city" 
                       value="<?php echo htmlspecialchars($_POST['city'] ?? $user_details['city'] ?? ''); ?>" required>
            </div>
            <div class="form-group">
                <label for="province">Province:</label>
                <input type="text" id="province" name="province" 
                       value="<?php echo htmlspecialchars($_POST['province'] ?? $user_details['province'] ?? ''); ?>" required>
            </div>
            <div class="form-group">
                <label for="postal_code">Postal Code:</label>
                <input type="text" id="postal_code" name="postal_code" 
                       value="<?php echo htmlspecialchars($_POST['postal_code'] ?? $user_details['postal_code'] ?? ''); ?>" required>
            </div>
            
            <button type="submit" class="button checkout-place-order">Place Order</button>
        </form>
    </div>
</section>

<?php
include 'includes/footer.php';
?>