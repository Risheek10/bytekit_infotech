<?php
// This is the main dashboard for the ByteKit Infotech admin panel. It provides an overview and links to various management sections.

// Include the admin header (which contains access control and starts the session)
include 'includes/admin_header.php';
?>

<section class="admin-dashboard">
    <h2>Welcome to the Admin Dashboard, <?php echo htmlspecialchars($_SESSION['first_name'] ?? $_SESSION['username']); ?>!</h2>
    <p>Manage your ByteKit Infotech store from here.</p>

    <div class="admin-dashboard-links">
        <div class="admin-dashboard-link-item">
            <h3>Manage Products</h3>
            <p>Add, edit, or delete computer parts.</p>
            <a href="products.php" class="button">Go to Products</a>
        </div>
        <div class="admin-dashboard-link-item">
            <h3>Manage Categories</h3>
            <p>Organize products into categories.</p>
            <a href="categories.php" class="button">Go to Categories</a>
        </div>
        <div class="admin-dashboard-link-item">
            <h3>View Orders</h3>
            <p>Review customer orders and update their status.</p>
            <a href="orders.php" class="button">Go to Orders</a>
        </div>
        <div class="admin-dashboard-link-item">
            <h3>Manage Users</h3>
            <p>View and manage user accounts.</p>
            <a href="users.php" class="button">Go to Users</a>
        </div>
    </div>
</section>

<?php
// Include the admin footer
include 'includes/admin_footer.php';
?>