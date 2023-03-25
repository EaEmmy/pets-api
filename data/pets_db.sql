-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 26, 2023 at 12:34 AM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pets_db`
--
CREATE DATABASE IF NOT EXISTS `pets_db` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `pets_db`;

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

DROP TABLE IF EXISTS `category`;
CREATE TABLE `category` (
  `category_id` int(11) NOT NULL,
  `name` varchar(12) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`category_id`, `name`) VALUES
(1, 'Cat'),
(2, 'Dog'),
(3, 'Goat'),
(4, 'Bird'),
(5, 'Rabbit'),
(6, 'Hamster');

-- --------------------------------------------------------

--
-- Table structure for table `entry`
--

DROP TABLE IF EXISTS `entry`;
CREATE TABLE `entry` (
  `entry_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `date_type` enum('receivedOn','dateLost','dateFound') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `entry`
--

INSERT INTO `entry` (`entry_id`, `date`, `date_type`) VALUES
(1, '2017-07-12', 'receivedOn'),
(2, '2017-03-24', 'receivedOn'),
(3, '2017-05-18', 'receivedOn'),
(4, '2017-06-08', 'dateLost'),
(5, '2017-09-20', 'dateFound'),
(6, '2017-04-28', 'dateFound'),
(7, '2019-03-22', 'dateLost'),
(8, '2018-08-10', 'dateLost'),
(9, '2018-09-13', 'dateFound'),
(10, '2019-02-13', 'dateFound'),
(11, '2018-01-17', 'receivedOn'),
(12, '2018-11-30', 'dateLost');

-- --------------------------------------------------------

--
-- Table structure for table `pets_appearance`
--

