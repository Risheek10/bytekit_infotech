<?php
// includes/header.php
// This file provides the consistent header section for all public-facing pages of the website.
// It includes the start of the HTML document, meta tags, CSS links, and the main navigation menu.

// Calculate cart item count for the header display.
// This variable is used to show the number of items in the shopping cart.
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
    
    <!-- SEO Meta Tags for Search Engine Optimization -->
    <meta name="description" content="ByteKit Infotech: Your one-stop shop for high-quality computer parts and PC building components. Find CPUs, GPUs, RAM, storage, and more.">
    <meta name="keywords" content="computer parts, PC components, custom PC, build PC, CPU, GPU, RAM, SSD, online store, computer hardware, desktop components, ByteKit Infotech">
    <link rel="icon" href="/bytekit_infotech/images/icons/favicon.ico" type="image/x-icon">
    
    <!-- Link to the main stylesheet for website styling -->
    <link rel="stylesheet" href="/bytekit_infotech/css/style.css">

    <!-- Optional: Link Google Fonts here if you plan to use custom fonts -->
    <!-- <link rel="preconnect" href="https://fonts.googleapis.com"> -->
    <!-- <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin> -->
    <!-- <link href="https://fonts.googleapis.com/css2?family=Roboto+Mono:wght@400;700&display=swap" rel="stylesheet"> -->

</head>
<body>
    <header>
        <div class="container">
            <?php
            // Display session-based messages to the user (e.g., "Product added to cart!", error messages).
            if (isset($_SESSION['message'])) {
                echo '<div class="session-message success-message"><p>' . htmlspecialchars($_SESSION['message']) . '</p></div>';
                unset($_SESSION['message']); // Clear the message after displaying it once.
            }
            ?>
            <!-- Website Logo / Title, links to homepage -->
            <h1><a href="/bytekit_infotech/index.php" style="color:#fff; text-decoration:none;">ByteKit Infotech</a></h1>
            
            <!-- Main Navigation Menu -->
            <nav>
                <ul>
                    <li><a href="/bytekit_infotech/index.php">Home</a></li>
                    <li><a href="/bytekit_infotech/products.php">Products</a></li>
                    <li><a href="/bytekit_infotech/cart.php">Cart (<?php echo $cart_item_count; ?>)</a></li>
                    
                    <!-- NEW: Help link -->
                    <li><a href="/bytekit_infotech/help/getting_started.php">Help</a></li> 

                    <?php 
                    // Check if a user is logged in to display personalized links.
                    if (isset($_SESSION['user_id'])): 
                    ?>
                        <li><a href="/bytekit_infotech/my_orders.php">My Orders</a></li>
                        <?php 
                        // Show Admin Panel link only if the logged-in user is an administrator.
                        if (isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'admin'): 
                        ?>
                            <li><a href="/bytekit_infotech/admin/index.php" class="admin-link">Admin Panel</a></li>
                        <?php endif; ?>
                        <li><a href="/bytekit_infotech/logout.php">Logout (<?php echo htmlspecialchars($_SESSION['username']); ?>)</a></li>
                    <?php 
                    // If no user is logged in, show Login and Register links.
                    else: 
                    ?>
                        <li><a href="/bytekit_infotech/login.php">Login</a></li>
                        <li><a href="/bytekit_infotech/register.php">Register</a></li>
                    <?php endif; ?>

                    <!-- Theme Toggle Button (Light/Dark/Computer Mode) -->
                    <li class="theme-toggle-li">
                        <img id="theme-toggle-button" src="/bytekit_infotech/images/icons/light-bulb-off.png" alt="Toggle Light/Dark Mode" class="theme-toggle-bulb-img">
                    </li>
                </ul>
            </nav>
        </div>
    </header>
