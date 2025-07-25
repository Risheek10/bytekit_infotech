<?php
// products.php
// This page displays a list of all computer products available for sale on the ByteKit Infotech website.

// Start the user's session.
session_start();

// Include necessary files for website structure, database, and helpful functions.
include 'includes/header.php'; // HTML header and navigation bar.
include 'includes/db_connect.php'; // Connects to the database using the $pdo object.
include 'includes/functions.php'; // Provides useful functions like 'sanitize_input'.
?>

<section class="products-listing">
    <h2>Our Latest Products</h2>
    <div class="product-grid">
        <?php
        try {
            // SQL Query: Select product details from the database.
            // Products are ordered by name (alphabetical).
            $stmt = $pdo->query("SELECT product_id, name, price, image_url FROM products ORDER BY name ASC");
            $products = $stmt->fetchAll(PDO::FETCH_ASSOC); // Get all results as an array.

            // Check if any products were found in the database.
            if ($products) {
                // Loop through each product to display its information.
                foreach ($products as $product) {
        ?>
                    <div class="product-item">
                        <?php
                        // Construct the image source URL.
                        // If 'image_url' is empty in the database, use a placeholder image from an online service.
                        // Otherwise, build the path to the image in your 'images/products/' folder.
                        $image_src = !empty($product['image_url']) ? 'images/products/' . htmlspecialchars($product['image_url']) : 'https://via.placeholder.com/150?text=No+Image';
                        ?>
                        <!-- Product Image -->
                        <img src="<?php echo htmlspecialchars($image_src); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                        
                        <!-- Product Name -->
                        <h3><?php echo htmlspecialchars($product['name']); ?></h3>
                        
                        <!-- Product Price, formatted to two decimal places -->
                        <p class="price">$<?php echo htmlspecialchars(number_format($product['price'], 2)); ?></p>
                        
                        <!-- Link to view detailed information about the product -->
                        <a href="product_detail.php?id=<?php echo htmlspecialchars($product['product_id']); ?>" class="button view-details">View Details</a>
                        
                        <!-- Form to add the product to the shopping cart -->
                        <form action="add_to_cart.php" method="POST" class="add-to-cart-form">
                            <!-- Hidden input fields to send product ID and a default quantity (1) to the cart script -->
                            <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($product['product_id']); ?>">
                            <input type="hidden" name="quantity" value="1"> 
                            <button type="submit" class="button add-to-cart">Add to Cart</button>
                        </form>
                    </div>
        <?php
                } // End of foreach loop
            } else {
                // Message shown if no products are found in the database.
                echo "<p>No products found in the store yet. Please add some from the admin panel!</p>";
            }
        } catch (PDOException $e) {
            // Display an error message if there's a problem getting products from the database.
            echo "<p style='color: red;'>Error fetching products: " . htmlspecialchars($e->getMessage()) . "</p>";
            error_log("Database error on products.php: " . $e->getMessage()); // Log detailed error for debugging.
        }
        ?>
    </div>
</section>

<?php
// Include the footer part of the website.
// This file contains the end of the HTML page and closing tags.
include 'includes/footer.php';
?>
