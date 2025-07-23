<?php
// bytekit_infotech/admin/includes/admin_header.php

// Start the session (crucial for checking login status and user type)
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Include database connection and functions (assuming functions.php has is_logged_in and redirect)
// Adjust paths as necessary to go up one level (admin/) then into includes/
include_once '../includes/db_connect.php';
include_once '../includes/functions.php';

// IMPORTANT: Admin Access Control
// Redirect if not logged in OR if not an admin
if (!is_logged_in() || $_SESSION['user_type'] !== 'admin') {
    // You can set a message for non-admin users if you want
    // $_SESSION['message'] = "You do not have permission to access the admin panel.";
    redirect('/bytekit_infotech/login.php'); // Redirect to login page
    exit; // Stop execution
}
// If they are an admin, proceed.

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ByteKit Infotech Admin Panel</title>
    <link rel="stylesheet" href="/bytekit_infotech/css/style.css"> <style>
        /* Admin specific styles (can be moved to a separate admin.css later) */
        body.admin-body {
            background-color: #f0f2f5; /* Lighter background for admin */
        }
        .admin-header {
            background: #2c3e50; /* Darker header for admin */
            color: #ecf0f1;
            padding: 15px 0;
            border-bottom: 3px solid #3498db; /* Admin blue */
        }
        .admin-header h1 {
            float: left;
            margin: 0;
            padding: 0;
            font-size: 1.8em;
        }
        .admin-header nav {
            float: right;
            margin-top: 5px;
        }
        .admin-header ul {
            margin: 0;
            padding: 0;
            list-style: none;
        }
        .admin-header li {
            display: inline;
            padding: 0 15px;
        }
        .admin-header a {
            color: #ecf0f1;
            text-decoration: none;
            text-transform: uppercase;
            font-size: 14px;
        }
        .admin-header a:hover {
            color: #3498db; /* Admin blue on hover */
            font-weight: bold;
        }
        .admin-container {
            width: 90%;
            margin: auto;
            overflow: hidden;
            padding: 30px 0;
        }
        .admin-dashboard-links {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 25px;
            margin-top: 30px;
        }
        .admin-dashboard-link-item {
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 25px;
            text-align: center;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            transition: transform 0.2s ease-in-out, box-shadow 0.2s;
        }
        .admin-dashboard-link-item:hover {
            transform: translateY(-7px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        .admin-dashboard-link-item h3 {
            font-size: 1.5em;
            margin-bottom: 15px;
            color: #3498db; /* Admin blue */
        }
        .admin-dashboard-link-item p {
            color: #666;
            margin-bottom: 20px;
        }
        .admin-dashboard-link-item .button {
            background-color: #3498db;
            color: #fff;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            font-size: 1em;
            transition: background-color 0.2s;
        }
        .admin-dashboard-link-item .button:hover {
            background-color: #2980b9;
        }
    </style>
</head>
<body class="admin-body">
    <header class="admin-header">
        <div class="admin-container">
            <h1><a href="/bytekit_infotech/admin/index.php" style="color:#ecf0f1; text-decoration:none;">Admin Dashboard</a></h1>
            <nav>
                <ul>
                    <li><a href="/bytekit_infotech/admin/products.php">Products</a></li>
                    <li><a href="/bytekit_infotech/admin/categories.php">Categories</a></li>
                    <li><a href="/bytekit_infotech/admin/orders.php">Orders</a></li>
                    <li><a href="/bytekit_infotech/admin/users.php">Users</a></li>
                    <li><a href="/bytekit_infotech/logout.php">Logout</a></li> </ul>
            </nav>
        </div>
    </header>
    <div class="admin-container">
        ```