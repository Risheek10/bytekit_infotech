<?php
//This script handles the deletion of a specific product from the store's database by an administrator.

include 'includes/admin_header.php';

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    $_SESSION['error'] = "Invalid request method.";
    redirect('products.php');
    exit;
}

$product_id = filter_var($_POST['product_id'] ?? null, FILTER_VALIDATE_INT);

// Validate product ID
if (!$product_id) {
    $_SESSION['error'] = "No product ID specified for deletion.";
    redirect('products.php');
    exit;
}

try {
    $stmt_name = $pdo->prepare("SELECT name FROM products WHERE product_id = ?");
    $stmt_name->execute([$product_id]);
    $product_name_result = $stmt_name->fetch(PDO::FETCH_ASSOC);
    $product_name = $product_name_result ? htmlspecialchars($product_name_result['name']) : 'Unknown Product';

    // Prepare and execute the DELETE statement
    $stmt_delete = $pdo->prepare("DELETE FROM products WHERE product_id = ?");
    $stmt_delete->execute([$product_id]);

    // Check if any row was affected
    if ($stmt_delete->rowCount() > 0) {
        $_SESSION['message'] = "Product '" . $product_name . "' (ID: " . $product_id . ") has been deleted successfully.";
    } else {
        $_SESSION['error'] = "Product with ID " . $product_id . " not found or could not be deleted.";
    }

} catch (PDOException $e) {
    // Catch database errors
    $_SESSION['error'] = "Database error: Could not delete product. " . htmlspecialchars($e->getMessage());
    error_log("Admin Delete Product DB Error: " . $e->getMessage());
}

// Redirect back to the product listing page
redirect('products.php');
exit;
?>