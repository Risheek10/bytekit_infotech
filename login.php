<?php
// bytekit_infotech/login.php

// Start the session at the very beginning of the script
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Include necessary files
include 'includes/header.php'; // HTML header and navigation
include 'includes/db_connect.php'; // Database connection ($pdo object)
include 'includes/functions.php'; // Custom functions like sanitize_input(), redirect()

$errors = []; // Array to store validation/login errors
$success_message = ''; // To display success message

// Redirect if already logged in
if (is_logged_in()) {
    redirect('index.php'); // Or to a user dashboard page if you create one
}

// Check if a success message from registration was passed
if (isset($_GET['registered']) && $_GET['registered'] == 'true') {
    $success_message = "Registration successful! Please log in.";
}

// Check if the form was submitted (HTTP POST request)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 1. Sanitize and retrieve form data
    $username_email = sanitize_input($_POST['username_email']); // Can be username or email
    $password = $_POST['password']; // Do NOT sanitize password, will be verified

    // 2. Validate input
    if (empty($username_email)) {
        $errors[] = "Username or Email is required.";
    }
    if (empty($password)) {
        $errors[] = "Password is required.";
    }

    // 3. Attempt to authenticate user if no initial errors
    if (empty($errors)) {
        try {
            // Check if input is an email or username
            if (filter_var($username_email, FILTER_VALIDATE_EMAIL)) {
                $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
            } else {
                $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
            }
            $stmt->execute([$username_email]);
            $user = $stmt->fetch(); // Fetch the user record

            // Check if user exists and password is correct
            if ($user && password_verify($password, $user['password_hash'])) {
                // Login successful!
                $success_message = "Login successful! Welcome, " . htmlspecialchars($user['first_name'] ?: $user['username']) . "!";

                // Set session variables
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['first_name'] = $user['first_name'];
                $_SESSION['user_type'] = $user['user_type']; // Important for admin checks later

                // Update last_login timestamp in DB
                $update_stmt = $pdo->prepare("UPDATE users SET last_login = CURRENT_TIMESTAMP WHERE user_id = ?");
                $update_stmt->execute([$user['user_id']]);

                // Redirect to homepage or dashboard after a short delay or directly
                $redirect_page = $_GET['redirect'] ?? '';

                if ($_SESSION['user_type'] === 'admin') {
                    redirect('/bytekit_infotech/admin/index.php'); // Redirect admins to admin dashboard
                } elseif (!empty($redirect_page)) {
                    // Redirect to the originally requested page if user was redirected here for login
                    redirect('/bytekit_infotech/' . $redirect_page);
                } else {
                    redirect('index.php'); // Redirect regular customers to the homepage
                }

            } else {
                $errors[] = "Invalid username/email or password.";
            }

        } catch (PDOException $e) {
            $errors[] = "Database error during login: " . $e->getMessage();
            error_log("Login DB error: " . $e->getMessage());
        }
    }
}
?>

<div class="video-background">
    <video autoplay muted loop id="bg-video">
        <source src="images/bg.mp4" type="video/mp4">
        Your browser does not support the video tag.
    </video>
    <div class="video-overlay"></div> </div>

<div class="auth-wrapper">
    <div class="auth-form-container">
        <?php
        // Display validation/login errors (these should always be visible, not hidden by hover)
        if (!empty($errors)) {
            echo '<div class="error-messages">';
            foreach ($errors as $error) {
                echo '<p>' . htmlspecialchars($error) . '</p>';
            }
            echo '</div>';
        }

        // Display success message (e.g., from successful registration)
        if ($success_message) {
            echo '<div class="success-message">';
            echo '<p>' . htmlspecialchars($success_message) . '</p>';
            echo '</div>';
        }
        ?>

        <div class="initial-state">
            <h2>Login</h2>
        </div>
        <form action="login.php" method="POST" class="auth-form">
            <div class="form-group">
                <label for="username_email">Username or Email:</label>
                <input type="text" id="username_email" name="username_email" value="<?php echo htmlspecialchars($_POST['username_email'] ?? ''); ?>" required autofocus>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit" class="button">Login</button>
            <p class="form-link">Don't have an account? <a href="register.php">Register here</a></p>
        </form>
    </div>
</div>

<?php
include 'includes/footer.php'; // HTML footer
?>