<?php
// bytekit_infotech/admin/delete_product.php

// Include admin header for security checks (session start, admin type check)
include 'includes/admin_header.php';

// At this point, the user is confirmed to be an admin.

// Ensure it's a POST request (delete operations should not be done via GET for security/idempotence reasons)
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
    // Before deleting the product, we might want to get its name for the success message
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