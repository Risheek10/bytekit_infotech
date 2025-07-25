<?php
// This page displays the current contents of the user's shopping cart, showing selected items, quantities, and the total price.

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include_once 'includes/functions.php'; // For sanitize_input(), etc.
include_once 'includes/db_connect.php'; // For potential future needs (e.g., re-fetching fresh product data)
include 'includes/header.php'; // HTML header and navigation

$cart = $_SESSION['cart'] ?? []; // Get cart from session, default to empty array if not set
$cart_total = 0; // Initialize cart total
?>

<section class="cart-container">
    <h2>Your Shopping Cart</h2>

    <?php if (empty($cart)): ?>
        <div class="empty-cart-message">
            <p>Your cart is empty. Start shopping now!</p>
            <p><a href="products.php" class="button">Browse Products</a></p>
        </div>
    <?php else: ?>
        <form action="update_cart.php" method="POST" class="cart-form">
            <table class="cart-table">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Subtotal</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($cart as $product_id => $item):
                        // Calculate subtotal for current item
                        $item_subtotal = $item['price'] * $item['quantity'];
                        $cart_total += $item_subtotal; // Add to grand total
                    ?>
                        <tr>
                            <td>
                                <?php echo htmlspecialchars($item['name']); ?>
                            </td>
                            <td>$<?php echo htmlspecialchars(number_format($item['price'], 2)); ?></td>
                            <td>
                                <input type="number" name="quantity[<?php echo htmlspecialchars($product_id); ?>]" 
                                       value="<?php echo htmlspecialchars($item['quantity']); ?>" 
                                       min="1" class="cart-quantity-input">
                            </td>
                            <td>$<?php echo htmlspecialchars(number_format($item_subtotal, 2)); ?></td>
                            <td>
                                <button type="submit" name="remove_item" value="<?php echo htmlspecialchars($product_id); ?>" class="button remove-item-button">Remove</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <div class="cart-summary">
                <h3>Cart Total: <span>$<?php echo htmlspecialchars(number_format($cart_total, 2)); ?></span></h3>
                <div class="cart-actions">
                    <button type="submit" name="update_cart" class="button update-cart-button">Update Cart</button>
                    <a href="checkout.php" class="button checkout-button">Proceed to Checkout</a>
                </div>
            </div>
        </form>
    <?php endif; ?>
</section>

<?php
include 'includes/footer.php'; // HTML footer
?>