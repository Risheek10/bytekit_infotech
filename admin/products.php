<?php
// This page in the admin panel allows administrators to view a list of all products in the store for management purposes.

include 'includes/admin_header.php';

// Fetch all products from the database
$products = [];
try {
   $stmt = $pdo->query("SELECT
                            p.product_id,
                            p.name,
                            p.price,
                            p.stock_quantity,
                            p.created_at,
                            p.updated_at,
                            c.name AS category_name, -- Select the category name and alias it
                            p.category_id            -- Keep category_id for reference if needed
                          FROM
                            products p
                          LEFT JOIN
                            categories c ON p.category_id = c.category_id
                          ORDER BY
                            p.product_id DESC");
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo '<div class="error-messages"><p>Error fetching products: ' . htmlspecialchars($e->getMessage()) . '</p></div>';
    error_log("Admin Products Page DB Error: " . $e->getMessage());
}
?>

<section class="admin-content">
    <h2>Manage Products</h2>

    <?php
    // Display session messages (e.g., from add/edit/delete actions)
    if (isset($_SESSION['message'])) {
        echo '<div class="session-message success-message"><p>' . htmlspecialchars($_SESSION['message']) . '</p></div>';
        unset($_SESSION['message']); // Clear the message after displaying it
    }
    if (isset($_SESSION['error'])) {
        echo '<div class="session-message error-message"><p>' . htmlspecialchars($_SESSION['error']) . '</p></div>';
        unset($_SESSION['error']);
    }
    ?>

    <div class="action-bar">
        <a href="add_product.php" class="button">Add New Product</a>
    </div>

    <?php if (empty($products)): ?>
        <p>No products found in the database. <a href="add_product.php">Add one now!</a></p>
    <?php else: ?>
        <table class="admin-table">
                 <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Price</th>
                    <th>Stock</th>
                    <th>Category</th> <th>Created At</th>
                    <th>Updated At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($products as $product): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($product['product_id']); ?></td>
                        <td><?php echo htmlspecialchars($product['name']); ?></td>
                        <td>$<?php echo htmlspecialchars(number_format($product['price'], 2)); ?></td>
                        <td><?php echo htmlspecialchars($product['stock_quantity']); ?></td>
                        <td><?php echo htmlspecialchars($product['category_name'] ?? 'Uncategorized'); ?></td> <td><?php echo htmlspecialchars($product['created_at']); ?></td>
                        <td><?php echo htmlspecialchars($product['updated_at'] ?? 'N/A'); ?></td>
                        <td class="admin-actions">
                            <a href="edit_product.php?id=<?php echo htmlspecialchars($product['product_id']); ?>" class="button edit-button">Edit</a>
                            <form action="delete_product.php" method="POST" style="display:inline-block;" onsubmit="return confirm('Are you sure you want to delete this product?');">
                                <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($product['product_id']); ?>">
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
// Include the admin footer
include 'includes/admin_footer.php';
?>