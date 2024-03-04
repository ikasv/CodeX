-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jan 24, 2024 at 05:39 AM
-- Server version: 10.3.39-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `knch_knchomedecor`
--

-- --------------------------------------------------------

--
-- Table structure for table `states`
--

CREATE TABLE `states` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `code` varchar(255) DEFAULT NULL,
  `payroll_tax` decimal(10,2) NOT NULL DEFAULT 0.00,
  `status` tinyint(4) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `states`
--

INSERT INTO `states` (`id`, `name`, `code`, `payroll_tax`, `status`, `created_at`, `updated_at`) VALUES
(1, 'ANDHRA PRADESH', NULL, 0.00, 0, NULL, NULL),
(2, 'ASSAM', NULL, 0.00, 0, NULL, NULL),
(3, 'ARUNACHAL PRADESH', NULL, 0.00, 0, NULL, NULL),
(4, 'BIHAR', NULL, 0.00, 0, NULL, NULL),
(5, 'GUJRAT', NULL, 0.00, 0, NULL, NULL),
(6, 'HARYANA', NULL, 0.00, 0, NULL, NULL),
(7, 'HIMACHAL PRADESH', NULL, 0.00, 0, NULL, NULL),
(8, 'JAMMU & KASHMIR', NULL, 0.00, 0, NULL, NULL),
(9, 'KARNATAKA', NULL, 0.00, 0, NULL, NULL),
(10, 'KERALA', NULL, 0.00, 0, NULL, NULL),
(11, 'MADHYA PRADESH', NULL, 0.00, 0, NULL, NULL),
(12, 'MAHARASHTRA', NULL, 0.00, 0, NULL, NULL),
(13, 'MANIPUR', NULL, 0.00, 0, NULL, NULL),
(14, 'MEGHALAYA', NULL, 0.00, 0, NULL, NULL),
(15, 'MIZORAM', NULL, 0.00, 0, NULL, NULL),
(16, 'NAGALAND', NULL, 0.00, 0, NULL, NULL),
(17, 'ORISSA', NULL, 0.00, 0, NULL, NULL),
(18, 'PUNJAB', NULL, 0.00, 0, NULL, NULL),
(19, 'RAJASTHAN', NULL, 0.00, 0, NULL, NULL),
(20, 'SIKKIM', NULL, 0.00, 0, NULL, NULL),
(21, 'TAMIL NADU', NULL, 0.00, 0, NULL, NULL),
(22, 'TRIPURA', NULL, 0.00, 0, NULL, NULL),
(23, 'UTTAR PRADESH', NULL, 0.00, 0, NULL, NULL),
(24, 'WEST BENGAL', NULL, 0.00, 0, NULL, NULL),
(25, 'DELHI', NULL, 0.00, 0, NULL, NULL),
(26, 'GOA', NULL, 0.00, 0, NULL, NULL),
(27, 'PONDICHERY', NULL, 0.00, 0, NULL, NULL),
(28, 'LAKSHDWEEP', NULL, 0.00, 0, NULL, NULL),
(29, 'DAMAN & DIU', NULL, 0.00, 0, NULL, NULL),
(30, 'DADRA & NAGAR', NULL, 0.00, 0, NULL, NULL),
(31, 'CHANDIGARH', NULL, 0.00, 0, NULL, NULL),
(32, 'ANDAMAN & NICOBAR', NULL, 0.00, 0, NULL, NULL),
(33, 'UTTARANCHAL', NULL, 0.00, 0, NULL, NULL),
(34, 'JHARKHAND', NULL, 0.00, 0, NULL, NULL),
(35, 'CHATTISGARH', NULL, 0.00, 0, NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `states`
--
ALTER TABLE `states`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `states`
--
ALTER TABLE `states`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
