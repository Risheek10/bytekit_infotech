<?php
// bytekit_infotech/admin/add_product.php

// Include the admin header (handles session start, access control, and layout)
include 'includes/admin_header.php';

// At this point, the user is confirmed to be an admin.

$errors = [];
$success_message = '';

// Fetch all categories for the dropdown
$categories = [];
try {
    $stmt = $pdo->query("SELECT category_id, name FROM categories ORDER BY name ASC");
    $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // Log the error but don't stop the page from loading
    error_log("Admin Add Product: Error fetching categories: " . $e->getMessage());
    $errors[] = "Error loading categories. Please try again later.";
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 1. Sanitize and retrieve form data
    $name = sanitize_input($_POST['name'] ?? '');
    $description = sanitize_input($_POST['description'] ?? '');
    $price = filter_var($_POST['price'] ?? 0, FILTER_VALIDATE_FLOAT);
    $stock_quantity = filter_var($_POST['stock_quantity'] ?? 0, FILTER_VALIDATE_INT);
    $image_url = sanitize_input($_POST['image_url'] ?? '');
    $brand = sanitize_input($_POST['brand'] ?? '');
    $model_number = sanitize_input($_POST['model_number'] ?? '');
    $category_id = filter_var($_POST['category_id'] ?? null, FILTER_VALIDATE_INT); // Will connect to categories later

    // 2. Validate input
    if (empty($name)) {
        $errors[] = "Product Name is required.";
    }
    if ($price === false || $price <= 0) {
        $errors[] = "Valid Price is required (must be a positive number).";
    }
    if ($stock_quantity === false || $stock_quantity < 0) {
        $errors[] = "Valid Stock Quantity is required (must be a non-negative integer).";
    }
    // Basic URL validation for image (can be more robust)
    if (!empty($image_url) && !filter_var($image_url, FILTER_VALIDATE_URL)) {
        $errors[] = "Invalid Image URL format.";
    }

    // You might add more validation for brand, model, description length, etc.

    // 3. If no errors, insert into database
    if (empty($errors)) {
        try {
            $stmt = $pdo->prepare("INSERT INTO products (name, description, price, stock_quantity, image_url, brand, model_number, category_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$name, $description, $price, $stock_quantity, $image_url, $brand, $model_number, $category_id]);

            $_SESSION['message'] = "Product '" . htmlspecialchars($name) . "' added successfully!";
            redirect('products.php'); // Redirect back to product list
            exit;

        } catch (PDOException $e) {
            $errors[] = "Database error: Could not add product. " . htmlspecialchars($e->getMessage());
            error_log("Admin Add Product DB Error: " . $e->getMessage());
        }
    }
}
?>

<section class="admin-content">
    <h2>Add New Product</h2>

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

    <form action="add_product.php" method="POST" class="auth-form">
        <div class="form-group">
            <label for="name">Product Name:</label>
            <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($_POST['name'] ?? ''); ?>" required>
        </div>
        <div class="form-group">
            <label for="description">Description:</label>
            <textarea id="description" name="description" rows="5"><?php echo htmlspecialchars($_POST['description'] ?? ''); ?></textarea>
        </div>
        <div class="form-group">
            <label for="price">Price ($):</label>
            <input type="number" id="price" name="price" step="0.01" min="0.01" value="<?php echo htmlspecialchars($_POST['price'] ?? ''); ?>" required>
        </div>
        <div class="form-group">
            <label for="stock_quantity">Stock Quantity:</label>
            <input type="number" id="stock_quantity" name="stock_quantity" min="0" value="<?php echo htmlspecialchars($_POST['stock_quantity'] ?? ''); ?>" required>
        </div>
        <div class="form-group">
            <label for="image_url">Image URL (Optional):</label>
            <input type="url" id="image_url" name="image_url" placeholder="e.g., https://example.com/image.jpg" value="<?php echo htmlspecialchars($_POST['image_url'] ?? ''); ?>">
        </div>
        <div class="form-group">
            <label for="brand">Brand (Optional):</label>
            <input type="text" id="brand" name="brand" value="<?php echo htmlspecialchars($_POST['brand'] ?? ''); ?>">
        </div>
        <div class="form-group">
            <label for="model_number">Model Number (Optional):</label>
            <input type="text" id="model_number" name="model_number" value="<?php echo htmlspecialchars($_POST['model_number'] ?? ''); ?>">
        </div>
        
        <div class="form-group">
            <label for="category_id">Category ID (For now, just a number like 1, 2, etc.):</label>
            <input type="number" id="category_id" name="category_id" min="1" value="<?php echo htmlspecialchars($_POST['category_id'] ?? 1); ?>">
            <small>You can ignore this for now, or just use '1'. We'll implement category management later.</small>
        </div>

        <button type="submit" class="button">Add Product</button>
        <a href="products.php" class="button" style="background-color:#6c757d; margin-left: 10px;">Cancel</a>
    </form>
</section>

<?php
include 'includes/admin_footer.php';
?>