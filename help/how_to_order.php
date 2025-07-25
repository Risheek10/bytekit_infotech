<?php
// help/how_to_order.php
// This page provides a step-by-step guide for users on how to place an order on the ByteKit Infotech website.

session_start();
include_once '../includes/db_connect.php';
include_once '../includes/functions.php';
include '../includes/header.php';
?>

<main class="container">
    <section class="help-content">
        <h2>How to Place an Order</h2>
        <p>Follow these simple steps to successfully place your order on ByteKit Infotech:</p>

        <h3>Step 1: Find Your Products</h3>
        <p>Browse our extensive catalogue using the "Products" link. You can use the search bar to find specific items or explore different categories. Click "View Details" to learn more about a product.</p>

        <h3>Step 2: Add to Cart</h3>
        <p>Once you've decided on a product, click the "Add to Cart" button. You can adjust the quantity directly on the product detail page or later in your cart.</p>

        <h3>Step 3: Review Your Cart</h3>
        <p>Click the "Cart" icon in the navigation bar to review all the items you've added. Here you can change quantities, remove items, or continue shopping.</p>

        <h3>Step 4: Proceed to Checkout</h3>
        <p>When you are satisfied with your cart, click the "Proceed to Checkout" button. You will be asked to log in if you haven't already.</p>

        <h3>Step 5: Provide Shipping Information</h3>
        <p>On the checkout page, review your order summary. Fill in your shipping address details. If you're a returning customer, these might be pre-filled.</p>

        <h3>Step 6: Choose Payment Method & Place Order</h3>
        <p>Select your preferred payment method (e.g., Cash on Delivery). Review everything one last time, then click "Place Order."</p>

        <h3>Step 7: Order Confirmation</h3>
        <p>You will receive an order confirmation page with your unique order ID. An email confirmation will also be sent (in a full system).</p>

        <p>If you have any issues, please visit our <a href="faq.php">FAQ</a> or <a href="../contact.php">Contact Us</a> page.</p>
    </section>
</main>

<?php
include '../includes/footer.php';
?>