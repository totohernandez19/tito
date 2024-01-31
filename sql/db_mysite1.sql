-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 21, 2023 at 10:51 PM
-- Server version: 10.4.24-MariaDB
-- PHP Version: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_mysite`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(10) NOT NULL,
  `cname` varchar(100) NOT NULL,
  `fname` varchar(50) NOT NULL,
  `lname` varchar(50) NOT NULL,
  `addr1` varchar(100) NOT NULL,
  `addr2` varchar(100) NOT NULL,
  `phone` varchar(10) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `active` varchar(1) NOT NULL DEFAULT 'y'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `cname`, `fname`, `lname`, `addr1`, `addr2`, `phone`, `timestamp`, `active`) VALUES
(1, 'Yoel Hernandez', 'Yoel', 'Hernandez', '5150 Boggy Creek Rd, J33', 'St. Cloud Fl 34771', '4079533297', '2023-01-25 23:21:47', 'y');

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `id` bigint(10) NOT NULL,
  `name` varchar(100) NOT NULL,
  `addr1` varchar(100) NOT NULL,
  `addr2` varchar(100) NOT NULL,
  `phone` varchar(10) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `active` varchar(1) NOT NULL DEFAULT 'y'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`id`, `name`, `addr1`, `addr2`, `phone`, `timestamp`, `active`) VALUES
(1, 'Belmont Management Group', '1133 Luisiana Ave', 'Winter Park Fl 32789', '4077450696', '2023-01-25 23:01:06', 'y');

-- --------------------------------------------------------

--
-- Table structure for table `job`
--

CREATE TABLE `job` (
  `id` bigint(10) NOT NULL,
  `meldnum` varchar(20) NOT NULL,
  `addr1` varchar(100) NOT NULL,
  `addr2` varchar(100) NOT NULL,
  `bill` decimal(10,2) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `active` varchar(1) NOT NULL DEFAULT 'y'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `job`
--

INSERT INTO `job` (`id`, `meldnum`, `addr1`, `addr2`, `bill`, `timestamp`, `active`) VALUES
(1, 'T6W334U', '451 Holt Ave', 'Winter Park, FL 32789', '0.00', '2023-02-21 21:33:43', 'y'),
(2, 'T3I2AA0', '732 Secret Harbor Ln, Unit# 204', 'Lake Mary, FL 32746', '0.00', '2023-02-21 21:36:40', 'y');

-- --------------------------------------------------------

--
-- Table structure for table `jobdet`
--

CREATE TABLE `jobdet` (
  `id` bigint(10) NOT NULL,
  `meldnum` varchar(20) NOT NULL,
  `job` varchar(100) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `qty` int(5) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `active` varchar(1) NOT NULL DEFAULT 'y'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `jobdet`
--

INSERT INTO `jobdet` (`id`, `meldnum`, `job`, `price`, `qty`, `timestamp`, `active`) VALUES
(1, 'T3I2AA0', 'caulking repair', '70.00', 1, '2023-02-21 21:37:31', 'y'),
(2, 'T6W334U', 'Gutter repair', '120.00', 1, '2023-02-21 21:39:21', 'y');

-- --------------------------------------------------------

--
-- Table structure for table `markuplist`
--

CREATE TABLE `markuplist` (
  `id` bigint(10) NOT NULL,
  `price_start` decimal(10,2) NOT NULL,
  `price_end` decimal(10,2) NOT NULL,
  `perc` int(3) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `active` varchar(1) NOT NULL DEFAULT 'y'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `markuplist`
--

INSERT INTO `markuplist` (`id`, `price_start`, `price_end`, `perc`, `timestamp`, `active`) VALUES
(1, '0.00', '80.00', 40, '2023-02-21 16:46:06', 'y'),
(2, '80.01', '100.00', 30, '2023-02-21 16:46:13', 'y'),
(3, '100.01', '100000.00', 25, '2023-02-21 16:46:18', 'y');

-- --------------------------------------------------------

--
-- Table structure for table `pricelist`
--

CREATE TABLE `pricelist` (
  `id` bigint(10) NOT NULL,
  `job` varchar(100) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `active` varchar(1) NOT NULL DEFAULT 'y'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `pricelist`
--

INSERT INTO `pricelist` (`id`, `job`, `price`, `timestamp`, `active`) VALUES
(1, 'Bathroom sink faucet', '85.00', '2023-02-21 21:17:11', 'y'),
(2, 'Blind', '40.00', '2023-02-21 21:17:11', 'y'),
(3, 'blind 2 inch', '45.00', '2023-02-21 21:17:11', 'y'),
(4, 'blind vertical', '50.00', '2023-02-21 21:17:11', 'y'),
(5, 'Cabinet bottom wood', '60.00', '2023-02-21 21:17:11', 'y'),
(6, 'cabinet repair', '70.00', '2023-02-21 21:17:11', 'y'),
(7, 'caulking repair', '70.00', '2023-02-21 21:17:11', 'y'),
(8, 'Ceiling fan', '120.00', '2023-02-21 21:17:11', 'y'),
(9, 'Ceiling fan junction box', '160.00', '2023-02-21 21:17:11', 'y'),
(10, 'Celing lamp', '60.00', '2023-02-21 21:17:11', 'y'),
(11, 'Chandelier', '160.00', '2023-02-21 21:17:11', 'y'),
(13, 'Cover (switch,outlet,GFCI,etc)', '5.00', '2023-02-21 21:17:37', 'y'),
(14, 'Door lock', '50.00', '2023-02-21 21:17:11', 'y'),
(15, 'Door lock (smart)', '100.00', '2023-02-21 21:17:11', 'y'),
(16, 'dryer vent and duct install', '70.00', '2023-02-21 21:17:11', 'y'),
(17, 'electrcal work', '120.00', '2023-02-21 21:17:11', 'y'),
(18, 'garbage disposal', '110.00', '2023-02-21 21:17:11', 'y'),
(19, 'GFCI', '80.00', '2023-02-21 21:17:11', 'y'),
(20, 'hood range', '110.00', '2023-02-21 21:17:11', 'y'),
(21, 'hood range with microwave', '200.00', '2023-02-21 21:17:11', 'y'),
(22, 'kitchen angle stop', '75.00', '2023-02-21 21:17:11', 'y'),
(23, 'kitchen ceiling lamp', '80.00', '2023-02-21 21:17:11', 'y'),
(24, 'kitchen faucet', '100.00', '2023-02-21 21:17:11', 'y'),
(25, 'Kitchen light bulbs and ballast', '60.00', '2023-02-21 21:17:11', 'y'),
(26, 'kitchen light tubes', '35.00', '2023-02-21 21:17:11', 'y'),
(27, 'mailbox install', '160.00', '2023-02-21 21:17:12', 'y'),
(28, 'new ac window unit', '180.00', '2023-02-21 21:17:12', 'y'),
(29, 'new closet door', '180.00', '2023-02-21 21:17:12', 'y'),
(30, 'new toilet (remove old one +80)', '250.00', '2023-02-21 21:17:12', 'y'),
(31, 'outlet, switch', '60.00', '2023-02-21 21:17:42', 'y'),
(32, 'remote switch install', '60.00', '2023-02-21 21:17:12', 'y'),
(33, 'shelf install', '110.00', '2023-02-21 21:17:12', 'y'),
(34, 'shower head', '30.00', '2023-02-21 21:17:12', 'y'),
(35, 'sink and kitchen(traps,gasket,unclog,etc)', '70.00', '2023-02-21 21:17:48', 'y'),
(36, 'sink drain', '100.00', '2023-02-21 21:17:12', 'y'),
(37, 'sink, countertop install', '220.00', '2023-02-21 21:17:54', 'y'),
(38, 'smoke detector(battery)', '15.00', '2023-02-21 21:17:12', 'y'),
(39, 'smoke detector(new)', '80.00', '2023-02-21 21:17:12', 'y'),
(40, 'toilet cover', '50.00', '2023-02-21 21:17:12', 'y'),
(41, 'toilet new wax ring', '160.00', '2023-02-21 21:17:12', 'y'),
(42, 'toilet tank system', '100.00', '2023-02-21 21:17:12', 'y'),
(43, 'trash or junk removal', '120.00', '2023-02-21 21:17:12', 'y'),
(44, 'washing machine connection', '90.00', '2023-02-21 21:17:12', 'y'),
(45, 'water filtration install', '140.00', '2023-02-21 21:17:12', 'y'),
(46, 'New garbage disposal', '140.00', '2023-02-21 21:18:57', 'y'),
(47, 'Gutter repair', '120.00', '2023-02-21 21:39:11', 'y');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` bigint(10) NOT NULL,
  `userid` bigint(10) NOT NULL,
  `username` varchar(30) NOT NULL,
  `password` varchar(30) NOT NULL,
  `fname` varchar(30) NOT NULL,
  `lname` varchar(30) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `active` varchar(1) NOT NULL DEFAULT 'y'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `userid`, `username`, `password`, `fname`, `lname`, `timestamp`, `active`) VALUES
(1, 24432, 'admin', 'admin', 'toto', 'hernandez', '2023-01-26 18:28:40', 'y'),
(2, 668165328014, 'tito', '1234', 'tito', 'hern', '2023-01-26 19:51:23', 'y'),
(5, 715482159567, 'mariaesc', '4321', 'maria', 'escoton', '2023-01-26 19:50:28', 'y');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `job`
--
ALTER TABLE `job`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jobdet`
--
ALTER TABLE `jobdet`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `markuplist`
--
ALTER TABLE `markuplist`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pricelist`
--
ALTER TABLE `pricelist`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `id` bigint(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `job`
--
ALTER TABLE `job`
  MODIFY `id` bigint(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `jobdet`
--
ALTER TABLE `jobdet`
  MODIFY `id` bigint(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `markuplist`
--
ALTER TABLE `markuplist`
  MODIFY `id` bigint(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `pricelist`
--
ALTER TABLE `pricelist`
  MODIFY `id` bigint(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` bigint(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
