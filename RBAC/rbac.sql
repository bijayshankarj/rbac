-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 09, 2024 at 04:01 PM
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
(1, 'Bijay', 'bijay@bijay.com', '$2y$10$3Qy1NLBKPjYfHrAwcRyzAuLN0DEhkvBSUeeuFrl4pVWz0fbbmgxWy', 0);

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
  `status` varchar(8) NOT NULL DEFAULT 'Inactive'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `userName`, `fatherName`, `address`, `role`, `email`, `password`, `status`) VALUES
(1, 'Bijay Shankar', 'Bijay_123456', 'Suresh J', 'vk', 'viewer', 'abc@xyzz.com', '$2y$10$czTAaifTvL4Kn0ls18cAUOq8s6QEkTIaAAJJs6r6HJKZLRhG0wTlC', 'Inactive'),
(13, 'ak', 'ak@123', 'sj', 'vk', 'admin', 'ak@ak.com', '$2y$10$czTAaifTvL4Kn0ls18cAUOq8s6QEkTIaAAJJs6r6HJKZLRhG0wTlC', 'Inactive'),
(14, 'Bijayy', 'hmm', 'hm', 'hm', 'user', 'hmm@hmm.com', '$2y$10$czTAaifTvL4Kn0ls18cAUOq8s6QEkTIaAAJJs6r6HJKZLRhG0wTlC', 'Inactive'),
(16, 'ABCD', 'ABCD', 'ABCD', 'ABCD', 'user', 'ab@ab.com', '$2y$10$czTAaifTvL4Kn0ls18cAUOq8s6QEkTIaAAJJs6r6HJKZLRhG0wTlC', 'Inactive');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

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
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