DROP TABLE IF EXISTS `pets_appearance`;
CREATE TABLE `pets_appearance` (
  `appearance_id` int(11) NOT NULL,
  `breed` varchar(50) DEFAULT NULL,
  `color` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `pets_appearance`
--

INSERT INTO `pets_appearance` (`appearance_id`, `breed`, `color`) VALUES
(1, 'Domestic Shorthair', 'Black'),
(2, 'Domestic Shorthair', 'White'),
(3, 'Domestic Shorthair', 'Brown'),
(4, 'Domestic Shorthair', 'Gray'),
(5, 'Domestic Longhair', 'Black'),
(6, 'Domestic Longhair', 'White'),
(7, 'Domestic Longhair', 'Brown'),
(9, 'Domestic Mediumhair', 'Tabby'),
(10, 'Domestic Shorthair', 'Orange'),
(11, 'Domestic Shorthair', 'Tabby'),
(12, 'Domestic Shorthair', 'Calico'),
(13, 'Domestic Longhair', 'Gray'),
(14, 'Domestic Mediumhair', 'Black');

-- --------------------------------------------------------

--
-- Table structure for table `pets_info`
--

DROP TABLE IF EXISTS `pets_info`;
CREATE TABLE `pets_info` (
  `animal_id` int(11) NOT NULL,
  `name` varchar(20) DEFAULT NULL,
  `age` int(5) DEFAULT NULL,
  `gender` enum('male','female','neutered_male','spayed_female','unaltered') DEFAULT NULL,
  `status` enum('lost','found','adoptable') DEFAULT NULL,
  `current_location` varchar(80) DEFAULT NULL,
  `category_id` int(11) NOT NULL,
  `record_id` int(11) NOT NULL,
  `appearance_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `pets_info`
--

INSERT INTO `pets_info` (`animal_id`, `name`, `age`, `gender`, `status`, `current_location`, `category_id`, `record_id`, `appearance_id`) VALUES
(1, 'Neko', 3, 'male', 'lost', NULL, 1, 1, 1),
(2, 'Trixie', 1, 'female', 'lost', NULL, 2, 2, 2),
(3, 'Gizmo', 2, 'female', 'lost', NULL, 1, 3, 3),
(4, 'Caramel', 4, 'neutered_male', 'found', 'Reber Ranch 28606 132ND AVE SE KENT, WA 98042', 1, 4, 9),
(5, 'Fabio', 1, 'neutered_male', 'adoptable', 'King County Pet Adoption Center 21615 64TH AVE S KENT, WA 98032', 1, 5, 6),
(6, 'Velvet', 1, 'spayed_female', 'adoptable', 'King County Pet Adoption Center 21615 64TH AVE S KENT, WA 98032', 5, 6, 1),
(7, 'Mellow', 5, 'neutered_male', 'adoptable', 'King County Pet Adoption Center 21615 64TH AVE S KENT, WA 98032', 2, 7, 2),
(8, 'Tilly', 2, 'spayed_female', 'found', 'In RASKC Foster Home', 1, 8, 5),
(9, 'Roxy', 1, 'unaltered', 'adoptable', 'King County Pet Adoption Center 21615 64TH AVE S KENT, WA 98032', 6, 9, 1),
(10, 'Earl Lucy', NULL, 'unaltered', 'lost', NULL, 3, 10, 2),
(11, 'Oreo', 5, 'unaltered', 'lost', NULL, 3, 10, 1),
(12, 'Scotty', 2, 'unaltered', 'lost', NULL, 4, 11, 1),
(13, 'Chacha', 3, 'spayed_female', 'adoptable', 'King County Pet Adoption Center 21615 64TH AVE S KENT, WA 98032', 2, 11, 12),
(14, 'Nesquick', 1, 'spayed_female', 'adoptable', 'King County Pet Adoption Center 21615 64TH AVE S KENT, WA 98032', 5, 12, 5),
(15, 'Tye', 4, 'female', 'lost', NULL, 2, 12, 9),
(16, 'Susie', 13, 'spayed_female', 'adoptable', 'King County Pet Adoption Center 21615 64TH AVE S KENT, WA 98032', 1, 2, 6),
(17, 'Willow', 9, 'male', 'found', 'King County Pet Adoption Center 21615 64TH AVE S KENT, WA 98032', 1, 6, 5);

-- --------------------------------------------------------

--
-- Table structure for table `record`
--

DROP TABLE IF EXISTS `record`;
CREATE TABLE `record` (
  `record_id` int(11) NOT NULL,
  `address` varchar(100) DEFAULT NULL,
  `city` varchar(40) DEFAULT NULL,
  `state` varchar(10) DEFAULT NULL,
  `postal_code` varchar(10) DEFAULT NULL,
  `jurisdiction` varchar(20) DEFAULT NULL,
  `entry_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `record`
--

INSERT INTO `record` (`record_id`, `address`, `city`, `state`, `postal_code`, `jurisdiction`, `entry_id`) VALUES
(1, 'Riverview Blvd And 216th Street\r\n', 'SEATTLE', 'WA', '98108', 'KING COUNTY', 1),
(2, '18800 block of SE 269TH ST\r\n', 'COVINGTON', 'WA', '98042', 'COVINGTON', 2),
(3, '2249 Cedar Crest Rd\r\n', 'CARNATION', 'GA', '98014', 'CARNATION', 3),
(4, '947 Elma G Miles Pky\r\n', 'KENT', 'AL', '98032', 'KENT', 4),
(5, '46303 Southward Ter\r\n', 'NEWCAS', 'AR', '98030', 'DES MOINES', 5),
(6, '263 Hurd Rd', 'SEATAC', 'KY', '98198', 'SEATAC', 6),
(7, '2242 Ramsey Ford Rd', 'LA', 'NC', '28455', 'NAKINA', 7),
(8, '5035 Archdale Rd\r\n', 'HOWELL', 'NC', '27040', 'OHIO', 8),
(9, '6938 Elmburg Rd\r\n', 'MOUNTAINSIDE', 'KY', '40003', 'VERMONT', 9),
(10, '102 Ravine Dr\r\n', 'WEST MONROE', 'LA', '71291', 'CALIFORNIA', 10),
(11, '1700 Robbins Rd', 'GRAND HAVEN', 'MI', '49417', 'MARYLAND', 11),
(12, '34111 82nd Avenue Ct E\r\n', 'EATONVILLE', 'WA', '98328', 'ENUMCLAW', 12);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`category_id`);

--
-- Indexes for table `entry`
--
ALTER TABLE `entry`
  ADD PRIMARY KEY (`entry_id`);

--
-- Indexes for table `pets_appearance`
--
ALTER TABLE `pets_appearance`
  ADD PRIMARY KEY (`appearance_id`);

--
-- Indexes for table `pets_info`
--
ALTER TABLE `pets_info`
  ADD PRIMARY KEY (`animal_id`),
  ADD KEY `category_id` (`category_id`),
  ADD KEY `record_id` (`record_id`),
  ADD KEY `apperance_id` (`appearance_id`);

--
-- Indexes for table `record`
--
ALTER TABLE `record`
  ADD PRIMARY KEY (`record_id`),
  ADD KEY `entry_id` (`entry_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `entry`
--
ALTER TABLE `entry`
  MODIFY `entry_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `pets_appearance`
--
ALTER TABLE `pets_appearance`
  MODIFY `appearance_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `pets_info`
--
ALTER TABLE `pets_info`
  MODIFY `animal_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `record`
--
ALTER TABLE `record`
  MODIFY `record_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `pets_info`
--
ALTER TABLE `pets_info`
  ADD CONSTRAINT `apperance_id` FOREIGN KEY (`appearance_id`) REFERENCES `pets_appearance` (`appearance_id`),
  ADD CONSTRAINT `category_id` FOREIGN KEY (`category_id`) REFERENCES `category` (`category_id`),
  ADD CONSTRAINT `record_id` FOREIGN KEY (`record_id`) REFERENCES `record` (`record_id`);

--
-- Constraints for table `record`
--
ALTER TABLE `record`
  ADD CONSTRAINT `entry_id` FOREIGN KEY (`entry_id`) REFERENCES `entry` (`entry_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
