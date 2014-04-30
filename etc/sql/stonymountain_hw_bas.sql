-- phpMyAdmin SQL Dump
-- version 3.4.10.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jun 11, 2013 at 08:06 AM
-- Server version: 5.1.58
-- PHP Version: 5.2.17

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `stonymountain_hw_bas`
--

-- --------------------------------------------------------

--
-- Table structure for table `bas_el_energy_extra`
--

CREATE TABLE IF NOT EXISTS `bas_el_energy_extra` (
  `ts` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `P7_1` double DEFAULT NULL,
  `P8` double DEFAULT NULL,
  `P2_1` double DEFAULT NULL,
  `P2_2` double DEFAULT NULL,
  `P2_3` double DEFAULT NULL,
  `P2_4` double DEFAULT NULL,
  `P4_1` double DEFAULT NULL,
  `P4_2` double DEFAULT NULL,
  `BLR_1` double DEFAULT NULL,
  `BLR_2` double DEFAULT NULL,
  `P3_1` double DEFAULT NULL,
  `P3_2` double DEFAULT NULL,
  `SHTS` double DEFAULT NULL,
  PRIMARY KEY (`ts`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `boiler_loop_minute`
--

CREATE TABLE IF NOT EXISTS `boiler_loop_minute` (
  `ts` varchar(21) NOT NULL DEFAULT '',
  `building` int(11) NOT NULL,
  `T15` double DEFAULT NULL,
  `T14` double DEFAULT NULL,
  `BLR1` decimal(7,4) DEFAULT NULL,
  `BLR2` decimal(7,4) DEFAULT NULL,
  `TANK10` double DEFAULT NULL,
  `T10` double DEFAULT NULL,
  `AIRT04` double DEFAULT NULL,
  PRIMARY KEY (`ts`,`building`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `EnergyD_Graph`
--

CREATE TABLE IF NOT EXISTS `EnergyD_Graph` (
  `ts` varchar(10) NOT NULL DEFAULT '',
  `building` int(11) NOT NULL,
  `Date` date DEFAULT NULL,
  `Year` int(4) DEFAULT NULL,
  `Month` int(2) DEFAULT NULL,
  `Week` int(2) DEFAULT NULL,
  `Day` int(2) DEFAULT NULL,
  `Day of Week` int(3) DEFAULT NULL,
  `Day Name` varchar(9) DEFAULT NULL,
  `Energy1` double(20,3) DEFAULT NULL,
  `Energy2` double(20,3) DEFAULT NULL,
  `Energy3` double(20,3) DEFAULT NULL,
  `Energy4` double(20,3) DEFAULT NULL,
  `Energy5` double(20,3) DEFAULT NULL,
  `Energy6` double(20,3) DEFAULT NULL,
  `Energy7` double(20,3) DEFAULT NULL,
  PRIMARY KEY (`ts`,`building`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `EnergyH_Graph`
--

CREATE TABLE IF NOT EXISTS `EnergyH_Graph` (
  `ts` varchar(18) NOT NULL DEFAULT '',
  `building` int(11) NOT NULL,
  `Date` date DEFAULT NULL,
  `Year` int(4) DEFAULT NULL,
  `Month` int(2) DEFAULT NULL,
  `Week` int(2) DEFAULT NULL,
  `Day` int(2) DEFAULT NULL,
  `Day of Week` int(3) DEFAULT NULL,
  `Day Name` varchar(9) DEFAULT NULL,
  `Energy1` double(20,3) DEFAULT NULL,
  `Energy2` double(20,3) DEFAULT NULL,
  `Energy3` double(20,3) DEFAULT NULL,
  `Energy4` double(20,3) DEFAULT NULL,
  `Energy5` double(20,3) DEFAULT NULL,
  `Energy6` double(20,3) DEFAULT NULL,
  `Energy7` double(20,3) DEFAULT NULL,
  PRIMARY KEY (`ts`,`building`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `Energy_Minute`
--

CREATE TABLE IF NOT EXISTS `Energy_Minute` (
  `ts` varchar(21) NOT NULL DEFAULT '',
  `building` int(11) NOT NULL,
  `Date` date DEFAULT NULL,
  `Year` int(4) DEFAULT NULL,
  `Month` int(2) DEFAULT NULL,
  `Day` int(2) DEFAULT NULL,
  `Hour` int(2) DEFAULT NULL,
  `Minute` int(2) DEFAULT NULL,
  `Week` int(2) DEFAULT NULL,
  `Day of Week` int(3) DEFAULT NULL,
  `Day Name` varchar(9) DEFAULT NULL,
  `Energy1` double DEFAULT NULL,
  `Energy2` double DEFAULT NULL,
  `Energy3` double DEFAULT NULL,
  `Energy4` double DEFAULT NULL,
  `Energy5` double DEFAULT NULL,
  `Energy6` double DEFAULT NULL,
  `Energy7` double DEFAULT NULL,
  PRIMARY KEY (`ts`,`building`),
  UNIQUE KEY `building` (`building`,`Date`,`Year`,`Month`,`Day`,`Hour`,`Minute`,`Week`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `flow_minute`
--

CREATE TABLE IF NOT EXISTS `flow_minute` (
  `ts` varchar(21) NOT NULL DEFAULT '',
  `Flow1` double DEFAULT NULL,
  `Flow2` double DEFAULT NULL,
  `Flow3` double DEFAULT NULL,
  `Flow4_1` double DEFAULT NULL,
  `Flow4_2` double DEFAULT NULL,
  `Flow5_1` double DEFAULT NULL,
  `Flow5_2` double DEFAULT NULL,
  `Flow5_3` double DEFAULT NULL,
  `Flow5_4` double DEFAULT NULL,
  `Flow6` double DEFAULT NULL,
  PRIMARY KEY (`ts`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `solar_minute`
--

CREATE TABLE IF NOT EXISTS `solar_minute` (
  `ts` varchar(21) NOT NULL DEFAULT '',
  `building` int(11) NOT NULL,
  `T` decimal(7,4) DEFAULT NULL,
  `SupTemp2` double DEFAULT NULL,
  `SupPres` double DEFAULT NULL,
  `SupTemp1` double DEFAULT NULL,
  `SupTemp` double DEFAULT NULL,
  `RetPres` double DEFAULT NULL,
  `AirTemp` double DEFAULT NULL,
  PRIMARY KEY (`ts`,`building`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `temp_minute`
--

CREATE TABLE IF NOT EXISTS `temp_minute` (
  `ts` varchar(21) NOT NULL DEFAULT '',
  `T1` double DEFAULT NULL,
  `T2` double DEFAULT NULL,
  `T3` double DEFAULT NULL,
  `T4` double DEFAULT NULL,
  `T5` double DEFAULT NULL,
  `T6` double DEFAULT NULL,
  `T7` double DEFAULT NULL,
  `T8` double DEFAULT NULL,
  `T9` double DEFAULT NULL,
  `T12` double DEFAULT NULL,
  `T13` double DEFAULT NULL,
  PRIMARY KEY (`ts`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
