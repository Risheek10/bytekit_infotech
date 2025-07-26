-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jul 26, 2025 at 02:44 PM
-- Server version: 10.4.34-MariaDB-log
-- PHP Version: 8.3.21

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `jayakum6_bytekit_infotech`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
CREATE TABLE `categories` (
  `category_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`category_id`, `name`, `description`, `created_at`, `updated_at`) VALUES
(1, 'CPU', '', '2025-07-22 13:50:55', '2025-07-22 13:51:55'),
(2, 'Graphics Cards', '', '2025-07-22 13:51:03', '2025-07-22 13:51:03'),
(3, 'Case', '', '2025-07-22 13:51:09', '2025-07-22 13:51:09'),
(4, 'Memory Card', '', '2025-07-22 13:51:15', '2025-07-22 13:51:15'),
(5, 'Storage', '', '2025-07-22 13:51:19', '2025-07-22 13:51:19'),
(6, 'Motherboard', '', '2025-07-22 13:51:26', '2025-07-22 13:51:26');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `order_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `total_amount` decimal(10,2) NOT NULL,
  `shipping_address` text NOT NULL,
  `city` varchar(100) NOT NULL,
  `province` varchar(100) NOT NULL,
  `postal_code` varchar(20) NOT NULL,
  `status` enum('pending','processing','shipped','delivered','cancelled') DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `user_id`, `order_date`, `total_amount`, `shipping_address`, `city`, `province`, `postal_code`, `status`) VALUES
(1, 1, '2025-07-21 23:33:21', 849.99, 'abc towers', 'toronto', 'ontario', 'm6s 3l5', 'pending');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

DROP TABLE IF EXISTS `order_items`;
CREATE TABLE `order_items` (
  `item_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price_at_purchase` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`item_id`, `order_id`, `product_id`, `quantity`, `price_at_purchase`) VALUES
(1, 1, 1, 1, 350.00),
(2, 1, 2, 1, 499.99);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
CREATE TABLE `products` (
  `product_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `brand` varchar(100) DEFAULT NULL,
  `model_number` varchar(100) DEFAULT NULL,
  `stock_quantity` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_id`, `name`, `description`, `price`, `image_url`, `category_id`, `brand`, `model_number`, `stock_quantity`, `created_at`, `updated_at`) VALUES
(1, 'Intel Core i7-12700K', '12th Gen CPU for high-performance computing.', 350.00, 'intel_i7_12700k.jpg', 1, 'Intel', 'i7-12700K', 50, '2025-07-21 22:36:21', '2025-07-22 16:43:07'),
(2, 'NVIDIA GeForce RTX 3070', 'High-end graphics card for gaming.', 499.99, 'rtx_3070.jpg', 2, 'NVIDIA', 'RTX3070', 30, '2025-07-21 22:36:21', '2025-07-22 17:00:01'),
(3, 'ASUS ROG Strix Z690-E', 'Gaming motherboard for Intel CPUs.', 299.00, 'rog_mobo.png\r\n', 3, 'ASUS', 'Z690-E', 20, '2025-07-21 22:36:21', '2025-07-22 17:01:20'),
(4, 'Corsair Vengeance RGB Pro 16GB (2x8GB) DDR4 3200MHz', 'High-speed RAM with RGB lighting.', 75.50, 'rbg_pro.jpg\r\n', 4, 'Corsair', 'CMW16GX4M2C3200C16', 100, '2025-07-21 22:36:21', '2025-07-22 17:03:36'),
(5, 'Samsung 970 EVO Plus 1TB NVMe SSD', 'Fast solid-state drive for storage.', 99.00, 'ssd.jpg', 5, 'Samsung', 'MZ-V7S1T0B/AM', 75, '2025-07-21 22:36:21', '2025-07-22 17:07:16'),
(7, 'NZXT H6 Flow', 'Mid-Tower Computer Case', 109.00, 'case.jpg', 1, 'NZXT', '', 20, '2025-07-22 17:08:14', '2025-07-22 19:48:04'),
(8, 'Corsair RM850x ATX powersupply', 'Gold standard Powersupply upto 850W', 200.00, 'psu.jpg', 1, 'Corsair', '', 10, '2025-07-22 17:09:15', '2025-07-22 19:47:53'),
(9, 'NZXT Kraken Z73 RGB', 'AIO cooler for CPU', 550.00, 'aio.jpg', 1, 'NZXT', '', 5, '2025-07-22 17:09:59', '2025-07-22 19:47:28');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `first_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `phone_number` varchar(20) DEFAULT NULL,
  `user_type` enum('customer','admin') DEFAULT 'customer',
  `registration_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `last_login` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `email`, `password_hash`, `first_name`, `last_name`, `address`, `phone_number`, `user_type`, `registration_date`, `last_login`) VALUES
(1, 'risheek_test1', 'test1@gmail.com', '$2y$10$BNiqccc9TsyqyNGczxJOqOI1OTEbwhSXXjXQ9oRm432mV.y/uU022', '', '', NULL, NULL, 'customer', '2025-07-21 23:06:48', '2025-07-26 17:17:10'),
(2, 'risheek_admin', 'admin1@gmail.com', '$2y$10$Q30gVoqLREC3TRt9XdP0bOQnHM77GL0.oTpXMyx.uckaGmRAQ07HG', '', '', NULL, NULL, 'admin', '2025-07-21 23:37:45', '2025-07-25 20:18:26');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`category_id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`item_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
