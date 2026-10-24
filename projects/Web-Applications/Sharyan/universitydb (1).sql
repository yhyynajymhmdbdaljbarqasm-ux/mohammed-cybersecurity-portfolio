-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: 18 سبتمبر 2026 الساعة 23:53
-- إصدار الخادم: 10.4.32-MariaDB
-- PHP Version: 8.1.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `universitydb`
--

-- --------------------------------------------------------

--
-- بنية الجدول `equipment`
--

CREATE TABLE `equipment` (
  `id` int(11) NOT NULL,
  `eq_name` varchar(150) NOT NULL,
  `description` text NOT NULL,
  `daily_price` decimal(10,2) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `gps_link` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- إرجاع أو استيراد بيانات الجدول `equipment`
--

INSERT INTO `equipment` (`id`, `eq_name`, `description`, `daily_price`, `image`, `gps_link`) VALUES
(2, 'حفااار', 'حفار جنزير', 1000.00, '1789230681_pakkk.jpg', 'https://maps.app.goo.gl/RgKKNYkL6yLhbwhd8?g_st=abt'),
(3, 'شيول', 'شيول متوسط', 800.00, '1789767352_shy.jpg', 'https://maps.app.goo.gl/rimJA2qi5v6xGdTq6'),
(4, 'مولفت', '40 متر', 1000.00, '1789767409_lll (1).jpg', 'https://maps.app.goo.gl/RgKKNYkL6yLhbwhd8?g_st=abt'),
(5, 'سيزر لفت', '20 متر', 800.00, '1789767692_Screenshot_٢٠٢٥١٢١٧_٢٣٠٧٥٨.jpg', 'https://maps.app.goo.gl/RgKKNYkL6yLhbwhd8?g_st=abt'),
(6, 'رافعه', '5 طن', 500.00, '1789767749_sak.jpg', 'https://maps.app.goo.gl/RgKKNYkL6yLhbwhd8?g_st=abt'),
(7, 'كرين', '7 طن', 700.00, '1789767803_IMG-20221208-WA0003_70240238013759.jpg', 'https://maps.app.goo.gl/RgKKNYkL6yLhbwhd8?g_st=abt'),
(8, 'حفااار توير', 'حفااار توير', 1100.00, '1789767892_pak.jpg.jpg', 'https://maps.app.goo.gl/RgKKNYkL6yLhbwhd8?g_st=abt');

-- --------------------------------------------------------

--
-- بنية الجدول `rentals`
--

CREATE TABLE `rentals` (
  `id` int(11) NOT NULL,
  `eq_name` varchar(150) NOT NULL,
  `client_name` varchar(150) NOT NULL,
  `base_number` varchar(100) NOT NULL,
  `rental_location` varchar(255) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `daily_price` decimal(10,2) NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `contract_pdf` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- إرجاع أو استيراد بيانات الجدول `rentals`
--

INSERT INTO `rentals` (`id`, `eq_name`, `client_name`, `base_number`, `rental_location`, `start_date`, `end_date`, `daily_price`, `total_price`, `contract_pdf`) VALUES
(1, 'حفااار', 'شركه رواد الرافعاات', '14255227122HJ', 'الرياض - البطحاء', '2026-09-12', '2026-10-10', 1000.00, 28000.00, '1789231218_saleoffer-6.pdf');

-- --------------------------------------------------------

--
-- بنية الجدول `rental_payments`
--

CREATE TABLE `rental_payments` (
  `id` int(11) NOT NULL,
  `rental_id` int(11) NOT NULL,
  `amount_paid` decimal(10,2) NOT NULL,
  `payment_date` date NOT NULL,
  `notes` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- إرجاع أو استيراد بيانات الجدول `rental_payments`
--

INSERT INTO `rental_payments` (`id`, `rental_id`, `amount_paid`, `payment_date`, `notes`) VALUES
(1, 1, 1000.00, '2026-09-12', '');

-- --------------------------------------------------------

--
-- بنية الجدول `student_courses`
--

CREATE TABLE `student_courses` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `registration_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- بنية الجدول `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `permissions` text DEFAULT 'all'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- إرجاع أو استيراد بيانات الجدول `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `permissions`) VALUES
(1, 'admin', '$2y$10$1ruEfDjgbZhRDug0Q/AIl.MVWhM36FNjFyXNh6IBl8X5.c.jeW9Sy', 'all'),
(7, 'kahlan', '$2y$10$XD1nejyYEEuyNo/eFuNEIO1s4GOjPNn.y2cdNg069rI4mNoDIXN5e', 'all'),
(9, 'mo', '$2y$10$33uXon7TZzLP9IUTZ6VfveBJN8gXcZOr/9IwqsXM404L.dVo72JoK', 'index,register_ops');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `equipment`
--
ALTER TABLE `equipment`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `eq_name` (`eq_name`);

--
-- Indexes for table `rentals`
--
ALTER TABLE `rentals`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `rental_payments`
--
ALTER TABLE `rental_payments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `student_courses`
--
ALTER TABLE `student_courses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `equipment`
--
ALTER TABLE `equipment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `rentals`
--
ALTER TABLE `rentals`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `rental_payments`
--
ALTER TABLE `rental_payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `student_courses`
--
ALTER TABLE `student_courses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
