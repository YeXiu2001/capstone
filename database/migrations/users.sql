-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 09, 2024 at 01:56 PM
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
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `contact` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `status` enum('pending','approved','declined') NOT NULL DEFAULT 'pending',
  `id_card` varchar(255) DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `contact`, `email_verified_at`, `password`, `status`, `id_card`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Raymart Paraiso', 'raymart@gmail.com', '09383025631', NULL, '$2y$12$DLpLZ5aYp/PuZTwuBSup0OAgL16QtucJfuZsXaLum.GYWUOTxZ3uK', 'approved', NULL, NULL, '2024-04-08 11:44:59', '2024-04-08 11:44:59'),
(2, 'Jonaem Azis', 'jonaem@gmail.com', '09123456789', NULL, '$2y$12$HatO/DkGM2VZSB1C7NhwuOHH7FFPhEAvpw8fMI6LZrVajbYtssEqO', 'approved', NULL, NULL, '2024-04-08 11:44:59', '2024-04-08 11:44:59'),
(10, 'test', 'test@gmail.com', '12345678909', NULL, '$2y$12$9IpjXsXmn.FTxDXlP88GKee86cBpNoh.YjU22dqWRcRa1GlSxDL2y', 'pending', '1712607388.jpg', NULL, '2024-04-08 12:16:28', '2024-04-08 12:16:28'),
(11, 'MFaheem Azis', 'mfbading@gmail.com', '09765432313', NULL, '$2y$12$DWVdh95Yvt7pOOIPIQRVvOhVujvnpgyd19ivHOppUjLSyLRTUC412', 'approved', '1712634265.png', NULL, '2024-04-08 19:44:25', '2024-04-08 19:44:25');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD UNIQUE KEY `users_contact_unique` (`contact`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
