<?php
// This page allows new users to create an account on the ByteKit Infotech website. It handles form submission, input validation, and user creation.

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include 'includes/header.php'; // HTML header and navigation
include 'includes/db_connect.php'; // Database connection ($pdo object)
include 'includes/functions.php'; // Custom functions like sanitize_input(), redirect()

$errors = []; // Array to store validation errors
$success_message = ''; // To display success message

// Check if the form was submitted (HTTP POST request)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = sanitize_input($_POST['username']);
    $email = sanitize_input($_POST['email']);
    $password = $_POST['password']; 
    $confirm_password = $_POST['confirm_password'];
    $first_name = sanitize_input($_POST['first_name']);
    $last_name = sanitize_input($_POST['last_name']);

    if (empty($username)) {
        $errors[] = "Username is required.";
    } elseif (!preg_match("/^[a-zA-Z0-9_]{3,20}$/", $username)) {
        $errors[] = "Username must be 3-20 characters long and can only contain letters, numbers, and underscores.";
    }

    if (empty($email)) {
        $errors[] = "Email is required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }

    if (empty($password)) {
        $errors[] = "Password is required.";
    } elseif (strlen($password) < 6) {
        $errors[] = "Password must be at least 6 characters long.";
    }

    if ($password !== $confirm_password) {
        $errors[] = "Passwords do not match.";
    }

    if (empty($errors)) {
        try {
            // Check if username already exists
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE username = ?");
            $stmt->execute([$username]);
            if ($stmt->fetchColumn() > 0) {
                $errors[] = "Username already taken.";
            }

            // Check if email already exists
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE email = ?");
            $stmt->execute([$email]);
            if ($stmt->fetchColumn() > 0) {
                $errors[] = "Email already registered.";
            }
        } catch (PDOException $e) {
            $errors[] = "Database error during username/email check: " . $e->getMessage();
            error_log("Registration DB check error: " . $e->getMessage());
        }
    }

    if (empty($errors)) {
        // Hash the password
        $password_hash = password_hash($password, PASSWORD_DEFAULT);

        try {
            // Insert user into the database
            $stmt = $pdo->prepare("INSERT INTO users (username, email, password_hash, first_name, last_name) VALUES (?, ?, ?, ?, ?)");
            if ($stmt->execute([$username, $email, $password_hash, $first_name, $last_name])) {
                $success_message = "Registration successful! You can now log in.";
                // redirect('login.php?registered=true');
            } else {
                $errors[] = "Failed to register user. Please try again.";
            }
        } catch (PDOException $e) {
            $errors[] = "Database error during registration: " . $e->getMessage();
            error_log("Registration DB insert error: " . $e->getMessage());
        }
    }
}
?>

<section class="auth-form-container">
    <h2>Register for ByteKit Infotech</h2>

    <?php
    // Display validation errors
    if (!empty($errors)) {
        echo '<div class="error-messages">';
        foreach ($errors as $error) {
            echo '<p>' . htmlspecialchars($error) . '</p>';
        }
        echo '</div>';
    }

    // Display success message
    if ($success_message) {
        echo '<div class="success-message">';
        echo '<p>' . htmlspecialchars($success_message) . '</p>';
        echo '<p><a href="login.php">Click here to Login</a></p>';
        echo '</div>';
    }
    ?>

    <form action="register.php" method="POST" class="auth-form">
        <div class="form-group">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>" required>
        </div>
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>" required>
        </div>
        <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
        </div>
        <div class="form-group">
            <label for="confirm_password">Confirm Password:</label>
            <input type="password" id="confirm_password" name="confirm_password" required>
        </div>
        <div class="form-group">
            <label for="first_name">First Name (Optional):</label>
            <input type="text" id="first_name" name="first_name" value="<?php echo htmlspecialchars($_POST['first_name'] ?? ''); ?>">
        </div>
        <div class="form-group">
            <label for="last_name">Last Name (Optional):</label>
            <input type="text" id="last_name" name="last_name" value="<?php echo htmlspecialchars($_POST['last_name'] ?? ''); ?>">
        </div>
        <button type="submit" class="button">Register</button>
    </form>

    <p class="form-link">Already have an account? <a href="login.php">Login here</a></p>
</section>

<?php
include 'includes/footer.php'; // HTML footer
?>