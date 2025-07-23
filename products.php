<?php
// bytekit_infotech/products.php

// Include the header (which contains your HTML head, body opening, and navigation)
include 'includes/header.php';

// Include the database connection file
include 'includes/db_connect.php'; // This provides the $pdo object
?>

<section class="products-listing">
    <h2>Our Latest Products</h2>
    <div class="product-grid">
        <?php
        try {
            // Prepare and execute a SQL query to fetch all products
            $stmt = $pdo->query("SELECT product_id, name, price, image_url FROM products ORDER BY name ASC");
            $products = $stmt->fetchAll(); // Fetch all results as an associative array

            // Check if any products were found
            if ($products) {
                // Loop through each product and display it
                foreach ($products as $product) {
        ?>
                    <div class="product-item">
                        <?php
                        // Display product image if available, otherwise a placeholder
                        $image_src = !empty($product['image_url']) ? 'images/products/' . htmlspecialchars($product['image_url']) : 'https://via.placeholder.com/150?text=No+Image';
                        ?>
                        <img src="<?php echo htmlspecialchars($image_src); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                        <h3><?php echo htmlspecialchars($product['name']); ?></h3>
                        <p class="price">$<?php echo htmlspecialchars(number_format($product['price'], 2)); ?></p>
                        <a href="product_detail.php?id=<?php echo htmlspecialchars($product['product_id']); ?>" class="button view-details">View Details</a>
                        <form action="add_to_cart.php" method="POST" class="add-to-cart-form">
                        <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($product['product_id']); ?>">
                        <input type="hidden" name="quantity" value="1"> <button type="submit" class="button add-to-cart">Add to Cart</button>
</form>
                        </div>
        <?php
                }
            } else {
                echo "<p>No products found in the store yet. Please add some from the admin panel!</p>";
            }
        } catch (PDOException $e) {
            echo "<p style='color: red;'>Error fetching products: " . htmlspecialchars($e->getMessage()) . "</p>";
            // In a production environment, you'd log this error and show a generic message
            error_log("Error fetching products: " . $e->getMessage());
        }
        ?>
    </div>
</section>

<?php
// Include the footer (which closes the HTML body and document)
include 'includes/footer.php';
?>