<?php
// help/faq.php
// This page lists frequently asked questions (FAQ) and their answers to help users quickly find solutions.

session_start();
include_once '../includes/db_connect.php';
include_once '../includes/functions.php';
include '../includes/header.php';
?>

<main class="container">
    <section class="help-content">
        <h2>Frequently Asked Questions (FAQ)</h2>
        <div class="faq-list">
            <div class="faq-item">
                <h3>Q: How do I create an account?</h3>
                <p>A: Click the "Register" link in the navigation bar and follow the instructions. It only takes a minute!</p>
            </div>
            <div class="faq-item">
                <h3>Q: Can I order without an account?</h3>
                <p>A: Currently, an account is required to place an order, as it helps us track your purchases and provide support.</p>
            </div>
            <div class="faq-item">
                <h3>Q: How do I track my order?</h3>
                <p>A: Once logged in, visit the "My Orders" section from the navigation bar. You can view the status of all your past orders there.</p>
            </div>
            <div class="faq-item">
                <h3>Q: What payment methods do you accept?</h3>
                <p>A: Currently, we only accept "Cash on Delivery" (COD). We plan to add more payment options in the future.</p>
            </div>
            <div class="faq-item">
                <h3>Q: How can I change my shipping address?</h3>
                <p>A: Your default shipping address is stored in your profile. During checkout, you will have the option to update it for that specific order. For permanent changes to your default address, please visit your user profile page (feature coming soon).</p>
            </div>
            <div class="faq-item">
                <h3>Q: What if I receive a damaged product?</h3>
                <p>A: Please contact our customer support immediately via the "Contact Us" page. Provide your order details and a description of the damage, and we will assist you with a return or replacement.</p>
            </div>
        </div>
    </section>
</main>

<?php
include '../includes/footer.php';
?>