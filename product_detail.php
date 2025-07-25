<?php
// This page shows detailed information for a single computer product based on its ID.

// the header
include 'includes/header.php';

// the database connection file
include 'includes/db_connect.php'; // This provides the $pdo object

$product = null;

// Checks if a product ID is provided in the URL
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $product_id = $_GET['id'];

    try {
        // SQL query to fetch a single product by its ID
        // statement for security to prevent SQL injection
        $stmt = $pdo->prepare("SELECT * FROM products WHERE product_id = :id");
        $stmt->bindParam(':id', $product_id, PDO::PARAM_INT); // Bind the ID as an integer
        $stmt->execute(); // Execute the prepared statement

        $product = $stmt->fetch(); // Fetch a single row

        // If no product found with that ID
        if (!$product) {
            echo '<div class="product-detail-container">';
            echo '<h2>Product Not Found</h2>';
            echo '<p>The product you are looking for does not exist.</p>';
            echo '<p><a href="products.php" class="button">Back to Products</a></p>';
            echo '</div>';
        }

    } catch (PDOException $e) {
        echo '<div class="product-detail-container">';
        echo '<p style="color: red;">Error fetching product details: ' . htmlspecialchars($e->getMessage()) . '</p>';
        echo '<p><a href="products.php" class="button">Back to Products</a></p>';
        echo '</div>';
        error_log("Error fetching product details for ID " . $product_id . ": " . $e->getMessage());
    }
} else {
    // If no ID was provided in the URL
    echo '<div class="product-detail-container">';
    echo '<h2>Invalid Product Request</h2>';
    echo '<p>Please select a product from the <a href="products.php">products list</a>.</p>';
    echo '</div>';
}

// Only display product details if a product was successfully found
if ($product) {
?>
    <section class="product-detail-container">
        <div class="product-image">
            <?php
            // Display product image if available, otherwise a placeholder
            $image_src = !empty($product['image_url']) ? 'images/products/' . htmlspecialchars($product['image_url']) : 'https://via.placeholder.com/150?text=No+Image';
            ?>
            <img src="<?php echo htmlspecialchars($image_src); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
        </div>
        <div class="product-info">
            <h1><?php echo htmlspecialchars($product['name']); ?></h1>
            <p class="price-detail">$<?php echo htmlspecialchars(number_format($product['price'], 2)); ?></p>
            <p class="description"><?php echo nl2br(htmlspecialchars($product['description'])); ?></p>
            <p><strong>Brand:</strong> <?php echo htmlspecialchars($product['brand']); ?></p>
            <p><strong>Model:</strong> <?php echo htmlspecialchars($product['model_number']); ?></p>
            <p><strong>Stock:</strong> <?php echo htmlspecialchars($product['stock_quantity']); ?></p>
            
            <form action="add_to_cart.php" method="POST">
                <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($product['product_id']); ?>">
                <input type="number" name="quantity" value="1" min="1" class="quantity-input"> <button type="submit" class="add-to-cart-btn">Add to Cart</button>
            </form>
            <p><a href="products.php" class="button back-to-products">Back to Products</a></p>
        </div>
    </section>
<?php
}

// Include the footer (which closes the HTML body and document)
include 'includes/footer.php';
?>