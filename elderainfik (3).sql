-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 26, 2024 at 12:29 PM
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
-- Database: `elderainfik`
--

-- --------------------------------------------------------

--
-- Table structure for table `medicine`
--

CREATE TABLE `medicine` (
  `id` int(11) NOT NULL,
  `eldername` text NOT NULL,
  `email` varchar(150) NOT NULL,
  `medicine` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `consumptiondate` varchar(20) NOT NULL,
  `consumptiontime` varchar(20) NOT NULL,
  `caretakeremail` int(11) NOT NULL,
  `remark` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `motorspin`
--

CREATE TABLE `motorspin` (
  `id` int(11) NOT NULL,
  `eldername` text NOT NULL,
  `elderemail` varchar(150) NOT NULL,
  `medicinename` text NOT NULL,
  `medicinetype` text NOT NULL,
  `datetime` datetime NOT NULL,
  `spinstate` enum('true','false') NOT NULL DEFAULT 'false',
  `remarks` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `motorspin`
--

INSERT INTO `motorspin` (`id`, `eldername`, `elderemail`, `medicinename`, `medicinetype`, `datetime`, `spinstate`, `remarks`) VALUES
(73, 'fikries', 'fikries184@gmail.com', 'panadol', 'actifast', '2024-10-25 15:24:00', 'true', ''),
(74, 'fikries', 'fikries184@gmail.com', 'panadol', 'actifast', '2024-10-25 15:56:00', 'true', ''),
(75, 'fikries', 'fikries184@gmail.com', 'panadol', 'actifast', '2024-10-26 00:39:00', 'true', ''),
(76, 'mai', 'mai@gmail.com', 'heart attack', 'Aspirin', '2024-11-02 12:20:00', 'false', ''),
(77, 'Nur Ain Binti Razak', 'nurulainr00@gmail.com', 'panadol', 'actifast', '2024-10-31 23:29:00', 'true', ''),
(78, 'Muhammad Fikri Bin Zaharudin', 'fikries184@gmail.com', 'panadol', 'actifast', '2024-11-12 03:10:00', 'true', ''),
(79, 'Muhammad Fikri Bin Zaharudin', 'fikries184@gmail.com', 'panadol', 'actifast', '2024-11-12 09:30:00', 'false', ''),
(80, 'Muhammad Fikri Bin Zaharudin', 'fikries184@gmail.com', 'panadol', 'actifast', '2024-11-12 09:40:00', 'true', ''),
(81, 'Muhammad Fikri Bin Zaharudin', 'fikries184@gmail.com', 'panadol', 'actifast', '2024-11-13 09:54:00', 'true', ''),
(82, 'Muhammad Fikri Bin Zaharudin', 'fikries184@gmail.com', 'panadol', 'actifast', '2024-11-13 10:20:00', 'true', ''),
(83, 'Muhammad Fikri Bin Zaharudin', 'fikries184@gmail.com', 'panadol', 'actifast', '2024-11-13 10:46:00', 'true', ''),
(84, 'Muhammad Fikri Bin Zaharudin', 'fikries184@gmail.com', 'panadol', 'actifast', '2024-11-13 11:06:00', 'true', ''),
(85, 'Muhammad Fikri Bin Zaharudin', 'fikries184@gmail.com', 'panadol', 'actifast', '2024-11-13 11:17:00', 'true', ''),
(86, 'Muhammad Fikri Bin Zaharudin', 'fikries184@gmail.com', 'panadol', 'actifast', '2024-11-13 11:26:00', 'true', ''),
(87, 'Muhammad Fikri Bin Zaharudin', 'fikries184@gmail.com', 'panadol', 'actifast', '2024-11-13 11:57:00', 'true', ''),
(88, 'Muhammad Fikri Bin Zaharudin', 'fikries184@gmail.com', 'panadol', 'actifast', '2024-11-13 12:06:00', 'true', ''),
(89, 'Muhammad Fikri Bin Zaharudin', 'fikries184@gmail.com', 'panadol', 'actifast', '2024-11-13 12:25:00', 'true', ''),
(90, 'Muhammad Fikri Bin Zaharudin', 'fikries184@gmail.com', 'panadol', 'actifast', '2024-11-13 12:34:00', 'true', ''),
(91, 'Muhammad Fikri Bin Zaharudin', 'fikries184@gmail.com', 'panadol', 'actifast', '2024-11-26 11:33:00', 'true', '');

-- --------------------------------------------------------

--
-- Table structure for table `patient`
--

CREATE TABLE `patient` (
  `id` int(11) NOT NULL,
  `name` varchar(200) NOT NULL,
  `email` varchar(200) NOT NULL,
  `address` varchar(200) NOT NULL,
  `datein` date NOT NULL,
  `dateout` date DEFAULT NULL,
  `timein` time NOT NULL,
  `illness` varchar(200) NOT NULL,
  `usename` varchar(250) NOT NULL,
  `password` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `patient`
--

INSERT INTO `patient` (`id`, `name`, `email`, `address`, `datein`, `dateout`, `timein`, `illness`, `usename`, `password`) VALUES
(1, 'Nurulain Binti Rosdi', 'ain@gmail.com', '2 Taman Mutiara, kAJANG, Selangor', '2024-07-10', '2024-06-13', '00:00:00', 'kencing manis', '', ''),
(2, 'abu bin azman', 'abu@gmail.com', '10 Taman Mesra Kajang, Selangor', '2024-06-21', '1970-12-10', '00:00:00', '', '', ''),
(3, 'maisarah umairah binti shahrul', 'mai@gmail.com', 'Seksyen 3 ,Bangi, Selangor', '2024-06-14', NULL, '14:00:00', '', '', ''),
(4, 'Muhammad Fikri Bin Zaharudin', 'fikries184@gmail.com', '28-0012 FLAT SERI MELAKA, CHERAS, KL', '2024-06-15', NULL, '00:00:00', '', '', ''),
(5, 'Nur Ain Binti Razak', 'nurulainr00@gmail.com', '11 Bukit Pandan, PANDAN JAYA , KL', '2024-06-27', NULL, '00:00:00', 'flu', '', ''),
(6, 'Nur Ain Binti Razak', 'nurulainr00@gmail.com', '11 Bukit Pandan, PANDAN JAYA , KL', '2024-06-22', NULL, '00:00:00', 'flu', '', ''),
(7, 'Nur Aliya Binti Zainudin', 'amirulasrix@gmail.com', '11 Bukit Pandan, PANDAN JAYA , KL', '2024-06-08', NULL, '00:00:00', 'KENCING MANIS', '', ''),
(8, 'Nur Ain Binti Razak', 'nurulainr00@gmail.com', '11 Bukit Pandan, PANDAN JAYA , KL', '2024-06-19', NULL, '00:00:00', 'flu', '', ''),
(9, 'Nur Ain Binti Razak', 'nurulainr00@gmail.com', '11 Bukit Pandan, PANDAN JAYA , KL', '2024-06-26', NULL, '00:00:00', 'flu', '', ''),
(10, 'Nur Ain Binti Razak', 'nurulainr00@gmail.com', '11 Bukit Pandan, PANDAN JAYA , KL', '2024-06-12', NULL, '00:00:00', 'flu', '', ''),
(11, 'Nur Ain Binti Razak', 'nurulainr00@gmail.com', '24 Intan Payung', '2024-06-03', NULL, '00:00:00', 'flu', '', ''),
(12, 'Nur Ain Binti Razakmalia', 'nurulainr00@gmail.com', '24 Intan Payung', '2024-06-04', NULL, '00:00:00', 'flu', '', ''),
(13, 'Nur Ain Binti Razak', 'nurulainr00@gmail.com', '8 Intan Payung', '2024-06-19', NULL, '00:00:00', 'flu', '', ''),
(14, 'Nur Ain Binti Razak', 'nurulainr00@gmail.com', '10 Intan Payung', '2024-07-03', NULL, '00:00:00', 'flu', '', ''),
(15, 'Nur Ain Binti Razak', 'nurulainr00@gmail.com', '44 Bangi', '2024-07-05', NULL, '17:50:00', 'flu', '', ''),
(16, 'MUHAMMAD AIZAT BIN SHAMRI', 'muhd2140@gmail.com', 'lorong jujur 2 btr', '2024-06-12', NULL, '00:11:00', 'SAKIT JANTUNG', '', ''),
(17, 'MUHAMMAD AIZAT BIN SHAMRI', 'Aizatmuhd2140@gmail.com', 'lorong jujur 2 btr', '2024-06-12', NULL, '00:13:00', 'SAKIT JANTUNG', '', ''),
(18, 'NUR ALEEYA BIN NIZAM', 'kl2311015218@student.uptm.edu.my', '44 Indah', '2024-06-19', NULL, '09:46:00', '', '', ''),
(19, 'Nur Aliya Binti Zainudin', 'amirulasrix@gmail.com', 'KL', '2024-08-13', NULL, '00:32:00', 'KENCING MANIS', '', ''),
(20, 'Nur Aliya Binti Zainudin', 'amirulasrix@gmail.com', 'KL', '2024-08-13', NULL, '00:32:00', 'KENCING MANIS', '', ''),
(21, 'Nur Aliya Binti Zainudin', 'amirulasrix@gmail.com', 'KL 3', '2024-08-14', NULL, '15:04:00', 'KENCING MANIS', '', ''),
(22, 'MUHAMMAD ALIF BIN ZAINUDIN', 'maliff040604@gmail.com', 'IPOH', '2024-08-01', NULL, '14:34:00', 'SAKIT JANTUNG', '', ''),
(23, 'AHMAD NORMAN BIN SAIFUDDIN', 'fikf46490@gmail.com', 'kuala lumpur', '2024-08-02', NULL, '14:54:00', '', '', ''),
(24, 'Nur Aliya Binti Zainudin', 'amirulasrix@gmail.com', 'KL', '2024-10-14', NULL, '19:05:00', 'Test', '', ''),
(25, 'MUHAMMAD AIMAN FARHAN', 'aimanlixdy@gmail.com', 'taman sutera,kajang', '2024-10-16', NULL, '16:15:00', 'DIABETES', '', ''),
(26, 'sensei azlin', 'azlin.ramli@mara.gov.my', 'mjii', '2024-10-16', NULL, '17:07:00', 'demam', '', ''),
(27, 'Nur Ain Binti Razak', 'nurulainr00@gmail.com', 'Bangi', '2024-10-25', NULL, '19:00:00', 'flu', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `pushtime`
--

CREATE TABLE `pushtime` (
  `id` int(11) NOT NULL,
  `date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pushtime`
--

INSERT INTO `pushtime` (`id`, `date`) VALUES
(67, '2024-09-18 18:41:05'),
(68, '2024-09-18 19:03:10'),
(69, '2024-09-18 19:04:20'),
(70, '2024-09-18 19:08:38'),
(71, '2024-09-18 19:14:27'),
(72, '2024-10-14 01:35:01'),
(73, '2024-10-14 01:35:26'),
(74, '2024-10-14 01:36:32'),
(75, '2024-10-14 01:36:45'),
(76, '2024-10-14 01:37:43'),
(77, '2024-10-14 01:38:16'),
(78, '2024-10-14 01:38:48'),
(79, '2024-10-14 01:48:47'),
(80, '2024-10-14 01:49:14'),
(81, '2024-10-14 01:49:37'),
(82, '2024-10-14 01:49:52'),
(83, '2024-10-14 01:50:14'),
(84, '2024-10-14 02:04:41'),
(85, '2024-10-14 02:06:04'),
(86, '2024-10-14 02:10:34'),
(87, '2024-10-14 02:46:49'),
(88, '2024-10-14 02:46:51'),
(89, '2024-10-14 02:55:13'),
(90, '2024-10-15 02:10:37'),
(91, '2024-10-15 02:13:19'),
(92, '2024-10-15 03:00:14'),
(93, '2024-10-15 03:00:49'),
(94, '2024-10-16 12:18:28'),
(95, '2024-10-16 12:26:24'),
(96, '2024-10-16 12:29:29'),
(97, '2024-10-16 12:30:00'),
(98, '2024-10-16 12:36:12'),
(99, '2024-10-16 12:38:17'),
(100, '2024-10-16 15:29:46'),
(101, '2024-10-16 15:41:12'),
(102, '2024-10-16 15:41:45'),
(103, '2024-10-16 15:47:38'),
(104, '2024-10-16 16:06:08'),
(105, '2024-10-16 16:08:10'),
(106, '2024-10-16 16:21:13'),
(107, '2024-10-16 16:23:32'),
(108, '2024-10-16 16:35:23'),
(109, '2024-10-16 16:38:31'),
(110, '2024-10-16 16:42:17'),
(111, '2024-10-16 16:44:08'),
(112, '2024-10-16 16:47:10'),
(113, '2024-10-16 16:48:00'),
(114, '2024-10-16 16:52:20'),
(115, '2024-10-16 17:04:19'),
(116, '2024-10-16 17:12:15');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `medicine`
--
ALTER TABLE `medicine`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `motorspin`
--
ALTER TABLE `motorspin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `patient`
--
ALTER TABLE `patient`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pushtime`
--
ALTER TABLE `pushtime`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `medicine`
--
ALTER TABLE `medicine`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT for table `motorspin`
--
ALTER TABLE `motorspin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=92;

--
-- AUTO_INCREMENT for table `patient`
--
ALTER TABLE `patient`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `pushtime`
--
ALTER TABLE `pushtime`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=117;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
