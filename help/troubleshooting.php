<?php
// help/troubleshooting.php
// This page provides solutions to common problems users might encounter while using the ByteKit Infotech website.

session_start();
include_once '../includes/db_connect.php';
include_once '../includes/functions.php';
include '../includes/header.php';
?>

<main class="container">
    <section class="help-content">
        <h2>Troubleshooting Common Issues</h2>
        <p>Here are some solutions to problems you might face while using ByteKit Infotech:</p>

        <h3>1. Cannot Log In</h3>
        <ul>
            <li><strong>Check Username/Email and Password:</strong> Make sure you are typing your username (or registered email) and password correctly. Passwords are case-sensitive.</li>
            <li><strong>Reset Password:</strong> If you've forgotten your password, look for a "Forgot Password" link on the login page (feature to be implemented later).</li>
            <li><strong>Account Disabled:</strong> If your account has been disabled (e.g., by an administrator), please contact customer support.</li>
        </ul>

        <h3>2. Products Not Adding to Cart</h3>
        <ul>
            <li><strong>Browser Cookies:</strong> Ensure your browser has cookies enabled, as the shopping cart relies on session cookies.</li>
            <li><strong>Out of Stock:</strong> Check if the product is out of stock. Our system will prevent adding items beyond available quantity.</li>
        </ul>

        <h3>3. Website Looks Strange / Missing Styles</h3>
        <ul>
            <li><strong>Clear Browser Cache:</strong> Sometimes your browser loads old versions of our website files. Try clearing your browser's cache (Ctrl+Shift+R or Cmd+Shift+R for a hard refresh).</li>
            <li><strong>Check Internet Connection:</strong> A slow or unstable internet connection might prevent some parts of the website from loading correctly.</li>
        </ul>

        <h3>4. Order Placement Issues</h3>
        <ul>
            <li><strong>Required Fields:</strong> Ensure all required fields (like shipping address details) are filled correctly on the checkout page.</li>
            <li><strong>Cart Empty:</strong> You cannot place an order if your cart is empty.</li>
        </ul>

        <h3>Still Having Trouble?</h3>
        <p>If these solutions don't help, please don't hesitate to <a href="../contact.php">Contact Us</a> directly. Provide as much detail as possible about the issue you're facing.</p>
    </section>
</main>

<?php
include '../includes/footer.php';
?>