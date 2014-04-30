-- phpMyAdmin SQL Dump
-- version 3.3.9
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Mar 06, 2013 at 04:48 PM
-- Server version: 5.5.8
-- PHP Version: 5.3.5

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: '401'
--

-- --------------------------------------------------------

--
-- Table structure for table 'achievements'
--


INSERT INTO Resident (Resident_ID, `Name`, Username, Room_Number, Location, Points, Room_Status) VALUES
(1, 'TEST', 'TEST', 4, 'TEST', 2000, 'TEST');
--
-- Dumping data for table 'achievements'
--

INSERT INTO Achievements (Achievement_ID, `Name`, Description, Enabled_Icon, Disabled_Icon, Points) VALUES
(1, 'First Achiev', ' The Best Achiev', '2', '3', 400),
(2, 'Second Aciev', 'The Worst Achiev', '23', '45', 4000),
(3, 'Third Achiev', 'Gold Achiev', '300', '453', 10000000);

-- --------------------------------------------------------

--
-- Table structure for table 'building'
--


--
-- Dumping data for table 'building'
--

INSERT INTO Building (Building_ID, Resident_ID, Building, Floor, Orientation, Layout) VALUES
(12, 1, 'Windsor', 'Plan 4 ', 'NW', '2 Bedrooms and 5 Bathrooms');

-- --------------------------------------------------------

--
-- Table structure for table 'earned_achievements'
--


--
-- Dumping data for table 'earned_achievements'
--

INSERT INTO Earned_Achievements (Resident_ID, Achievement_ID, Date_Earned) VALUES
(1, 1, '2013-03-08'),
(1, 2, '2013-03-09');

-- --------------------------------------------------------

--
-- Table structure for table 'resident'
--

--
-- Dumping data for table 'resident'
--



