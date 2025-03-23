-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 23, 2025 at 05:13 AM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.1.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `nfm`
--

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `added_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`id`, `customer_id`, `product_id`, `quantity`, `added_at`) VALUES
(1, 1, 3, 5, '2025-03-22 14:16:43');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `category_name` varchar(255) NOT NULL,
  `category_image` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `category_name`, `category_image`, `created_at`) VALUES
(1, 'fruits', '1742636912_fruits.jpg', '2025-03-22 09:48:32'),
(2, 'vegetables', '1742637013_vegetables.jpg', '2025-03-22 09:50:13'),
(3, 'beans', '1742637052_beans.jpg', '2025-03-22 09:50:52'),
(4, 'oilseeds', '1742637069_oilseeds.jpg', '2025-03-22 09:51:09'),
(5, 'grains', '1742637371_grain.jpg', '2025-03-22 09:56:11'),
(6, 'abc', 'uploads/categories/Screenshot (1).png', '2025-03-23 01:15:47'),
(7, 'abc', 'uploads/categories/Screenshot 2024-01-08 215805.png', '2025-03-23 01:15:54'),
(8, 'abc', 'uploads/categories/Screenshot 2024-01-08 215805.png', '2025-03-23 01:19:57');

-- --------------------------------------------------------

--
-- Table structure for table `chatbot_hints`
--

CREATE TABLE `chatbot_hints` (
  `id` int(11) NOT NULL,
  `question` varchar(100) NOT NULL,
  `reply` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `chatbot_hints`
--

INSERT INTO `chatbot_hints` (`id`, `question`, `reply`) VALUES
(1, 'HI||Hello||Hola', 'Hello, how are you.'),
(2, 'How are you', 'Good to see you again!'),
(3, 'what is your name||whats your name', 'My name is Vishal Bot'),
(4, 'what should I call you', 'You can call me Vishal Bot'),
(5, 'Where are your from', 'I m from India'),
(6, 'Bye||See you later||Have a Good Day', 'Sad to see you are going. Have a nice day');

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `customer_id` int(11) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `password` varchar(255) NOT NULL,
  `address` text NOT NULL,
  `state` varchar(100) DEFAULT 'Gujarat',
  `city` varchar(100) NOT NULL,
  `pincode` varchar(6) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`customer_id`, `full_name`, `email`, `phone`, `password`, `address`, `state`, `city`, `pincode`, `created_at`) VALUES
(1, 'kesha kalariya', 'kesha@gmail.com', '1234567890', '$2y$10$jNtDN6eWhOndCnv//4wc4.1ixlmie4mchxtgkwgdhgquf1UG3BIwe', 'junagadh', 'Gujarat', 'junagadh', '362229', '2025-03-22 12:04:57'),
(2, 'shloka dave', 'srdave@gmail.com', '3456786543', '$2y$10$eNfFEYJzEqe3NbTOF/cMhuqGpMpC8xWUwwdizJEJKhO2w6Ffz.mEC', 'gnd', 'Gujarat', 'gandhinagar', '382016', '2025-03-22 12:07:52');

-- --------------------------------------------------------

--
-- Table structure for table `delivery_assignments`
--

CREATE TABLE `delivery_assignments` (
  `assignment_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `delivery_boy_id` int(11) NOT NULL,
  `assigned_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `pickup_time` timestamp NULL DEFAULT NULL,
  `delivery_time` timestamp NULL DEFAULT NULL,
  `delivery_status` enum('Assigned','Picked Up','Out for Delivery','Delivered','Failed') NOT NULL DEFAULT 'Assigned'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `delivery_assignments`
--

INSERT INTO `delivery_assignments` (`assignment_id`, `order_id`, `delivery_boy_id`, `assigned_at`, `pickup_time`, `delivery_time`, `delivery_status`) VALUES
(14, 1, 1, '2025-03-22 22:54:03', NULL, NULL, 'Delivered'),
(15, 2, 2, '2025-03-22 22:54:03', NULL, NULL, 'Assigned');

-- --------------------------------------------------------

--
-- Table structure for table `delivery_boy`
--

CREATE TABLE `delivery_boy` (
  `delivery_boy_id` int(11) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `email` varchar(255) NOT NULL,
  `address` text NOT NULL,
  `city` varchar(100) NOT NULL,
  `state` varchar(100) NOT NULL,
  `pincode` varchar(6) NOT NULL,
  `status` enum('Active','Inactive') NOT NULL DEFAULT 'Active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `delivery_boy`
--

INSERT INTO `delivery_boy` (`delivery_boy_id`, `full_name`, `phone`, `email`, `address`, `city`, `state`, `pincode`, `status`, `created_at`, `username`, `password`) VALUES
(1, 'Ravi Kumar', '9876543210', 'ravi.kumar@gmail.com', 'Street 12, City Center', 'Ahmedabad', 'Gujarat', '380009', 'Active', '2025-03-22 22:51:05', 'ravi.kumar', 'hashed_password_1'),
(2, 'Manish Patel', '8765432109', 'manish.patel@gmail.com', 'Street 20, New Town', 'Surat', 'Gujarat', '395007', 'Active', '2025-03-22 22:51:05', 'manish.patel', 'hashed_password_2');

-- --------------------------------------------------------

--
-- Table structure for table `delivery_status`
--

CREATE TABLE `delivery_status` (
  `status_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `delivery_boy_id` int(11) NOT NULL,
  `status` varchar(255) NOT NULL,
  `location` varchar(255) NOT NULL,
  `status_time` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `delivery_status`
