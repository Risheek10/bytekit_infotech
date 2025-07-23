<?php
// bytekit_infotech/includes/header.php
$cart_item_count = 0;
if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
        $cart_item_count += $item['quantity'];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ByteKit Infotech - Your PC Part Paradise</title>
    <link rel="stylesheet" href="/bytekit_infotech/css/style.css">
</head>
<body>
    <header>
        <div class="container">
            <?php
            // Display session messages (e.g., "Product added to cart!")
            if (isset($_SESSION['message'])) {
                echo '<div class="session-message success-message"><p>' . htmlspecialchars($_SESSION['message']) . '</p></div>';
                unset($_SESSION['message']); // Clear the message after displaying it
            }
            ?>
            <h1><a href="/bytekit_infotech/index.php" style="color:#fff; text-decoration:none;">ByteKit Infotech</a></h1>
            <nav>
                <ul>
                    <li><a href="/bytekit_infotech/index.php">Home</a></li>
                    <li><a href="/bytekit_infotech/products.php">Products</a></li>
                    <li><a href="/bytekit_infotech/cart.php">Cart (<?php echo $cart_item_count; ?>)</a></li>
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <li><a href="/bytekit_infotech/my_orders.php">My Orders</a></li>
                       <?php if (isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'admin'): ?>
                            <li><a href="/bytekit_infotech/admin/index.php" class="admin-link">Admin Panel</a></li>
                        <?php endif; ?>
                        <li><a href="/bytekit_infotech/logout.php">Logout (<?php echo htmlspecialchars($_SESSION['username']); ?>)</a></li>
                    <?php else: ?>
                        <li><a href="/bytekit_infotech/login.php">Login</a></li>
                        <li><a href="/bytekit_infotech/register.php">Register</a></li>
                    <?php endif; ?>

                    <li class="theme-toggle-li">
                        <img id="theme-toggle-button" src="/bytekit_infotech/images/icons/light-bulb-off.png" alt="Toggle Light/Dark Mode" class="theme-toggle-bulb-img">
                    </li>
                </ul>
            </nav>
        </div>
    </header>