<?php
// bytekit_infotech/admin/delete_user.php

include 'includes/admin_header.php'; // Handles session, access control, layout

// Ensure it's a POST request for security
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    $_SESSION['error'] = "Invalid request method.";
    redirect('users.php');
    exit;
}

$user_id_to_delete = filter_var($_POST['user_id'] ?? null, FILTER_VALIDATE_INT);

// Validate user ID
if (!$user_id_to_delete) {
    $_SESSION['error'] = "No user ID specified for deletion.";
    redirect('users.php');
    exit;
}

// CRITICAL SECURITY CHECK: Prevent admin from deleting their own account
if ($user_id_to_delete == $_SESSION['user_id']) {
    $_SESSION['error'] = "You cannot delete your own user account!";
    redirect('users.php');
    exit;
}

try {
    // Optional: Get username before deleting for the success message
    $stmt_name = $pdo->prepare("SELECT username FROM users WHERE user_id = ?");
    $stmt_name->execute([$user_id_to_delete]);
    $user_name_result = $stmt_name->fetch(PDO::FETCH_ASSOC);
    $user_name = $user_name_result ? htmlspecialchars($user_name_result['username']) : 'Unknown User';

    // Before deleting the user, consider data integrity for related tables (e.g., orders, cart)
    // For simplicity in this exercise, we're directly deleting the user.
    // In a real application, you might:
    // 1. Soft delete (set an 'is_active' flag)
    // 2. Reassign their orders to a 'guest' user.
    // 3. Implement CASCADE DELETE on foreign keys (use with extreme caution).

    // Prepare and execute the DELETE statement
    $stmt_delete = $pdo->prepare("DELETE FROM users WHERE user_id = ?");
    $stmt_delete->execute([$user_id_to_delete]);

    // Check if any row was affected
    if ($stmt_delete->rowCount() > 0) {
        $_SESSION['message'] = "User '" . $user_name . "' (ID: " . $user_id_to_delete . ") has been deleted successfully.";
    } else {
        $_SESSION['error'] = "User with ID " . $user_id_to_delete . " not found or could not be deleted.";
    }

} catch (PDOException $e) {
    // Catch database errors
    $_SESSION['error'] = "Database error: Could not delete user. " . htmlspecialchars($e->getMessage());
    error_log("Admin Delete User DB Error: " . $e->getMessage());
}

// Redirect back to the user listing page
redirect('users.php');
exit;
?>