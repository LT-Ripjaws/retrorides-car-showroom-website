-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 16, 2025 at 10:14 AM
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
-- Database: `retrorides_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `booking_id` int(11) NOT NULL,
  `car_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `customer_name` varchar(100) NOT NULL,
  `customer_email` varchar(100) NOT NULL,
  `booking_date` datetime DEFAULT current_timestamp(),
  `status` enum('processing','sold','cancelled') DEFAULT 'processing'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`booking_id`, `car_id`, `user_id`, `customer_name`, `customer_email`, `booking_date`, `status`) VALUES
(24, 6, 7, 'Customer', 'customer@gmail.com', '2025-10-14 21:03:36', 'sold'),
(25, 6, 7, 'Customer', 'customer@gmail.com', '2025-10-14 21:19:41', 'sold'),
(26, 2, 7, 'Customer', 'customer@gmail.com', '2025-10-14 21:21:52', 'processing'),
(27, 7, 7, 'DummyCustomer', 'customer@gmail.com', '2025-10-16 13:01:20', 'cancelled');

-- --------------------------------------------------------

--
-- Table structure for table `cars`
--

CREATE TABLE `cars` (
  `car_id` int(11) NOT NULL,
  `vin` varchar(17) NOT NULL,
  `brand` varchar(50) NOT NULL,
  `model` varchar(50) NOT NULL,
  `year` year(4) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `status` enum('available','sold','maintenance') DEFAULT 'available',
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cars`
--

INSERT INTO `cars` (`car_id`, `vin`, `brand`, `model`, `year`, `price`, `status`, `description`, `image`, `created_at`) VALUES
(2, 'CARVER1MER', 'mercedes-benz', '300sl gullwing', '1956', 5000.00, 'available', 'description', 'mercedes-benz 300sl gullwing.png', '2025-09-04 15:02:55'),
(3, 'FORD121', 'ford', 'Mustang Fastback', '1967', 10000.00, 'available', 'The first-generation Ford Mustang was manufactured by Ford from March 1964 until 1973. The introduction of the Mustang created a new class of automobiles known as pony cars. The Mustang\'s styling, with its long hood and short deck, proved wildly popular and inspired a host of competition.', '1967 Ford Mustang Fastback.png', '2025-09-13 07:55:18'),
(4, 'CADA669', 'cadillac', 'eldorado', '1959', 2000.00, 'available', 'The Cadillac Eldorado is a luxury car manufactured and marketed by the Cadillac Motor Car Division of General Motors from 1952 until 2002, over twelve generations.\r\nThe Eldorado was at or near the top of the Cadillac product line. The original 1953 Eldorado convertible and the Eldorado Brougham models of 1957–1960 had distinct bodyshells and were the most expensive models offered by Cadillac during those years. ', '1959 cadillac eldorado.png', '2025-09-13 07:56:29'),
(6, 'VOLKS121', 'Volkswagen', 'Beetle', '1962', 12000.00, 'available', 'Beetle', 'car_68d3ec399002e5.66008534.png', '2025-09-24 13:03:53'),
(7, 'CEVRO121', 'Chevrolet', 'Corvette Stingray', '1964', 19200.00, 'available', '1964 Chevrolet Corvette Stingray', 'car_68d3ecfba81660.94454601.png', '2025-09-24 13:07:07');

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

CREATE TABLE `employees` (
  `employee_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `role` enum('admin','sales','mechanic','support','other') NOT NULL DEFAULT 'other',
  `department` varchar(50) DEFAULT NULL,
  `joined` date NOT NULL,
  `status` enum('active','inactive','leave') NOT NULL DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employees`
--

INSERT INTO `employees` (`employee_id`, `name`, `email`, `password`, `phone`, `role`, `department`, `joined`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Root Admin', 'admin@retrorides.com', '$2y$10$MUGpMv7GV9/zrPWsFEMD1.5qp1cMSCfYAIvKW5Gh1Ioe48tTII4RW', '0000000000', 'admin', 'admin', '2025-09-02', 'active', '2025-09-02 15:26:19', '2025-09-26 11:23:55'),
(6, 'MD. Ansar Uddin', 'ansar@retrorides.com', '$2y$10$/hq14g8Wf8G05BX3M9XXHeZZyHRmO4a6KY2.hY0LGw2wmL1rlMN8u', '00101010', 'sales', 'sales', '2025-09-05', 'active', '2025-09-05 15:38:17', '2025-10-01 11:11:10');

-- --------------------------------------------------------

--
-- Table structure for table `remember_me`
--

CREATE TABLE `remember_me` (
  `user_id` int(11) NOT NULL,
  `role` enum('admin','sales','customer','mechanic') NOT NULL,
  `token` char(64) NOT NULL,
  `expires_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('customer','premium','vip') DEFAULT 'customer',
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `role`, `status`, `created_at`, `updated_at`) VALUES
(7, 'DummyCustomer', 'customer@gmail.com', '$2y$10$./2Db.QFvyb7O8GMOKumveZ12cmeYyAd2ESu2Jm1S4iFiSm2ysR1G', 'customer', 'active', '2025-10-11 08:13:52', '2025-10-15 11:53:06');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`booking_id`),
  ADD KEY `car_id` (`car_id`);

--
-- Indexes for table `cars`
--
ALTER TABLE `cars`
  ADD PRIMARY KEY (`car_id`),
  ADD UNIQUE KEY `vin` (`vin`);

--
-- Indexes for table `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`employee_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `remember_me`
--
ALTER TABLE `remember_me`
  ADD PRIMARY KEY (`user_id`,`role`),
  ADD KEY `token` (`token`);

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
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `booking_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `cars`
--
ALTER TABLE `cars`
  MODIFY `car_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `employees`
--
ALTER TABLE `employees`
  MODIFY `employee_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_ibfk_1` FOREIGN KEY (`car_id`) REFERENCES `cars` (`car_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
