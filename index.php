<?php
// index.php
// This is the main page (homepage) of the ByteKit Infotech website.
// It shows a welcome message and a list of featured computer parts.
session_start();

// database connection
include_once 'includes/db_connect.php';

// common functions
include_once 'includes/functions.php';

// Fetches featured products for the homepage
$featured_products = [];
try {
    $stmt = $pdo->query("SELECT product_id, name, price, image_url FROM products ORDER BY created_at DESC LIMIT 4");
    $featured_products = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("Error fetching featured products for homepage: " . $e->getMessage());
    $_SESSION['error'] = "Could not load featured products at this time.";
}

// the header (which contains your HTML head, body opening, and navigation)
include 'includes/header.php';
?>

<section class="hero">
    <h2>Welcome to ByteKit Infotech!</h2>
    <p>Your ultimate destination for high-quality PC components. Build your dream machine today!</p>
    <a href="products.php" class="button">Explore Products</a>
</section>

<section class="featured-products">
    <h3>Featured Products</h3>
    <div class="product-grid">
        <?php if (!empty($featured_products)): ?>
            <?php foreach ($featured_products as $product): ?>
                <div class="product-item">
                    <?php
                    $image_src = !empty($product['image_url']) ? 'images/products/' . htmlspecialchars($product['image_url']) : 'https://via.placeholder.com/150?text=No+Image';
                    ?>
                    <img src="<?php echo $image_src; ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                    <h4><?php echo htmlspecialchars($product['name']); ?></h4>
                    <p class="price">$<?php echo htmlspecialchars(number_format($product['price'], 2)); ?></p>
                    <a href="product_detail.php?id=<?php echo htmlspecialchars($product['product_id']); ?>" class="button view-details">View Details</a>
                    </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No featured products available at the moment.</p>
        <?php endif; ?>
    </div>
</section>

<?php
// the footer (which closes the HTML body and document)
include 'includes/footer.php';
?>