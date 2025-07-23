<?php
// bytekit_infotech/admin/edit_user.php

include 'includes/admin_header.php'; // Handles session, access control, layout

$errors = [];
$user_data = []; // To hold user data retrieved from DB

// 1. Get user ID from URL
$user_id = filter_var($_GET['id'] ?? null, FILTER_VALIDATE_INT);

// Redirect if no valid user ID is provided
if (!$user_id) {
    $_SESSION['error'] = "No user ID specified for editing.";
    redirect('users.php');
    exit;
}

// Prevent an admin from demoting themselves or deleting themselves
// This is a basic check. More robust logic might be needed for multi-admin setups.
if ($user_id == $_SESSION['user_id'] && $_SESSION['user_type'] === 'admin') {
    $_SESSION['error'] = "You cannot edit your own user type or critical account details this way. Please contact another admin or use a dedicated profile page.";
    redirect('users.php');
    exit;
}

// 2. Fetch user data from database to pre-fill the form
try {
    $stmt = $pdo->prepare("SELECT user_id, username, email, first_name, last_name, phone_number, user_type FROM users WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $user_data = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user_data) {
        $_SESSION['error'] = "User not found.";
        redirect('users.php');
        exit;
    }
} catch (PDOException $e) {
    $_SESSION['error'] = "Database error fetching user: " . htmlspecialchars($e->getMessage());
    error_log("Admin Edit User Fetch DB Error: " . $e->getMessage());
    redirect('users.php');
    exit;
}

// 3. Handle form submission (when data is POSTed)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 3a. Sanitize and retrieve form data
    $email = sanitize_input($_POST['email'] ?? '');
    $first_name = sanitize_input($_POST['first_name'] ?? '');
    $last_name = sanitize_input($_POST['last_name'] ?? '');
    $phone_number = sanitize_input($_POST['phone_number'] ?? '');
    $user_type = sanitize_input($_POST['user_type'] ?? 'customer'); // Default to 'customer' if not set

    // 3b. Validate input
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "A valid Email is required.";
    }

    // Validate user_type
    $allowed_user_types = ['customer', 'admin'];
    if (!in_array($user_type, $allowed_user_types)) {
        $errors[] = "Invalid user type selected.";
        $user_type = $user_data['user_type']; // Revert to original type to prevent bad data
    }

    // Check for duplicate email (excluding the current user's email)
    try {
        $stmt_email_check = $pdo->prepare("SELECT COUNT(*) FROM users WHERE email = ? AND user_id != ?");
        $stmt_email_check->execute([$email, $user_id]);
        if ($stmt_email_check->fetchColumn() > 0) {
            $errors[] = "Email address is already in use by another user.";
        }
    } catch (PDOException $e) {
        $errors[] = "Database error checking email: " . htmlspecialchars($e->getMessage());
        error_log("Admin Edit User Email Check DB Error: " . $e->getMessage());
    }

    // 3c. If no validation errors, proceed with UPDATE
    if (empty($errors)) {
        try {
            $stmt = $pdo->prepare("UPDATE users SET email = ?, first_name = ?, last_name = ?, phone_number = ?, user_type = ?, updated_at = CURRENT_TIMESTAMP WHERE user_id = ?");
            $stmt->execute([$email, $first_name, $last_name, $phone_number, $user_type, $user_id]);

            $_SESSION['message'] = "User '" . htmlspecialchars($user_data['username']) . "' (ID: " . $user_id . ") updated successfully!";
            redirect('users.php'); // Redirect back to user list
            exit;

        } catch (PDOException $e) {
            $errors[] = "Database error: Could not update user. " . htmlspecialchars($e->getMessage());
            error_log("Admin Edit User Update DB Error: " . $e->getMessage());
        }
    }
    // If there were POST errors, user_data needs to be updated with POST values
    // so the form re-displays with user's attempted changes (good UX)
    $user_data = array_merge($user_data, $_POST);
}
?>

<section class="admin-content">
    <h2>Edit User: <?php echo htmlspecialchars($user_data['username'] ?? 'N/A'); ?> (ID: <?php echo htmlspecialchars($user_id); ?>)</h2>

    <?php
    // Display validation errors
    if (!empty($errors)) {
        echo '<div class="error-messages">';
        foreach ($errors as $error) {
            echo '<p>' . htmlspecialchars($error) . '</p>';
        }
        echo '</div>';
    }
    ?>

    <form action="edit_user.php?id=<?php echo htmlspecialchars($user_id); ?>" method="POST" class="auth-form">
        <div class="form-group">
            <label for="username">Username (Cannot be changed):</label>
            <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($user_data['username'] ?? ''); ?>" disabled>
        </div>
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user_data['email'] ?? ''); ?>" required>
        </div>
        <div class="form-group">
            <label for="first_name">First Name:</label>
            <input type="text" id="first_name" name="first_name" value="<?php echo htmlspecialchars($user_data['first_name'] ?? ''); ?>">
        </div>
        <div class="form-group">
            <label for="last_name">Last Name:</label>
            <input type="text" id="last_name" name="last_name" value="<?php echo htmlspecialchars($user_data['last_name'] ?? ''); ?>">
        </div>
        <div class="form-group">
            <label for="phone_number">Phone Number (Optional):</label>
            <input type="tel" id="phone_number" name="phone_number" value="<?php echo htmlspecialchars($user_data['phone_number'] ?? ''); ?>">
        </div>
        
        <div class="form-group">
            <label for="user_type">User Type:</label>
            <select id="user_type" name="user_type" required>
                <option value="customer" <?php echo (($user_data['user_type'] ?? '') == 'customer') ? 'selected' : ''; ?>>Customer</option>
                <option value="admin" <?php echo (($user_data['user_type'] ?? '') == 'admin') ? 'selected' : ''; ?>>Admin</option>
            </select>
        </div>

        <button type="submit" class="button">Update User</button>
        <a href="users.php" class="button" style="background-color:#6c757d; margin-left: 10px;">Cancel</a>
    </form>
</section>

<?php
include 'includes/admin_footer.php';
?>