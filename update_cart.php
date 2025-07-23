<?php
// bytekit_infotech/update_cart.php

// Start the session (crucial for shopping cart operations)
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Include necessary files
include_once 'includes/db_connect.php'; // For potential stock checks
include_once 'includes/functions.php'; // For redirect()

// Ensure it's a POST request
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    redirect('cart.php'); // Redirect if not a POST request
}

$cart = $_SESSION['cart'] ?? []; // Get current cart from session
$message = ''; // To store feedback for the user

// --- Handle removing an item ---
if (isset($_POST['remove_item'])) {
    $product_id_to_remove = (int)$_POST['remove_item'];

    if (isset($cart[$product_id_to_remove])) {
        $removed_product_name = $cart[$product_id_to_remove]['name'];
        unset($cart[$product_id_to_remove]); // Remove the item from the cart array
        $message = htmlspecialchars($removed_product_name) . " has been removed from your cart.";
    } else {
        $message = "Error: Product not found in cart.";
    }
}
// --- Handle updating quantities ---
elseif (isset($_POST['update_cart']) && isset($_POST['quantity']) && is_array($_POST['quantity'])) {
    $updated_quantities = $_POST['quantity'];
    $messages_for_update = [];

    foreach ($updated_quantities as $product_id_str => $new_quantity) {
        $product_id = (int)$product_id_str;
        $new_quantity = (int)$new_quantity;

        // Ensure product exists in cart and new quantity is valid
        if (isset($cart[$product_id]) && $new_quantity >= 0) {
            // Optional: Re-fetch stock_quantity from DB to prevent over-ordering
            // This is important for a real store, for now we rely on add_to_cart for initial check.
            // For a robust system, you'd re-check stock here before updating the cart.
            try {
                $stmt = $pdo->prepare("SELECT stock_quantity FROM products WHERE product_id = ?");
                $stmt->execute([$product_id]);
                $product_db_info = $stmt->fetch();
                $max_stock = $product_db_info['stock_quantity'] ?? 0;

                if ($new_quantity > $max_stock) {
                    $new_quantity = $max_stock; // Cap quantity at available stock
                    $messages_for_update[] = "Only " . $max_stock . " of " . htmlspecialchars($cart[$product_id]['name']) . " available. Quantity adjusted.";
                }

            } catch (PDOException $e) {
                error_log("Stock check error during cart update: " . $e->getMessage());
                // Continue without stock check if DB error occurs
            }

            // Update quantity
            if ($new_quantity > 0) {
                $cart[$product_id]['quantity'] = $new_quantity;
            } else {
                // If quantity is 0, remove the item
                unset($cart[$product_id]);
                $messages_for_update[] = htmlspecialchars($cart[$product_id]['name']) . " removed from cart.";
            }
        }
    }
    $message = implode('<br>', $messages_for_update); // Join multiple messages
    if (empty($message)) { // If no specific messages from stock check/removal
        $message = "Your cart has been updated.";
    }
} else {
    $message = "Invalid cart action.";
}

// Save the updated cart back to session
$_SESSION['cart'] = $cart;

// Store the message in session to display on cart.php
$_SESSION['message'] = $message;

// Redirect back to the cart page
redirect('cart.php');
?>