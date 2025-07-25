<?php
// This page in the admin panel allows administrators to modify the details of existing computer products in the store.

// Include the admin header (handles session start, access control, and layout)
include 'includes/admin_header.php';

$errors = [];
$product_data = []; // To hold product data retrieved from DB

$product_id = filter_var($_GET['id'] ?? null, FILTER_VALIDATE_INT);

// Redirect if no valid product ID is provided
if (!$product_id) {
    $_SESSION['error'] = "No product ID specified for editing.";
    redirect('products.php');
    exit;
}

try {
    $stmt = $pdo->prepare("SELECT * FROM products WHERE product_id = ?");
    $stmt->execute([$product_id]);
    $product_data = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$product_data) {
        $_SESSION['error'] = "Product not found.";
        redirect('products.php');
        exit;
    }
} catch (PDOException $e) {
    $_SESSION['error'] = "Database error fetching product: " . htmlspecialchars($e->getMessage());
    error_log("Admin Edit Product Fetch DB Error: " . $e->getMessage());
    redirect('products.php');
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = sanitize_input($_POST['name'] ?? '');
    $description = sanitize_input($_POST['description'] ?? '');
    $price = filter_var($_POST['price'] ?? 0, FILTER_VALIDATE_FLOAT);
    $stock_quantity = filter_var($_POST['stock_quantity'] ?? 0, FILTER_VALIDATE_INT);
    $image_url = sanitize_input($_POST['image_url'] ?? '');
    $brand = sanitize_input($_POST['brand'] ?? '');
    $model_number = sanitize_input($_POST['model_number'] ?? '');
    $category_id = filter_var($_POST['category_id'] ?? null, FILTER_VALIDATE_INT);


    if (empty($name)) {
        $errors[] = "Product Name is required.";
    }
    if ($price === false || $price <= 0) {
        $errors[] = "Valid Price is required (must be a positive number).";
    }
    if ($stock_quantity === false || $stock_quantity < 0) {
        $errors[] = "Valid Stock Quantity is required (must be a non-negative integer).";
    }

    if (!empty($image_url)) {
        // Check if it's *not* a valid URL (http/https) AND *not* just a common image filename
        if (!filter_var($image_url, FILTER_VALIDATE_URL) && !preg_match('/\.(png|jpe?g|gif|webp)$/i', $image_url)) {
             $errors[] = "Invalid Image URL or filename format. Please use a full URL (http/https) or just the filename (e.g., image.png).";
        }
    }

    if (empty($errors)) {
        try {
            $stmt = $pdo->prepare("UPDATE products SET name = ?, description = ?, price = ?, stock_quantity = ?, image_url = ?, brand = ?, model_number = ?, category_id = ?, updated_at = CURRENT_TIMESTAMP WHERE product_id = ?");
            $stmt->execute([$name, $description, $price, $stock_quantity, $image_url, $brand, $model_number, $category_id, $product_id]);

            $_SESSION['message'] = "Product '" . htmlspecialchars($name) . "' updated successfully!";
            redirect('products.php'); // Redirect back to product list
            exit;

        } catch (PDOException $e) {
            $errors[] = "Database error: Could not update product. " . htmlspecialchars($e->getMessage());
            error_log("Admin Edit Product Update DB Error: " . $e->getMessage());
        }
    }
    $product_data = array_merge($product_data, $_POST);
}
?>

<section class="admin-content">
    <h2>Edit Product: <?php echo htmlspecialchars($product_data['name'] ?? 'N/A'); ?></h2>

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

    <form action="edit_product.php?id=<?php echo htmlspecialchars($product_id); ?>" method="POST" class="auth-form">
        <div class="form-group">
            <label for="name">Product Name:</label>
            <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($product_data['name'] ?? ''); ?>" required>
        </div>
        <div class="form-group">
            <label for="description">Description:</label>
            <textarea id="description" name="description" rows="5"><?php echo htmlspecialchars($product_data['description'] ?? ''); ?></textarea>
        </div>
        <div class="form-group">
            <label for="price">Price ($):</label>
            <input type="number" id="price" name="price" step="0.01" min="0.01" value="<?php echo htmlspecialchars($product_data['price'] ?? ''); ?>" required>
        </div>
        <div class="form-group">
            <label for="stock_quantity">Stock Quantity:</label>
            <input type="number" id="stock_quantity" name="stock_quantity" min="0" value="<?php echo htmlspecialchars($product_data['stock_quantity'] ?? ''); ?>" required>
        </div>
        <div class="form-group">
            <label for="image_url">Image URL (Optional):</label>
            <input type="text" id="image_url" name="image_url" placeholder="e.g., image.png" value="<?php echo htmlspecialchars($product_data['image_url'] ?? ''); ?>">
        </div>
        <div class="form-group">
            <label for="brand">Brand (Optional):</label>
            <input type="text" id="brand" name="brand" value="<?php echo htmlspecialchars($product_data['brand'] ?? ''); ?>">
        </div>
        <div class="form-group">
            <label for="model_number">Model Number (Optional):</label>
            <input type="text" id="model_number" name="model_number" value="<?php echo htmlspecialchars($product_data['model_number'] ?? ''); ?>">
        </div>
        <div class="form-group">
            <label for="category_id">Category ID:</label>
            <input type="number" id="category_id" name="category_id" min="1" value="<?php echo htmlspecialchars($product_data['category_id'] ?? 1); ?>">
            <small>This will be dynamic with category management later.</small>
        </div>

        <button type="submit" class="button">Update Product</button>
        <a href="products.php" class="button" style="background-color:#6c757d; margin-left: 10px;">Cancel</a>
    </form>
</section>

<?php
include 'includes/admin_footer.php';
?>