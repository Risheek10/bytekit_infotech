<?php
// help/getting_started.php
// This page provides a general introduction and guide for new users on how to start using the ByteKit Infotech website.

session_start();
include_once '../includes/db_connect.php';
include_once '../includes/functions.php';
include '../includes/header.php';
?>

<main class="container">
    <section class="help-content">
        <h2>Getting Started with ByteKit Infotech</h2>
        <p>Welcome to ByteKit Infotech, your one-stop shop for all computer components! This guide will help you get started with using our website.</p>

        <h3>1. Browse Products</h3>
        <p>You can find all our products by clicking on the "Products" link in the navigation bar. Use the search bar or category filters (coming soon!) to find specific items.</p>

        <h3>2. Creating an Account</h3>
        <p>To place orders and track your purchase history, we recommend creating an account. Click "Register" in the navigation bar and fill out the simple form. Your information will be kept secure.</p>

        <h3>3. Logging In</h3>
        <p>If you already have an account, click "Login" in the navigation bar. Enter your username (or email) and password. Once logged in, you can access your cart, order history, and more personalized features.</p>

        <h3>4. Adding to Cart & Checkout</h3>
        <p>Found something you like? Click "Add to Cart" on any product page. You can review your cart by clicking "Cart" in the navigation. When ready to buy, click "Proceed to Checkout" in your cart to finalize your order.</p>

        <p>For more detailed guides, please see the links below:</p>
        <ul>
            <li><a href="how_to_order.php">How to Place an Order</a></li>
            <li><a href="account_management.php">Managing Your Account</a></li>
            <li><a href="faq.php">Frequently Asked Questions</a></li>
        </ul>
    </section>
</main>

<?php
include '../includes/footer.php';
?>