<?php
// bytekit_infotech/admin/users.php

include 'includes/admin_header.php'; // Handles session, access control, layout

$users = [];
$error_message = '';

try {
    // Fetch all users
    $stmt = $pdo->query("SELECT user_id, username, email, first_name, last_name, user_type, registration_date, last_login FROM users ORDER BY registration_date DESC");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error_message = 'Error fetching users: ' . htmlspecialchars($e->getMessage());
    error_log("Admin Users Page DB Error: " . $e->getMessage());
}
?>

<section class="admin-content">
    <h2>Manage Users</h2>

    <?php
    // Display session messages (e.g., from user edits/deletions in the future)
    if (isset($_SESSION['message'])) {
        echo '<div class="session-message success-message"><p>' . htmlspecialchars($_SESSION['message']) . '</p></div>';
        unset($_SESSION['message']);
    }
    if (isset($_SESSION['error'])) {
        echo '<div class="session-message error-message"><p>' . htmlspecialchars($_SESSION['error']) . '</p></div>';
        unset($_SESSION['error']);
    }
    if ($error_message) {
        echo '<div class="error-messages"><p>' . $error_message . '</p></div>';
    }
    ?>

    <?php if (empty($users)): ?>
        <p>No users found in the database yet.</p>
    <?php else: ?>
        <table class="admin-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Full Name</th>
                    <th>User Type</th>
                    <th>Registered On</th>
                    <th>Last Login</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($user['user_id']); ?></td>
                        <td><?php echo htmlspecialchars($user['username']); ?></td>
                        <td><?php echo htmlspecialchars($user['email']); ?></td>
                        <td>
                            <?php 
                            $fullName = htmlspecialchars($user['first_name'] . ' ' . $user['last_name']);
                            echo trim($fullName) !== '' ? $fullName : 'N/A'; // Display N/A if names are empty
                            ?>
                        </td>
                        <td><span class="user-type-<?php echo htmlspecialchars($user['user_type']); ?>"><?php echo ucfirst(htmlspecialchars($user['user_type'])); ?></span></td>
                        <td><?php echo htmlspecialchars($user['registration_date']); ?></td>
                        <td><?php echo htmlspecialchars($user['last_login'] ?? 'Never'); ?></td>
                        <td class="admin-actions">
                            <a href="edit_user.php?id=<?php echo htmlspecialchars($user['user_id']); ?>" class="button edit-button">Edit</a>
                            <form action="delete_user.php" method="POST" style="display:inline-block;" onsubmit="return confirm('Are you sure you want to delete user <?php echo htmlspecialchars($user['username']); ?> (ID: <?php echo htmlspecialchars($user['user_id']); ?>)? This action cannot be undone.');">
                                <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($user['user_id']); ?>">
                                <button type="submit" class="button delete-button">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</section>

<?php
include 'includes/admin_footer.php';
?>