-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 29, 2025 at 09:16 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `skripsi`
--

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `credit_memos`
--

CREATE TABLE `credit_memos` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `credit_memos_id` varchar(255) NOT NULL,
  `return_id` varchar(255) NOT NULL,
  `amount` decimal(12,2) NOT NULL,
  `issued_date` date NOT NULL,
  `due_date` date DEFAULT NULL,
  `status` enum('DRAFT','ISSUED','PENDING_REFUND','REFUNDED','APPLIED','EXPIRED','REJECTED') NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dealers`
--

CREATE TABLE `dealers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `dealer_code` varchar(255) NOT NULL,
  `dealer_name` varchar(255) NOT NULL,
  `province` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `delivery_items`
--

CREATE TABLE `delivery_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `delivery_order_id` varchar(255) NOT NULL,
  `part_number` varchar(255) NOT NULL,
  `quantity` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `delivery_orders`
--

CREATE TABLE `delivery_orders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `delivery_order_id` varchar(255) NOT NULL,
  `sales_order_id` varchar(255) NOT NULL,
  `delivery_date` date NOT NULL,
  `status` enum('pending','ready','delivered','cancelled') DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `inventory`
--

CREATE TABLE `inventory` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` varchar(255) DEFAULT NULL,
  `location` varchar(100) DEFAULT NULL,
  `batch_number` varchar(100) DEFAULT NULL,
  `quantity_available` int(10) UNSIGNED DEFAULT NULL,
  `quantity_reserved` int(10) UNSIGNED DEFAULT 0,
  `quantity_damaged` int(10) UNSIGNED DEFAULT 0,
  `minimum_stock` int(10) DEFAULT 0,
  `last_updated` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `inventory_movements`
--

CREATE TABLE `inventory_movements` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `inventory_movement_id` varchar(255) NOT NULL,
  `product_id` varchar(255) DEFAULT NULL,
  `movement_type` enum('IN','OUT') DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `movement_date` datetime DEFAULT NULL,
  `reference_type` varchar(50) DEFAULT NULL,
  `reference_id` int(11) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `batch_number` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `invoices`
--

CREATE TABLE `invoices` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `outlet_code` varchar(255) NOT NULL,
  `service_id` varchar(255) NOT NULL,
  `invoice_no` varchar(255) NOT NULL,
  `invoice_date` date NOT NULL,
  `part_no_supplied` varchar(255) DEFAULT NULL,
  `part_desc_supplied` varchar(255) DEFAULT NULL,
  `supplied_qty` int(11) DEFAULT NULL,
  `part_sales_amount` decimal(10,2) DEFAULT NULL,
  `total_labor_price` decimal(10,2) DEFAULT NULL,
  `invoice_amount` decimal(10,2) NOT NULL,
  `payment_method` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `master_part`
--

CREATE TABLE `master_part` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `part_number` varchar(255) NOT NULL,
  `part_name` varchar(255) NOT NULL,
  `part_price` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `outlet_dealers`
--

CREATE TABLE `outlet_dealers` (
  `outlet_code` varchar(255) NOT NULL,
  `outlet_name` varchar(255) NOT NULL,
  `dealer_code` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `credit_limit` decimal(12,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `payment_id` varchar(255) NOT NULL,
  `invoice_id` varchar(255) NOT NULL,
  `payment_date` date NOT NULL,
  `amount_paid` decimal(12,2) NOT NULL,
  `payment_method` enum('Bank Transfer','Credit Note','e-Wallet','Cheque') DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_returns`
--

CREATE TABLE `product_returns` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `return_id` varchar(255) NOT NULL,
  `sales_order_id` varchar(255) NOT NULL,
  `part_number` varchar(255) NOT NULL,
  `quantity` int(11) NOT NULL,
  `return_date` date NOT NULL,
  `reason` enum('Wrong item delivered','Item defective on arrival','Customer changed mind','Packaging damaged') DEFAULT NULL,
  `condition` enum('GOOD','DAMAGED') NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `refund_action` enum('REFUND','CREDIT_MEMO') DEFAULT 'CREDIT_MEMO'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `quotations`
