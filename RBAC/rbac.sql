-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 14, 2024 at 01:15 PM
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
-- Database: `rbac`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(255) NOT NULL,
  `name` varchar(25) NOT NULL,
  `email` varchar(30) NOT NULL,
  `password` text NOT NULL,
  `status` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `name`, `email`, `password`, `status`) VALUES
(1, 'Bijay', 'bijay@bijay.com', '$2y$10$4HKPEM3z7SqBPFLfyHuq9evcJTExA9uYFOcJ3Ohmlo9CFnPjK9z/u', 0),
(2, 'Bijay Shankar', 'bjcrazytechz@gmail.com', '$2y$10$N0NKWX02uZbNECYS.yY8y.sJrqTWjmlTmwuknUKC5OCbW6PhDVrN2', 0);

-- --------------------------------------------------------

--
-- Table structure for table `otp-lib`
--

CREATE TABLE `otp-lib` (
  `email` varchar(255) NOT NULL,
  `pass` text NOT NULL,
  `timestamp` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(10) NOT NULL,
  `name` varchar(25) NOT NULL,
  `userName` varchar(30) NOT NULL,
  `fatherName` varchar(25) NOT NULL,
  `address` varchar(50) NOT NULL,
  `role` varchar(10) NOT NULL DEFAULT 'user',
  `email` varchar(40) NOT NULL,
  `password` text NOT NULL,
  `status` varchar(8) NOT NULL DEFAULT 'Inactive',
  `profilePic` text NOT NULL DEFAULT '../upload/default.jpg'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `userName`, `fatherName`, `address`, `role`, `email`, `password`, `status`, `profilePic`) VALUES
(1, 'Bijay Shankar', 'Bijay_123456', 'Suresh J', 'vk', 'viewer', 'abc@xyzz.com', '$2y$10$czTAaifTvL4Kn0ls18cAUOq8s6QEkTIaAAJJs6r6HJKZLRhG0wTlC', 'Inactive', '../upload/example.png'),
(13, 'ak', 'ak@123', 'sj', 'vk', 'admin', 'ak@ak.com', '$2y$10$czTAaifTvL4Kn0ls18cAUOq8s6QEkTIaAAJJs6r6HJKZLRhG0wTlC', 'Inactive', '../upload/default.jpg'),
(16, 'ABCD', 'ABCD', 'ABCD', 'ABCD', 'user', 'ab@ab.com', '$2y$10$czTAaifTvL4Kn0ls18cAUOq8s6QEkTIaAAJJs6r6HJKZLRhG0wTlC', 'Inactive', '../upload/default.jpg'),
(17, 'Sabya', 'sabya', 'abcd', 'Balangir', 'admin', 'sabya@sabya.com', '$2y$10$57JU1ZYn8VlSbMnlu26rCuDFUdqX/0FefwVzFvVjyOEmo3DA4Fj6C', 'Inactive', '../upload/default.jpg'),
(24, 'Rohit', 'rohit', 'rohit', 'rohit', 'admin', 'rohit@rohit.com', '$2y$10$oG1MukHWEeEMH63s9ThfIuBu5RFT0uSbDktC5xWTYILbeYNSG/uqS', 'Inactive', '../upload/67599546505c8-1733924166.jpg'),
(25, 'bdsfh', 'dfkjgn', 'kjdfng', 'dfkjg', 'user', 'b@b.com', '$2y$10$zE4MFr93MA6AFE2eHG/WRO3EGMfGZHyQZx7XzgB8e3UURiryugGoO', 'Inactive', '../upload/default.jpg');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `otp-lib`
--
ALTER TABLE `otp-lib`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
