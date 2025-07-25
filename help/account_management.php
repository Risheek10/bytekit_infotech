<?php
// help/account_management.php
// This page guides users on how to manage their account, including registration, login, and profile updates.

session_start();
include_once '../includes/db_connect.php';
include_once '../includes/functions.php';
include '../includes/header.php';
?>

<main class="container">
    <section class="help-content">
        <h2>Managing Your Account</h2>
        <p>Your ByteKit Infotech account allows you to securely manage your orders, personal details, and preferences.</p>

        <h3>1. Registering a New Account</h3>
        <p>To create an account, click the "Register" link in the top navigation bar. Fill in your desired username, email, and a strong password. You can also provide your name for a personalized experience.</p>
        <p>Remember to choose a unique username and email. If you face issues, ensure your chosen username or email is not already in use.</p>

        <h3>2. Logging In to Your Account</h3>
        <p>Access your account by clicking the "Login" link. Enter the username (or email) and password you used during registration. If you forget your password, you can use the "Forgot Password" option (if implemented) on the login page.</p>

        <h3>3. Logging Out</h3>
        <p>To securely log out of your account, simply click the "Logout" link in the top navigation bar. This ends your session and protects your account information.</p>

        <h3>4. Updating Your Profile (Future Feature)</h3>
        <p>Soon, you will be able to update your personal details such as name, address, and phone number directly from your user dashboard once logged in. Stay tuned for this feature!</p>

        <h3>5. Password Security</h3>
        <p>Always choose a strong, unique password for your account. Avoid using easily guessed information. For your security, we store only encrypted versions of your password.</p>
    </section>
</main>

<?php
include '../includes/footer.php';
?>