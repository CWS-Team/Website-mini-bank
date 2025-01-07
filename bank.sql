-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 07, 2024 at 01:30 PM
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
-- Database: `bank`
--

-- --------------------------------------------------------

--
-- Table structure for table `m_customer`
--

CREATE TABLE `m_customer` (
  `id` bigint(20) NOT NULL,
  `customer_name` varchar(30) NOT NULL,
  `customer_username` varchar(50) NOT NULL,
  `customer_pin` varchar(200) NOT NULL,
  `customer_phone` varchar(20) DEFAULT NULL,
  `customer_email` varchar(50) DEFAULT NULL,
  `cif_number` varchar(30) DEFAULT NULL,
  `failed_login_attempts` int(11) DEFAULT 0,
  `failed_ib_token_attempts` int(11) DEFAULT 0,
  `failed_mb_token_attempts` int(11) DEFAULT 0,
  `ib_status` char(1) DEFAULT NULL,
  `mb_status` char(1) DEFAULT NULL,
  `previous_ib_status` char(1) DEFAULT NULL,
  `previous_mb_status` char(1) DEFAULT NULL,
  `last_login` timestamp NULL DEFAULT NULL,
  `last_token_id` varchar(50) DEFAULT NULL,
  `registration_card_number` varchar(20) DEFAULT NULL,
  `created` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_by` bigint(20) NOT NULL DEFAULT 1,
  `updated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_by` bigint(20) NOT NULL DEFAULT 1,
  `m_customer_group_id` bigint(20) NOT NULL DEFAULT 1,
  `auto_close_date` timestamp NULL DEFAULT NULL,
  `last_link_token` timestamp NULL DEFAULT NULL,
  `user_link_token` varchar(20) DEFAULT NULL,
  `spv_link_token` varchar(20) DEFAULT NULL,
  `token_type` char(1) DEFAULT NULL,
  `registration_account_number` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `m_parameter`
--

CREATE TABLE `m_parameter` (
  `id` bigint(20) NOT NULL,
  `parameter_name` varchar(30) DEFAULT NULL,
  `parameter_value` varchar(200) DEFAULT NULL,
  `description` varchar(500) DEFAULT NULL,
  `created` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_by` bigint(20) NOT NULL DEFAULT 1,
  `updated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_by` bigint(20) NOT NULL DEFAULT 1,
  `access_type` int(11) DEFAULT NULL,
  `parameter_value_binary` blob DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `m_portfolio_account`
--

CREATE TABLE `m_portfolio_account` (
  `id` bigint(20) NOT NULL,
  `m_customer_id` bigint(20) DEFAULT NULL,
  `account_number` varchar(20) DEFAULT NULL,
  `account_status` char(1) DEFAULT NULL,
  `account_name` varchar(50) DEFAULT NULL,
  `account_type` varchar(10) DEFAULT NULL,
  `product_code` varchar(10) DEFAULT NULL,
  `product_name` varchar(50) DEFAULT NULL,
  `currency_code` char(3) DEFAULT NULL,
  `branch_code` varchar(10) DEFAULT NULL,
  `plafond` decimal(30,5) DEFAULT NULL,
  `clear_balance` decimal(30,5) DEFAULT NULL,
  `available_balance` decimal(30,5) DEFAULT NULL,
  `confidential` char(1) DEFAULT NULL,
  `created` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_by` bigint(20) NOT NULL DEFAULT 1,
  `updated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_by` bigint(20) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `t_transaction`
--

CREATE TABLE `t_transaction` (
  `id` bigint(20) NOT NULL,
  `m_customer_id` bigint(20) NOT NULL,
  `mti` char(4) DEFAULT NULL,
  `transaction_type` char(2) NOT NULL DEFAULT '',
  `card_number` varchar(20) DEFAULT NULL,
  `transaction_amount` decimal(30,5) DEFAULT NULL,
  `fee_indicator` char(1) DEFAULT NULL,
  `fee` decimal(30,5) DEFAULT NULL,
  `transmission_date` timestamp NULL DEFAULT NULL,
  `transaction_date` timestamp NULL DEFAULT NULL,
  `value_date` timestamp NULL DEFAULT NULL,
  `conversion_rate` decimal(30,5) DEFAULT NULL,
  `stan` char(6) DEFAULT NULL,
  `merchant_type` char(4) DEFAULT NULL,
  `terminal_id` char(8) DEFAULT NULL,
  `reference_number` char(12) DEFAULT NULL,
  `approval_number` char(12) DEFAULT NULL,
  `response_code` char(2) DEFAULT NULL,
  `currency_code` char(3) DEFAULT NULL,
  `customer_reference` varchar(50) DEFAULT NULL,
  `biller_name` varchar(50) DEFAULT NULL,
  `from_account_number` varchar(20) DEFAULT NULL,
  `to_account_number` varchar(20) DEFAULT NULL,
  `from_account_type` char(2) DEFAULT '00',
  `to_account_type` char(2) DEFAULT '00',
  `balance` varchar(100) DEFAULT NULL,
  `description` varchar(250) DEFAULT NULL,
  `to_bank_code` char(3) DEFAULT NULL,
  `execution_type` char(10) NOT NULL DEFAULT 'N',
  `status` varchar(10) NOT NULL,
  `translation_code` text DEFAULT NULL,
  `free_data1` text DEFAULT NULL,
  `free_data2` text DEFAULT NULL,
  `free_data3` text DEFAULT NULL,
  `free_data4` text DEFAULT NULL,
  `free_data5` text DEFAULT NULL,
  `delivery_channel` varchar(10) DEFAULT NULL,
  `delivery_channel_id` varchar(50) DEFAULT NULL,
  `created` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_by` bigint(20) NOT NULL DEFAULT 1,
  `updated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_by` bigint(20) NOT NULL DEFAULT 1,
  `archive` tinyint(1) DEFAULT 0,
  `t_transaction_queue_id` bigint(20) DEFAULT NULL,
  `biller_id` varchar(20) DEFAULT NULL,
  `product_id` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `t_transaction_data`
--

CREATE TABLE `t_transaction_data` (
  `id` bigint(20) NOT NULL,
  `t_transaction_id` bigint(20) NOT NULL,
  `class_name` varchar(100) DEFAULT NULL,
  `transaction_data` text DEFAULT NULL,
  `created` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_by` bigint(20) NOT NULL DEFAULT 1,
  `updated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_by` bigint(20) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `m_customer`
--
ALTER TABLE `m_customer`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `customer_username` (`customer_username`),
  ADD UNIQUE KEY `customer_email` (`customer_email`);

--
-- Indexes for table `m_parameter`
--
ALTER TABLE `m_parameter`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `m_portfolio_account`
--
ALTER TABLE `m_portfolio_account`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `t_transaction`
--
ALTER TABLE `t_transaction`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `t_transaction_data`
--
ALTER TABLE `t_transaction_data`
  ADD PRIMARY KEY (`id`),
  ADD KEY `t_transaction_id` (`t_transaction_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `m_customer`
--
ALTER TABLE `m_customer`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `m_parameter`
--
ALTER TABLE `m_parameter`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `m_portfolio_account`
--
ALTER TABLE `m_portfolio_account`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `t_transaction`
--
ALTER TABLE `t_transaction`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `t_transaction_data`
--
ALTER TABLE `t_transaction_data`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `t_transaction_data`
--
ALTER TABLE `t_transaction_data`
  ADD CONSTRAINT `t_transaction_data_ibfk_1` FOREIGN KEY (`t_transaction_id`) REFERENCES `t_transaction` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
