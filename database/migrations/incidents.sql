-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 08, 2024 at 09:42 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `capstone`
--

-- --------------------------------------------------------

--
-- Table structure for table `incidents`
--

CREATE TABLE `incidents` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `reporter` varchar(255) NOT NULL,
  `contact` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `lat` varchar(255) NOT NULL,
  `long` varchar(255) NOT NULL,
  `incident` bigint(20) UNSIGNED NOT NULL,
  `eventdesc` varchar(255) DEFAULT NULL,
  `imagedir` varchar(255) DEFAULT NULL,
  `status` enum('pending','ongoing','resolved','dismissed') NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `incidents`
--

INSERT INTO `incidents` ( `reporter`, `contact`, `address`, `lat`, `long`, `incident`, `eventdesc`, `imagedir`, `status`, `created_at`, `updated_at`) VALUES
( 'Raymart Paraiso', '09383025631', 'Ubaldo Avenue, Iligan, 9200 Northern Mindanao, Philippines', '8.233661333333334', '124.25007066666669', 1, 'Naligsan daw si Layla', '1711353621.jpg', 'ongoing', '2024-03-25 00:00:21', '2024-04-05 23:44:18'),
( 'Raymart Paraiso', '09383025631', 'Ubaldo Laya Avenue, Iligan, 9200 Northern Mindanao, Philippines', '8.23365709371387', '124.24998971221156', 2, 'Test Trauma Case', '1711367863.jpg', 'ongoing', '2024-03-25 03:57:43', '2024-04-05 23:45:08'),
('Raymart Paraiso', '09383025631', 'Octagon Dapit, Mariano Badelles Sr. Street, Iligan, 9200 Northern Mindanao, Philippines', '8.229656717023705', '124.23780751002661', 2, 'taka lang angkol', '1711431375.png', 'pending', '2024-03-25 21:36:15', '2024-04-05 23:39:34'),
( 'Jonaem Azis', '09123456789', 'De Leon Street, Iligan, 9200 Northern Mindanao, Philippines', '8.229621441407783', '124.23803318301754', 2, 'hatdog duha', '1711436815.jpg', 'pending', '2024-03-25 23:06:55', '2024-04-05 19:55:55'),
( 'Jonaem Azis', '09123456789', 'Ubaldo Laya Avenue, Iligan, 9200 Northern Mindanao, Philippines', '8.2335929', '124.25004520000002', 4, 'test water search daw', '1711794522.png', 'pending', '2024-03-30 02:28:42', '2024-04-05 19:55:56'),
( 'Jonaem Azis', '09123456789', 'Ubaldo Laya Avenue, Iligan, 9200 Northern Mindanao, Philippines', '8.23362475', '124.250019', 1, 'test mdedical case', '1711794666.jpg', 'pending', '2024-03-30 02:31:06', '2024-03-31 14:07:12'),
( 'Jonaem Azis', '09123456789', 'Ubaldo Laya Avenue, Iligan, 9200 Northern Mindanao, Philippines', '8.2335929', '124.25004520000002', 2, 'asdasd', '1711794775.png', 'pending', '2024-03-30 02:32:55', '2024-04-05 19:55:57'),
( 'Jonaem Azis', '09123456789', 'Ubaldo Laya Avenue, Iligan, 9200 Northern Mindanao, Philippines', '8.2335929', '124.25004520000002', 3, 'asdasd', '1711794989.png', 'pending', '2024-03-30 02:36:29', '2024-03-31 14:07:15'),
( 'Jonaem Azis', '09123456789', 'Camp Fermin G. Lira, P. Acharon Extension, General Santos, 9500 Soccsksargen, Philippines', '6.1098224', '125.1664793', 1, '..', '1712153248.jpg', 'pending', '2024-04-03 06:07:28', '2024-04-05 19:06:00'),
( 'Jonaem Azis', '09123456789', 'Ubaldo Laya Avenue, Iligan, 9200 Northern Mindanao, Philippines', '8.2337173', '124.2499773', 1, 'Hràgragearq', '1712375274.jpg', 'pending', '2024-04-05 19:47:54', '2024-04-05 19:47:54');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `incidents`
--
ALTER TABLE `incidents`
  ADD PRIMARY KEY (`id`),
  ADD KEY `incidents_incident_foreign` (`incident`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `incidents`
--
ALTER TABLE `incidents`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `incidents`
--
ALTER TABLE `incidents`
  ADD CONSTRAINT `incidents_incident_foreign` FOREIGN KEY (`incident`) REFERENCES `case_types` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