--

INSERT INTO `delivery_status` (`status_id`, `order_id`, `delivery_boy_id`, `status`, `location`, `status_time`) VALUES
(3, 1, 1, 'In Transit', 'junagadh', '2025-03-23 04:08:15'),
(4, 2, 1, 'Delivered', 'Surat', '2025-03-22 22:51:35');

-- --------------------------------------------------------

--
-- Table structure for table `farmers`
--

CREATE TABLE `farmers` (
  `id` int(11) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `address` text NOT NULL,
  `state` varchar(100) NOT NULL,
  `city` varchar(100) NOT NULL,
  `pincode` varchar(10) NOT NULL,
  `certificate_url` varchar(255) NOT NULL,
  `bank_name` varchar(255) NOT NULL,
  `account_number` varchar(20) NOT NULL,
  `ifsc_code` varchar(20) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `farmers`
--

INSERT INTO `farmers` (`id`, `full_name`, `email`, `password`, `phone`, `address`, `state`, `city`, `pincode`, `certificate_url`, `bank_name`, `account_number`, `ifsc_code`, `created_at`) VALUES
(1, 'Rajesh Patel', 'abc@gmail.com', '$2y$10$ZZ1uK4.NSy8bim3H8mgLWOA8y/EoZ6gvlXlal6bKskpF0L870aGyC', '1234568900', 'fdfd', 'Gujarat', 'sdfsd', '567849', 'uploads/67de7eb42d75e_Picture1.jpg', 'sbi', '777777777777', 'SBIN0001234', '2025-03-22 09:11:16'),
(2, 'Amit Sharma', 'amit@gmail.com', '$2y$10$pwAXLKwSWrWK5r8ZvPFNK.h97MjIrvRMk3sQVHsrSG93Dh4w6yrE6', '1239456543', 'junagadh gujarat', 'Gujarat', 'jhgj', '654789', 'uploads/67de9aa24a796_mung.jpg', 'sbi', '098765432123', 'SBIN0001234', '2025-03-22 11:10:26'),
(3, 'Sunita Mehta', 'sunita@gmail.com', '$2y$10$5JPSEgE9.L8EdGN5BDn7e.YQwTtiEBMsngeysInrc/h5LjOHB1l.e', '1234567890', 'abc', 'Gujarat', 'abc', '456738', 'uploads/67dec20da4eca_fruits.jpg', 'sbi', '777666555444', 'SBIN0000456', '2025-03-22 13:58:37');

-- --------------------------------------------------------

--
-- Table structure for table `farmer_certificate`
--

CREATE TABLE `farmer_certificate` (
  `id` int(11) NOT NULL,
  `farmer_name` varchar(255) NOT NULL,
  `certificate_number` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `farmer_certificate`
--

INSERT INTO `farmer_certificate` (`id`, `farmer_name`, `certificate_number`) VALUES
(1, 'Rajesh Patel', 'CERT12345'),
(2, 'Amit Sharma', 'CERT67890'),
(3, 'Sunita Mehta', 'CERT11223');

-- --------------------------------------------------------

--
-- Table structure for table `message`
--

CREATE TABLE `message` (
  `id` int(11) NOT NULL,
  `message` text NOT NULL,
  `added_on` datetime NOT NULL,
  `type` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `message`
--

INSERT INTO `message` (`id`, `message`, `added_on`, `type`) VALUES
(1, 'Hi', '2020-04-22 12:41:04', 'user'),
(2, 'Hello, how are you.', '2020-04-22 12:41:05', 'bot'),
(3, 'what is your name', '2020-04-22 12:41:22', 'user'),
(4, 'My name is Vishal Bot', '2020-04-22 12:41:22', 'bot'),
(5, 'Where are your from', '2020-04-22 12:41:30', 'user'),
(6, 'I m from India', '2020-04-22 12:41:30', 'bot'),
(7, 'Go to hell', '2020-04-22 12:41:41', 'user'),
(8, 'Sorry not be able to understand you', '2020-04-22 12:41:41', 'bot'),
(9, 'bye', '2020-04-22 12:41:46', 'user'),
(10, 'Sad to see you are going. Have a nice day', '2020-04-22 12:41:46', 'bot');

-- --------------------------------------------------------

--
-- Table structure for table `my_orders`
--

CREATE TABLE `my_orders` (
  `order_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `product_price` decimal(10,2) NOT NULL,
  `quantity` int(11) NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `status` varchar(50) DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `order_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `my_orders`
--

INSERT INTO `my_orders` (`order_id`, `customer_id`, `product_id`, `product_name`, `product_price`, `quantity`, `total_amount`, `status`, `created_at`, `order_date`) VALUES
(1, 1, 1, 'kesar mango', '200.00', 3, '600.00', 'Pending', '2025-03-23 00:18:17', '2025-03-23 00:21:46'),
(2, 1, 3, 'special banana', '60.00', 1, '60.00', 'Pending', '2025-03-23 00:51:06', '2025-03-23 00:51:06'),
(3, 1, 3, 'special banana', '60.00', 1, '60.00', 'Pending', '2025-03-23 00:53:05', '2025-03-23 00:53:05'),
(4, 1, 1, 'kesar mango', '200.00', 1, '200.00', 'Pending', '2025-03-23 03:10:30', '2025-03-23 03:10:30'),
(5, 1, 1, 'kesar mango', '200.00', 1, '220.00', 'Pending', '2025-03-23 03:11:41', '2025-03-23 03:11:41'),
(6, 1, 2, 'cherry tomato', '20.00', 1, '220.00', 'Pending', '2025-03-23 03:11:41', '2025-03-23 03:11:41'),
(7, 1, 1, 'kesar mango', '200.00', 1, '200.00', 'Pending', '2025-03-23 03:13:40', '2025-03-23 03:13:40'),
(8, 1, 1, 'kesar mango', '200.00', 1, '200.00', 'Pending', '2025-03-23 03:49:23', '2025-03-23 03:49:23');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `farmer_id` int(11) NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `order_status` enum('Pending','Processing','Shipped','Delivered') NOT NULL DEFAULT 'Pending',
  `payment_status` enum('Pending','Completed','Failed','Refunded') NOT NULL DEFAULT 'Pending',
  `order_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `payment_method` varchar(50) NOT NULL,
  `razorpay_payment_id` varchar(100) NOT NULL,
  `razorpay_order_id` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `customer_id`, `farmer_id`, `total_price`, `order_status`, `payment_status`, `order_date`, `payment_method`, `razorpay_payment_id`, `razorpay_order_id`) VALUES
(3, 1, 2, '200.00', 'Pending', '', '2025-03-22 21:25:42', 'Razorpay', 'pay_Q9yUoDAJpgA9MK', 'order_Q9yUOk3W3RNEi9');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `order_item_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`order_item_id`, `order_id`, `product_id`, `quantity`, `price`) VALUES
(1, 3, 1, 1, '200.00');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `product_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `sub_category_id` int(11) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `product_image` varchar(255) NOT NULL,
  `product_price` decimal(10,2) NOT NULL,
  `stock` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `farmer_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_id`, `category_id`, `sub_category_id`, `product_name`, `product_image`, `product_price`, `stock`, `created_at`, `farmer_id`) VALUES
(1, 1, 1, 'kesar mango', 'mango.jpg', '200.00', 20, '2025-03-22 13:44:38', 2),
(2, 2, 2, 'cherry tomato', 'tamato.jpg', '20.00', 10, '2025-03-22 13:46:08', 2),
(3, 1, 6, 'special banana', 'banana.jpg', '60.00', 20, '2025-03-22 13:59:24', 3);

-- --------------------------------------------------------

--
-- Table structure for table `ratings_reviews`
--

CREATE TABLE `ratings_reviews` (
  `review_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `rating` int(11) NOT NULL,
  `review_text` text DEFAULT NULL,
  `review_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ratings_reviews`
--

INSERT INTO `ratings_reviews` (`review_id`, `customer_id`, `product_id`, `rating`, `review_text`, `review_date`) VALUES
(1, 1, 1, 1, 'good', '2025-03-23 00:23:25'),
(2, 1, 1, 1, 'good', '2025-03-23 00:23:32'),
(3, 1, 2, 3, 'very nice', '2025-03-23 03:15:16'),
(4, 1, 2, 3, 'good', '2025-03-23 03:15:37'),
(5, 1, 1, 4, 'good', '2025-03-23 03:50:16'),
(6, 1, 1, 5, 'good', '2025-03-23 03:50:20'),
(7, 1, 1, 1, 'good', '2025-03-23 03:50:20'),
(8, 1, 1, 1, 'good', '2025-03-23 03:50:20');

-- --------------------------------------------------------

--
-- Table structure for table `subcategories`
--

CREATE TABLE `subcategories` (
  `sub_category_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `sub_category_name` varchar(255) NOT NULL,
  `sub_category_image` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `subcategories`
--

INSERT INTO `subcategories` (`sub_category_id`, `category_id`, `sub_category_name`, `sub_category_image`, `created_at`) VALUES
(1, 1, 'mango', 'mango.jpg', '2025-03-22 10:15:23'),
(2, 2, 'tamato', 'tamato.jpg', '2025-03-22 10:17:21'),
(3, 3, 'mung', 'mung.jpg', '2025-03-22 10:17:35'),
(4, 4, 'till', 'til.jpg', '2025-03-22 10:17:45'),
(5, 5, 'rice', 'rice.jpg', '2025-03-22 10:17:59'),
(6, 1, 'banana', 'banana.jpg', '2025-03-22 10:47:28'),
(7, 1, 'grapes', 'uploads/subcategories/Screenshot 2024-01-19 171429.png', '2025-03-23 01:22:15');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `chatbot_hints`
--
ALTER TABLE `chatbot_hints`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`customer_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `delivery_assignments`
--
ALTER TABLE `delivery_assignments`
  ADD PRIMARY KEY (`assignment_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `delivery_boy_id` (`delivery_boy_id`);

--
-- Indexes for table `delivery_boy`
--
ALTER TABLE `delivery_boy`
  ADD PRIMARY KEY (`delivery_boy_id`),
  ADD UNIQUE KEY `phone` (`phone`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `delivery_status`
--
ALTER TABLE `delivery_status`
  ADD PRIMARY KEY (`status_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `delivery_boy_id` (`delivery_boy_id`);

--
-- Indexes for table `farmers`
--
ALTER TABLE `farmers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `phone` (`phone`),
  ADD UNIQUE KEY `account_number` (`account_number`);

--
-- Indexes for table `farmer_certificate`
--
ALTER TABLE `farmer_certificate`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `certificate_number` (`certificate_number`);

--
-- Indexes for table `message`
--
ALTER TABLE `message`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `my_orders`
--
ALTER TABLE `my_orders`
  ADD PRIMARY KEY (`order_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `farmer_id` (`farmer_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`order_item_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`),
  ADD KEY `category_id` (`category_id`),
  ADD KEY `sub_category_id` (`sub_category_id`);

--
-- Indexes for table `ratings_reviews`
--
ALTER TABLE `ratings_reviews`
  ADD PRIMARY KEY (`review_id`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `subcategories`
--
ALTER TABLE `subcategories`
  ADD PRIMARY KEY (`sub_category_id`),
  ADD KEY `category_id` (`category_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `chatbot_hints`
--
ALTER TABLE `chatbot_hints`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `customer_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `farmers`
--
ALTER TABLE `farmers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `farmer_certificate`
--
ALTER TABLE `farmer_certificate`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `message`
--
ALTER TABLE `message`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `my_orders`
--
ALTER TABLE `my_orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `order_item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `ratings_reviews`
--
ALTER TABLE `ratings_reviews`
  MODIFY `review_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `subcategories`
--
ALTER TABLE `subcategories`
  MODIFY `sub_category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`customer_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`customer_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`farmer_id`) REFERENCES `farmers` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE CASCADE;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `products_ibfk_2` FOREIGN KEY (`sub_category_id`) REFERENCES `subcategories` (`sub_category_id`) ON DELETE CASCADE;

--
-- Constraints for table `ratings_reviews`
--
ALTER TABLE `ratings_reviews`
  ADD CONSTRAINT `ratings_reviews_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`customer_id`),
  ADD CONSTRAINT `ratings_reviews_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`);

--
-- Constraints for table `subcategories`
--
ALTER TABLE `subcategories`
  ADD CONSTRAINT `subcategories_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
