<?php
// bytekit_infotech/add_to_cart.php

// Start the session (crucial for shopping cart)
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Include necessary files
include_once 'includes/db_connect.php'; // For getting product details
include_once 'includes/functions.php'; // For redirect()

// Redirect if not a POST request
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    redirect('products.php'); // Or to an error page
}

$product_id = isset($_POST['product_id']) ? (int)$_POST['product_id'] : 0;
$quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;

// Validate input
if ($product_id <= 0 || $quantity <= 0) {
    $_SESSION['message'] = "Invalid product or quantity.";
    redirect('products.php');
}

try {
    // Fetch product details to ensure it's a valid product and get price
    $stmt = $pdo->prepare("SELECT product_id, name, price, stock_quantity FROM products WHERE product_id = :product_id");
    $stmt->bindParam(':product_id', $product_id, PDO::PARAM_INT);
    $stmt->execute();
    $product = $stmt->fetch();

    if (!$product) {
        $_SESSION['message'] = "Product not found.";
        redirect('products.php');
    }

    // Check if enough stock is available
    if ($quantity > $product['stock_quantity']) {
        $_SESSION['message'] = "Not enough stock for " . htmlspecialchars($product['name']) . ". Available: " . htmlspecialchars($product['stock_quantity']);
        redirect('product_detail.php?id=' . $product_id); // Redirect back to detail page if stock issue
    }

    // Initialize the cart in session if it doesn't exist
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    // Add product to cart or update quantity if already in cart
    if (isset($_SESSION['cart'][$product_id])) {
        // Product already in cart, update quantity
        $_SESSION['cart'][$product_id]['quantity'] += $quantity;
        // Re-check stock with updated quantity
        if ($_SESSION['cart'][$product_id]['quantity'] > $product['stock_quantity']) {
            $_SESSION['cart'][$product_id]['quantity'] = $product['stock_quantity']; // Cap at max stock
            $_SESSION['message'] = "You've added the maximum available stock for " . htmlspecialchars($product['name']) . ". Total in cart: " . htmlspecialchars($_SESSION['cart'][$product_id]['quantity']);
        } else {
             $_SESSION['message'] = htmlspecialchars($quantity) . " more of " . htmlspecialchars($product['name']) . " added to cart.";
        }
    } else {
        // Product not in cart, add new item
        $_SESSION['cart'][$product_id] = [
            'id' => $product['product_id'],
            'name' => $product['name'],
            'price' => $product['price'],
            'quantity' => $quantity
        ];
        $_SESSION['message'] = htmlspecialchars($product['name']) . " added to cart.";
    }

    // Redirect to the cart page or back to the product page with a success message
    redirect('cart.php'); // Redirect to cart page
    // Or back to products page: redirect('products.php');

} catch (PDOException $e) {
    $_SESSION['message'] = "Database error adding to cart: " . $e->getMessage();
    error_log("Add to Cart DB error: " . $e->getMessage());
    redirect('products.php'); // Redirect to a safe page on error
}
?>