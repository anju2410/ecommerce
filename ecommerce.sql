-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 24, 2026 at 07:10 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ecommerce`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `name`, `email`, `password`) VALUES
(1, 'Admin', 'admin@gmail.com', '$2y$10$LKA2rq6vwRJBWoqPOSPUO.0F9HBG4SpuOOFZrOJ88aSp5Jdjsl2Ti');

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`id`, `user_id`, `product_id`, `quantity`) VALUES
(9, 1, 2, 1),
(12, 2, 2, 1);

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `created_at`) VALUES
(1, 'Electronics', '2026-02-12 05:10:55'),
(2, 'Fashion', '2026-02-12 05:10:55'),
(3, 'Home Appliances', '2026-02-12 05:10:55'),
(4, 'Travel', '2026-02-15 08:18:02');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `total_amount` decimal(10,2) DEFAULT NULL,
  `payment_method` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `payment_status` varchar(50) DEFAULT 'Pending',
  `order_status` varchar(50) DEFAULT 'Processing'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `total_amount`, `payment_method`, `created_at`, `payment_status`, `order_status`) VALUES
(1, 1, 32999.00, 'COD', '2026-02-12 06:47:41', 'Pending', 'Processing'),
(2, 1, 79999.00, 'Credit Card', '2026-02-12 06:52:17', 'Paid', 'Processing'),
(3, 1, 32999.00, 'Credit Card', '2026-02-13 04:37:02', 'Paid', 'Processing'),
(4, 1, 79999.00, 'Card', '2026-02-13 04:39:57', 'Pending', 'Pending'),
(5, 1, 79999.00, 'Card', '2026-02-13 04:44:06', 'Pending', 'Pending'),
(6, 1, 55999.00, 'Card', '2026-02-13 04:48:15', 'Pending', 'Pending'),
(7, 1, 55999.00, 'Card', '2026-02-13 04:56:40', 'Pending', 'Shipped'),
(8, 1, 55999.00, 'Card', '2026-02-13 05:07:07', 'Pending', 'Pending'),
(9, 2, 2999.00, 'COD', '2026-02-16 13:30:30', 'Pending', 'Processing'),
(10, 2, 2999.00, 'COD', '2026-02-16 13:35:45', 'Pending', 'Delivered'),
(11, 2, 55999.00, 'Card', '2026-02-16 13:36:37', 'Paid', 'Processing'),
(12, 6, 112998.00, 'Card', '2026-02-17 06:15:55', 'Paid', 'Delivered');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `quantity`, `price`) VALUES
(1, 1, 3, 1, 32999.00),
(2, 2, 1, 1, 79999.00),
(3, 3, 3, 1, 32999.00),
(4, 4, 1, 1, 79999.00),
(5, 5, 1, 1, 79999.00),
(6, 6, 2, 1, 55999.00),
(7, 7, 2, 1, 55999.00),
(8, 8, 2, 1, 55999.00),
(9, 9, 5, 1, 2999.00),
(10, 10, 5, 1, 2999.00),
(11, 11, 2, 1, 55999.00),
(12, 12, 1, 1, 79999.00),
(13, 12, 3, 1, 32999.00);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `subcategory_id` int(11) DEFAULT NULL,
  `name` varchar(200) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `category_id`, `subcategory_id`, `name`, `description`, `price`, `image`, `created_at`) VALUES
(1, 1, 1, 'iPhone 14', 'Apple smartphone with 128GB storage', 79999.00, 'iphone14.jpg', '2026-02-12 05:10:55'),
(2, 1, 2, 'Dell Inspiron Laptop', '15 inch laptop with 8GB RAM', 55999.00, 'dell.jpg', '2026-02-12 05:10:55'),
(3, 1, 3, 'Samsung 43 inch Smart TV', 'Full HD Smart LED TV', 32999.00, 'samsung_tv.jpg', '2026-02-12 05:10:55'),
(4, 2, 4, 'Men Cotton Shirt', 'Slim fit casual shirt', 1499.00, 'shirt.jpg', '2026-02-12 05:10:55'),
(5, 3, 6, 'Non-stick Cookware Set', '5-piece kitchen cookware set', 2999.00, 'cookware.jpg', '2026-02-12 05:10:55'),
(6, 2, 5, 'Tshirt', 'Symbol Premium Women\'s Super Soft Cotton Round Neck Solid T-Shirt ', 450.00, 'tsh.jpg', '2026-02-15 08:00:52');

-- --------------------------------------------------------

--
-- Table structure for table `product_ratings`
--

CREATE TABLE `product_ratings` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `rating` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `review` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product_ratings`
--

INSERT INTO `product_ratings` (`id`, `product_id`, `user_id`, `rating`, `created_at`, `review`) VALUES
(1, 2, 1, 4, '2026-02-16 12:06:13', NULL),
(2, 5, 2, 5, '2026-02-16 13:30:12', 'superb quality');

-- --------------------------------------------------------

--
-- Table structure for table `subcategories`
--

CREATE TABLE `subcategories` (
  `id` int(11) NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `name` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `subcategories`
--

INSERT INTO `subcategories` (`id`, `category_id`, `name`, `created_at`) VALUES
(1, 1, 'Mobile', '2026-02-12 05:10:55'),
(2, 1, 'Laptop', '2026-02-12 05:10:55'),
(3, 1, 'Television', '2026-02-12 05:10:55'),
(4, 2, 'Men Clothing', '2026-02-12 05:10:55'),
(5, 2, 'Women Clothing', '2026-02-12 05:10:55'),
(6, 3, 'Kitchen', '2026-02-12 05:10:55'),
(7, 3, 'Furniture', '2026-02-12 05:10:55'),
(8, 2, 'kids', '2026-02-15 08:18:27');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` varchar(20) DEFAULT 'Active',
  `reset_token` varchar(255) DEFAULT NULL,
  `token_expiry` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `created_at`, `status`, `reset_token`, `token_expiry`) VALUES
(1, 'Anjana', 'anjana@gmail.com', '$2y$10$LKA2rq6vwRJBWoqPOSPUO.0F9HBG4SpuOOFZrOJ88aSp5Jdjsl2Ti', '2026-02-12 05:10:55', 'Active', NULL, NULL),
(2, 'xyz', 'xyz@gmail.com', '$2y$10$39bSkKLqz2ajY7UyUIZ1pexMEa9zE.EYxtS.Nj12aOg0TXuBfN7YK', '2026-02-16 13:23:30', 'Active', NULL, NULL),
(6, 'abc', 'abc@gmail.com', '$2y$10$KEs6mAnEuIUuGtygIYNNvuRjXuSp5LBS6flZ/DpHGbZAbTy5YOANC', '2026-02-17 05:47:41', 'Active', 'e25a8e4959579ebf76f7e2f309ec2fcb47c7d7299e28e59e3b834d8b86142ca2', '2026-02-18 09:17:41');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`),
  ADD KEY `subcategory_id` (`subcategory_id`);

--
-- Indexes for table `product_ratings`
--
ALTER TABLE `product_ratings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `subcategories`
--
ALTER TABLE `subcategories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `product_ratings`
--
ALTER TABLE `product_ratings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `subcategories`
--
ALTER TABLE `subcategories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `products_ibfk_2` FOREIGN KEY (`subcategory_id`) REFERENCES `subcategories` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `product_ratings`
--
ALTER TABLE `product_ratings`
  ADD CONSTRAINT `product_ratings_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_ratings_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `subcategories`
--
ALTER TABLE `subcategories`
  ADD CONSTRAINT `subcategories_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
