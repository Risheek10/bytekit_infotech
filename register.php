<?php
// register.php
// This page allows new users to create an account on the ByteKit Infotech website.

session_start();

include 'includes/header.php';     // HTML header and navigation bar.
include 'includes/db_connect.php'; // Connects to the database using the $pdo object.
include 'includes/functions.php';  // Provides useful functions like 'sanitize_input' and 'redirect'.

$errors = [];          // Array to store any validation or processing errors.
$success_message = ''; // Message to show if registration is successful.

// Check if the form was submitted using the POST method.
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = sanitize_input($_POST['username'] ?? '');
    $email = sanitize_input($_POST['email'] ?? '');
    $password = $_POST['password'];         // Password is not sanitized yet, it will be hashed.
    $confirm_password = $_POST['confirm_password'] ?? '';
    $first_name = sanitize_input($_POST['first_name'] ?? '');
    $last_name = sanitize_input($_POST['last_name'] ?? '');

    // Username validation: must not be empty, and follow a specific format.
    if (empty($username)) {
        $errors[] = "Username is required.";
    } elseif (!preg_match("/^[a-zA-Z0-9_]{3,20}$/", $username)) {
        $errors[] = "Username must be 3-20 characters long and can only contain letters, numbers, and underscores.";
    }

    // Email validation: must not be empty, and be a valid email format.
    if (empty($email)) {
        $errors[] = "Email is required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }

    // Password validation: must not be empty, and be at least 6 characters long.
    if (empty($password)) {
        $errors[] = "Password is required.";
    } elseif (strlen($password) < 6) {
        $errors[] = "Password must be at least 6 characters long.";
    }

    // Confirm password validation: must match the password.
    if ($password !== $confirm_password) {
        $errors[] = "Passwords do not match.";
    }

    if (empty($errors)) {
        try {
            // Check if username already exists in the 'users' table.
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE username = ?");
            $stmt->execute([$username]);
            if ($stmt->fetchColumn() > 0) {
                $errors[] = "Username already taken.";
            }

            // Check if email already exists in the 'users' table.
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE email = ?");
            $stmt->execute([$email]);
            if ($stmt->fetchColumn() > 0) {
                $errors[] = "Email already registered.";
            }
        } catch (PDOException $e) {
            // Catch database errors during check and add to errors array.
            $errors[] = "Database error during username/email check: " . htmlspecialchars($e->getMessage());
            error_log("Registration DB check error: " . $e->getMessage()); // Log detailed error.
        }
    }

    if (empty($errors)) {
        $password_hash = password_hash($password, PASSWORD_DEFAULT);

        try {
            // Insert the new user's information into the 'users' table.
            $stmt = $pdo->prepare("INSERT INTO users (username, email, password_hash, first_name, last_name) VALUES (?, ?, ?, ?, ?)");
            // Execute the prepared statement with the sanitized and hashed data.
            if ($stmt->execute([$username, $email, $password_hash, $first_name, $last_name])) {
                $success_message = "Registration successful! You can now log in.";
            } else {
                // If the execution fails for other reasons, add a generic error.
                $errors[] = "Failed to register user. Please try again.";
            }
        } catch (PDOException $e) {
            // Catch database errors during insertion (e.g., connection lost).
            $errors[] = "Database error during registration: " . htmlspecialchars($e->getMessage());
            error_log("Registration DB insert error: " . $e->getMessage()); // Log detailed error.
        }
    }
}
?>

<!-- HTML section for the registration form -->
<section class="auth-form-container">
    <h2>Register for ByteKit Infotech</h2>

    <?php
    // Display any validation errors at the top of the form.
    if (!empty($errors)) {
        echo '<div class="error-messages">';
        foreach ($errors as $error) {
            echo '<p>' . htmlspecialchars($error) . '</p>';
        }
        echo '</div>';
    }

    // Display the success message if registration was completed.
    if ($success_message) {
        echo '<div class="success-message">';
        echo '<p>' . htmlspecialchars($success_message) . '</p>';
        echo '<p><a href="login.php">Click here to Login</a></p>'; // Link to login page.
        echo '</div>';
    }
    ?>

    <!-- The Registration Form -->
    <form action="register.php" method="POST" class="auth-form">
        <!-- Form field for Username -->
        <div class="form-group">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>" required>
        </div>
        <!-- Form field for Email -->
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>" required>
        </div>
        <!-- Form field for Password -->
        <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
        </div>
        <!-- Form field to Confirm Password -->
        <div class="form-group">
            <label for="confirm_password">Confirm Password:</label>
            <input type="password" id="confirm_password" name="confirm_password" required>
        </div>
        <!-- Optional form field for First Name -->
        <div class="form-group">
            <label for="first_name">First Name (Optional):</label>
            <input type="text" id="first_name" name="first_name" value="<?php echo htmlspecialchars($_POST['first_name'] ?? ''); ?>">
        </div>
        <!-- Optional form field for Last Name -->
        <div class="form-group">
            <label for="last_name">Last Name (Optional):</label>
            <input type="text" id="last_name" name="last_name" value="<?php echo htmlspecialchars($_POST['last_name'] ?? ''); ?>">
        </div>
        <!-- Submit button for the form -->
        <button type="submit" class="button">Register</button>
    </form>

    <!-- Link for users who already have an account -->
    <p class="form-link">Already have an account? <a href="login.php">Login here</a></p>
</section>

<?php
// Include the footer part of the website.
include 'includes/footer.php';
?>