--

CREATE TABLE `quotations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `quotation_id` varchar(255) NOT NULL,
  `outlet_code` varchar(255) NOT NULL,
  `quotation_date` date NOT NULL,
  `status` enum('Approved','Rejected','Pending') NOT NULL,
  `total_amount` decimal(12,2) NOT NULL,
  `valid_until` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `quotation_items`
--

CREATE TABLE `quotation_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `quotation_id` varchar(255) NOT NULL,
  `sub_part_number` varchar(255) DEFAULT NULL,
  `quantity` int(11) NOT NULL,
  `unit_price` decimal(12,2) NOT NULL,
  `subtotal` decimal(12,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sales`
--

CREATE TABLE `sales` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `month` varchar(255) NOT NULL,
  `year` year(4) NOT NULL,
  `revenue` int(11) NOT NULL,
  `sales` int(11) NOT NULL,
  `orders` int(11) NOT NULL,
  `returns` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sales_orders`
--

CREATE TABLE `sales_orders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `sales_order_id` varchar(255) NOT NULL,
  `customer_id` varchar(255) DEFAULT NULL,
  `quotation_id` varchar(255) DEFAULT NULL,
  `order_date` date DEFAULT NULL,
  `status` enum('draft','confirmed','delivered','rejected') DEFAULT NULL,
  `total_amount` decimal(12,2) DEFAULT NULL,
  `delivery_address` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sales_order_items`
--

CREATE TABLE `sales_order_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `sales_order_id` varchar(255) NOT NULL,
  `part_number` varchar(255) NOT NULL,
  `quantity` int(11) NOT NULL,
  `unit_price` decimal(12,2) NOT NULL,
  `subtotal` decimal(12,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `stock_reservations`
--

CREATE TABLE `stock_reservations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `part_number` varchar(255) DEFAULT NULL,
  `sales_order_id` varchar(255) DEFAULT NULL,
  `reserved_quantity` int(11) DEFAULT NULL,
  `reservation_date` datetime DEFAULT NULL,
  `status` enum('ACTIVE','RELEASED') DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sub_parts`
--

CREATE TABLE `sub_parts` (
  `sub_part_number` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `sub_part_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `part_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` decimal(12,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `transaction`
--

CREATE TABLE `transaction` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `invoice_id` varchar(255) NOT NULL,
  `sales_order_id` varchar(255) NOT NULL,
  `invoice_date` date NOT NULL,
  `due_date` date NOT NULL,
  `status` enum('unpaid','paid') NOT NULL,
  `total_amount` decimal(12,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `_service_`
--

CREATE TABLE `_service_` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `outlet_code` varchar(255) DEFAULT NULL,
  `service_code` varchar(255) DEFAULT NULL,
  `service_date` date DEFAULT NULL,
  `kilometer` int(11) DEFAULT NULL,
  `service_type` varchar(255) DEFAULT NULL,
  `car_id` varchar(255) DEFAULT NULL,
  `car_model` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `credit_memos`
--
ALTER TABLE `credit_memos`
  ADD PRIMARY KEY (`credit_memos_id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `fk_credit_memos_return` (`return_id`);

--
-- Indexes for table `dealers`
--
ALTER TABLE `dealers`
  ADD PRIMARY KEY (`dealer_code`),
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `delivery_items`
--
ALTER TABLE `delivery_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_delivery_items_delivery_order` (`delivery_order_id`),
  ADD KEY `fk_delivery_items_part_number` (`part_number`);

--
-- Indexes for table `delivery_orders`
--
ALTER TABLE `delivery_orders`
  ADD PRIMARY KEY (`delivery_order_id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `fk_delivery_order_sales_order_id` (`sales_order_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `inventory`
--
ALTER TABLE `inventory`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_inventory_product` (`product_id`);

--
-- Indexes for table `inventory_movements`
--
ALTER TABLE `inventory_movements`
  ADD PRIMARY KEY (`inventory_movement_id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `fk_inventory_movements_product` (`product_id`);

--
-- Indexes for table `invoices`
--
ALTER TABLE `invoices`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `invoices_invoice_no_unique` (`invoice_no`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `master_part`
--
ALTER TABLE `master_part`
  ADD PRIMARY KEY (`part_number`),
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `outlet_dealers`
--
ALTER TABLE `outlet_dealers`
  ADD PRIMARY KEY (`outlet_code`),
  ADD KEY `fk_dealers_dealers_code` (`dealer_code`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_payment_invoice` (`invoice_id`);

--
-- Indexes for table `product_returns`
--
ALTER TABLE `product_returns`
  ADD PRIMARY KEY (`return_id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `fk_returns_sales_order` (`sales_order_id`),
  ADD KEY `fk_returns_product` (`part_number`);

--
-- Indexes for table `quotations`
--
ALTER TABLE `quotations`
  ADD PRIMARY KEY (`quotation_id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `fk_quotations_outlet_code` (`outlet_code`);

--
-- Indexes for table `quotation_items`
--
ALTER TABLE `quotation_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_quotation_items_quotation_id` (`quotation_id`),
  ADD KEY `fk_quotation_items_part_number` (`sub_part_number`);

--
-- Indexes for table `sales`
--
ALTER TABLE `sales`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sales_orders`
--
ALTER TABLE `sales_orders`
  ADD PRIMARY KEY (`sales_order_id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `fk_sales_order_quotation_id` (`quotation_id`),
  ADD KEY `fk_sales_order_customer_id` (`customer_id`);

--
-- Indexes for table `sales_order_items`
--
ALTER TABLE `sales_order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_sales_order_items_order_id` (`sales_order_id`),
  ADD KEY `fk_sales_order_items_product` (`part_number`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `stock_reservations`
--
ALTER TABLE `stock_reservations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_stock_reservations_sales_order` (`sales_order_id`),
  ADD KEY `fk_stock_reservations_part_number` (`part_number`);

--
-- Indexes for table `sub_parts`
--
ALTER TABLE `sub_parts`
  ADD PRIMARY KEY (`sub_part_number`),
  ADD KEY `fk_subpart_part_number` (`part_number`);

--
-- Indexes for table `transaction`
--
ALTER TABLE `transaction`
  ADD PRIMARY KEY (`invoice_id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `fk_invoice_sales_order` (`sales_order_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Indexes for table `_service_`
--
ALTER TABLE `_service_`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `credit_memos`
--
ALTER TABLE `credit_memos`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `dealers`
--
ALTER TABLE `dealers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `delivery_items`
--
ALTER TABLE `delivery_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `delivery_orders`
--
ALTER TABLE `delivery_orders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `inventory`
--
ALTER TABLE `inventory`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `inventory_movements`
--
ALTER TABLE `inventory_movements`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `invoices`
--
ALTER TABLE `invoices`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `master_part`
--
ALTER TABLE `master_part`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product_returns`
--
ALTER TABLE `product_returns`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `quotations`
--
ALTER TABLE `quotations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `quotation_items`
--
ALTER TABLE `quotation_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sales`
--
ALTER TABLE `sales`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sales_orders`
--
ALTER TABLE `sales_orders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sales_order_items`
--
ALTER TABLE `sales_order_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stock_reservations`
--
ALTER TABLE `stock_reservations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `transaction`
--
ALTER TABLE `transaction`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `_service_`
--
ALTER TABLE `_service_`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `credit_memos`
--
ALTER TABLE `credit_memos`
  ADD CONSTRAINT `fk_credit_memos_return` FOREIGN KEY (`return_id`) REFERENCES `product_returns` (`return_id`);

--
-- Constraints for table `delivery_items`
--
ALTER TABLE `delivery_items`
  ADD CONSTRAINT `fk_delivery_items_delivery_order` FOREIGN KEY (`delivery_order_id`) REFERENCES `delivery_orders` (`delivery_order_id`),
  ADD CONSTRAINT `fk_delivery_items_part_number` FOREIGN KEY (`part_number`) REFERENCES `sub_parts` (`sub_part_number`);

--
-- Constraints for table `delivery_orders`
--
ALTER TABLE `delivery_orders`
  ADD CONSTRAINT `fk_delivery_order_sales_order_id` FOREIGN KEY (`sales_order_id`) REFERENCES `sales_orders` (`sales_order_id`);

--
-- Constraints for table `inventory`
--
ALTER TABLE `inventory`
  ADD CONSTRAINT `fk_inventory_product` FOREIGN KEY (`product_id`) REFERENCES `sub_parts` (`sub_part_number`);

--
-- Constraints for table `inventory_movements`
--
ALTER TABLE `inventory_movements`
  ADD CONSTRAINT `fk_inventory_movements_product` FOREIGN KEY (`product_id`) REFERENCES `sub_parts` (`sub_part_number`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `outlet_dealers`
--
ALTER TABLE `outlet_dealers`
  ADD CONSTRAINT `fk_dealers_dealers_code` FOREIGN KEY (`dealer_code`) REFERENCES `dealers` (`dealer_code`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `fk_payment_invoice` FOREIGN KEY (`invoice_id`) REFERENCES `transaction` (`invoice_id`);

--
-- Constraints for table `product_returns`
--
ALTER TABLE `product_returns`
  ADD CONSTRAINT `fk_returns_product` FOREIGN KEY (`part_number`) REFERENCES `sub_parts` (`sub_part_number`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_returns_sales_order` FOREIGN KEY (`sales_order_id`) REFERENCES `sales_orders` (`sales_order_id`);

--
-- Constraints for table `quotations`
--
ALTER TABLE `quotations`
  ADD CONSTRAINT `fk_quotations_outlet_code` FOREIGN KEY (`outlet_code`) REFERENCES `outlet_dealers` (`outlet_code`) ON UPDATE CASCADE;

--
-- Constraints for table `quotation_items`
--
ALTER TABLE `quotation_items`
  ADD CONSTRAINT `fk_quotation_items_part_number` FOREIGN KEY (`sub_part_number`) REFERENCES `sub_parts` (`sub_part_number`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_quotation_items_quotation_id` FOREIGN KEY (`quotation_id`) REFERENCES `quotations` (`quotation_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `sales_orders`
--
ALTER TABLE `sales_orders`
  ADD CONSTRAINT `fk_sales_order_customer_id` FOREIGN KEY (`customer_id`) REFERENCES `outlet_dealers` (`outlet_code`),
  ADD CONSTRAINT `fk_sales_order_quotation_id` FOREIGN KEY (`quotation_id`) REFERENCES `quotations` (`quotation_id`);

--
-- Constraints for table `sales_order_items`
--
ALTER TABLE `sales_order_items`
  ADD CONSTRAINT `fk_sales_order_items_order_id` FOREIGN KEY (`sales_order_id`) REFERENCES `sales_orders` (`sales_order_id`),
  ADD CONSTRAINT `fk_sales_order_items_product` FOREIGN KEY (`part_number`) REFERENCES `sub_parts` (`sub_part_number`);

--
-- Constraints for table `stock_reservations`
--
ALTER TABLE `stock_reservations`
  ADD CONSTRAINT `fk_stock_reservations_part_number` FOREIGN KEY (`part_number`) REFERENCES `sub_parts` (`sub_part_number`),
  ADD CONSTRAINT `fk_stock_reservations_sales_order` FOREIGN KEY (`sales_order_id`) REFERENCES `sales_orders` (`sales_order_id`);

--
-- Constraints for table `sub_parts`
--
ALTER TABLE `sub_parts`
  ADD CONSTRAINT `fk_subpart_part_number` FOREIGN KEY (`part_number`) REFERENCES `master_part` (`part_number`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `transaction`
--
ALTER TABLE `transaction`
  ADD CONSTRAINT `fk_invoice_sales_order` FOREIGN KEY (`sales_order_id`) REFERENCES `sales_orders` (`sales_order_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
