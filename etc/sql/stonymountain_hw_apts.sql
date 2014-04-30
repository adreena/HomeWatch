-- phpMyAdmin SQL Dump
-- version 3.4.10.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jun 11, 2013 at 08:01 AM
-- Server version: 5.1.58
-- PHP Version: 5.2.17

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `stonymountain_hw_apts`
--

-- --------------------------------------------------------

--
-- Table structure for table `Achievements`
--

CREATE TABLE IF NOT EXISTS `Achievements` (
  `Achievement_ID` int(11) NOT NULL,
  `Name` varchar(100) NOT NULL,
  `Description` varchar(100) DEFAULT NULL,
  `Enabled_Icon` varchar(100) DEFAULT NULL,
  `Disabled_Icon` varchar(100) DEFAULT NULL,
  `Points` int(11) DEFAULT NULL,
  PRIMARY KEY (`Achievement_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `Achievements`
--

INSERT INTO `Achievements` (`Achievement_ID`, `Name`, `Description`, `Enabled_Icon`, `Disabled_Icon`, `Points`) VALUES
(1, 'water_under_x', 'Maintain water usage under x for a month!', 'water_under_x.png', 'no_water_under_x.png', 100),
(2, 'elec_under_x', 'Maintain electricity usage under x for a month!', 'elec_under_x.png', 'no_elec_under_x.png', 100),
(3, 'healthy_humidity', 'Maintain healthy levels of humidity for a month!', 'healthy_humidity.png', 'no_healthy_humidity.png', 100),
(4, 'healthy_co2', 'Maintain healthy levels of CO2 for a month!', 'healthy_co2.png', 'no_healthy_co2.png', 100),
(5, 'low_temp', 'Maintain indoor temperatures under 20 degrees Celsius for a week!', 'low_temp.png', 'no_low_temp.png', 100),
(6, 'decrease_water', 'Decrease water usage by 10% in one month!', 'decrease_water.png', 'no_decrease_water.png', 100),
(7, 'decrease_elec', 'Decrease electricity usage by 10% in one month!', 'decrease_elec.png', 'no_decrease_elec.png', 100);

-- --------------------------------------------------------

--
-- Table structure for table `Air_Daily`
--

CREATE TABLE IF NOT EXISTS `Air_Daily` (
  `Apt` int(11) NOT NULL,
  `Temperature` double DEFAULT NULL,
  `Relative_Humidity` double DEFAULT NULL,
  `CO2` decimal(14,4) DEFAULT NULL,
  `Date` date NOT NULL DEFAULT '0000-00-00',
  PRIMARY KEY (`Date`,`Apt`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `Air_Hourly`
--

CREATE TABLE IF NOT EXISTS `Air_Hourly` (
  `TS` varchar(18) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `apt` int(11) NOT NULL,
  `CO2` decimal(14,4) DEFAULT NULL,
  `Relative_Humidity` double DEFAULT NULL,
  `Temperature` double DEFAULT NULL,
  PRIMARY KEY (`TS`,`apt`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `Air_Monthly`
--

CREATE TABLE IF NOT EXISTS `Air_Monthly` (
  `Apt` int(11) NOT NULL,
  `Temperature` double DEFAULT NULL,
  `Relative_Humidity` double DEFAULT NULL,
  `CO2` decimal(14,4) DEFAULT NULL,
  `Year` int(4) NOT NULL DEFAULT '0',
  `Month` int(2) NOT NULL DEFAULT '0',
  PRIMARY KEY (`Year`,`Month`,`Apt`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `Air_Weekly`
--

CREATE TABLE IF NOT EXISTS `Air_Weekly` (
  `Apt` int(11) NOT NULL,
  `Temperature` double DEFAULT NULL,
  `Relative_Humidity` double DEFAULT NULL,
  `CO2` decimal(14,4) DEFAULT NULL,
  `Year` int(4) NOT NULL DEFAULT '0',
  `Week` int(2) NOT NULL DEFAULT '0',
  PRIMARY KEY (`Year`,`Week`,`Apt`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `Air_Yearly`
--

CREATE TABLE IF NOT EXISTS `Air_Yearly` (
  `Apt` int(11) NOT NULL,
  `Temperature` double DEFAULT NULL,
  `Relative_Humidity` double DEFAULT NULL,
  `CO2` decimal(14,4) DEFAULT NULL,
  `Year` int(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`Year`,`Apt`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `Alerts`
--

CREATE TABLE IF NOT EXISTS `Alerts` (
  `Alert_ID` int(11) NOT NULL AUTO_INCREMENT,
  `Name` varchar(255) NOT NULL,
  `Value` varchar(255) NOT NULL,
  `Description` varchar(255) NOT NULL,
  PRIMARY KEY (`Alert_ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=17 ;

-- --------------------------------------------------------

--
-- Table structure for table `apt_info`
--

CREATE TABLE IF NOT EXISTS `apt_info` (
  `apt` int(11) NOT NULL,
  `floor` int(11) NOT NULL,
  `type` char(1) NOT NULL,
  `orientation` char(1) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `apt_info`
--

INSERT INTO `apt_info` (`apt`, `floor`, `type`, `orientation`) VALUES
(1, 3, 'B', 'S'),
(2, 4, 'A', 'S'),
(3, 2, 'A', 'S'),
(4, 1, 'B', 'S'),
(5, 2, 'B', 'S'),
(6, 4, 'A', 'N'),
(7, 3, 'A', 'N'),
(8, 4, 'B', 'S'),
(9, 1, 'A', 'N'),
(10, 1, 'A', 'S'),
(11, 2, 'A', 'N'),
(12, 3, 'A', 'S');

-- --------------------------------------------------------

--
-- Table structure for table `BasEnergy_Daily`
--

CREATE TABLE IF NOT EXISTS `BasEnergy_Daily` (
  `Date` date NOT NULL DEFAULT '0000-00-00',
  `P11` double unsigned DEFAULT NULL,
  `P12` double unsigned DEFAULT NULL,
  `HP1` double unsigned DEFAULT NULL,
  `HP2` double unsigned DEFAULT NULL,
  `HP3` double unsigned DEFAULT NULL,
  `HP4` double unsigned DEFAULT NULL,
  PRIMARY KEY (`Date`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `BasEnergy_Hourly`
--

CREATE TABLE IF NOT EXISTS `BasEnergy_Hourly` (
  `TS` varchar(18) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `P11` double unsigned DEFAULT NULL,
  `P12` double unsigned DEFAULT NULL,
  `HP1` double unsigned DEFAULT NULL,
  `HP2` double unsigned DEFAULT NULL,
  `HP3` double unsigned DEFAULT NULL,
  `HP4` double unsigned DEFAULT NULL,
  PRIMARY KEY (`TS`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `BasEnergy_Monthly`
--

CREATE TABLE IF NOT EXISTS `BasEnergy_Monthly` (
  `Year` int(4) NOT NULL DEFAULT '0',
  `Month` int(2) NOT NULL DEFAULT '0',
  `P11` double unsigned DEFAULT NULL,
  `P12` double unsigned DEFAULT NULL,
  `HP1` double unsigned DEFAULT NULL,
  `HP2` double unsigned DEFAULT NULL,
  `HP3` double unsigned DEFAULT NULL,
  `HP4` double unsigned DEFAULT NULL,
  PRIMARY KEY (`Year`,`Month`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `BasEnergy_Weekly`
--

CREATE TABLE IF NOT EXISTS `BasEnergy_Weekly` (
  `Year` int(4) NOT NULL DEFAULT '0',
  `Week` int(2) NOT NULL DEFAULT '0',
  `P11` double unsigned DEFAULT NULL,
  `P12` double unsigned DEFAULT NULL,
  `HP1` double unsigned DEFAULT NULL,
  `HP2` double unsigned DEFAULT NULL,
  `HP3` double unsigned DEFAULT NULL,
  `HP4` double unsigned DEFAULT NULL,
  PRIMARY KEY (`Year`,`Week`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `BasEnergy_Yearly`
--

CREATE TABLE IF NOT EXISTS `BasEnergy_Yearly` (
  `Year` int(4) NOT NULL DEFAULT '0',
  `P11` double unsigned DEFAULT NULL,
  `P12` double unsigned DEFAULT NULL,
  `HP1` double unsigned DEFAULT NULL,
  `HP2` double unsigned DEFAULT NULL,
  `HP3` double unsigned DEFAULT NULL,
  `HP4` double unsigned DEFAULT NULL,
  PRIMARY KEY (`Year`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `bas_el_energy_cur`
--

CREATE TABLE IF NOT EXISTS `bas_el_energy_cur` (
  `ts` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `P-1-1` double unsigned DEFAULT NULL,
  `P-1-2` double unsigned DEFAULT NULL,
  `HP1` double unsigned DEFAULT NULL,
  `HP2` double unsigned DEFAULT NULL,
  `HP3` double unsigned DEFAULT NULL,
  `HP4` double unsigned DEFAULT NULL,
  PRIMARY KEY (`ts`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `Building`
--

CREATE TABLE IF NOT EXISTS `Building` (
  `Building_ID` int(11) NOT NULL,
  `Resident_ID` int(11) NOT NULL,
  `Building` varchar(100) NOT NULL,
  `Floor` varchar(100) NOT NULL,
  `Orientation` varchar(100) NOT NULL,
  `Layout` varchar(100) NOT NULL,
  PRIMARY KEY (`Building_ID`,`Resident_ID`),
  KEY `Resident_ID` (`Resident_ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `Building`
--

INSERT INTO `Building` (`Building_ID`, `Resident_ID`, `Building`, `Floor`, `Orientation`, `Layout`) VALUES
(1, 1, 'Windsor', '4', 'NW', 'A');

-- --------------------------------------------------------

--
-- Table structure for table `Constants`
--

CREATE TABLE IF NOT EXISTS `Constants` (
  `Constant_ID` int(11) NOT NULL AUTO_INCREMENT,
  `Name` varchar(255) NOT NULL,
  `Value` double NOT NULL,
  `Description` varchar(255) NOT NULL,
  PRIMARY KEY (`Constant_ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `data_types`
--

CREATE TABLE IF NOT EXISTS `data_types` (
  `type_id` int(11) NOT NULL AUTO_INCREMENT,
  `type_desc` varchar(255) CHARACTER SET latin1 NOT NULL,
  `unit` varchar(255) CHARACTER SET latin1 NOT NULL,
  PRIMARY KEY (`type_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=13 ;

--
-- Dumping data for table `data_types`
--

INSERT INTO `data_types` (`type_id`, `type_desc`, `unit`) VALUES
(1, 'CO2', 'PPM'),
(2, 'Cost', '$'),
(3, 'Electricity', 'kWh'),
(4, 'Energy', 'KJ'),
(5, 'Energy', 'Wh'),
(6, 'Heat Flux', 'W/m²'),
(7, 'Relative Humidity', '%'),
(8, 'Temperature', '°C'),
(9, 'Water Volume', 'gallons'),
(10, 'Water Volme', 'L');

-- --------------------------------------------------------

--
-- Table structure for table `Earned_Achievements`
--

CREATE TABLE IF NOT EXISTS `Earned_Achievements` (
  `Resident_ID` int(11) NOT NULL,
  `Achievement_ID` int(11) NOT NULL,
  `Date_Earned` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`Resident_ID`,`Achievement_ID`),
  KEY `Achievement_ID` (`Achievement_ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `el_energy_cur`
--

CREATE TABLE IF NOT EXISTS `el_energy_cur` (
  `ts` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `apt` int(11) NOT NULL,
  `phase` char(1) NOT NULL,
  `ch1` double unsigned NOT NULL,
  `ch2` double unsigned NOT NULL,
  `aux1` double unsigned NOT NULL,
  `aux2` double unsigned NOT NULL,
  `aux3` double unsigned NOT NULL,
  `aux4` double unsigned NOT NULL,
  `aux5` double unsigned NOT NULL,
  PRIMARY KEY (`ts`,`apt`,`phase`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `El_Energy_Daily`
--

CREATE TABLE IF NOT EXISTS `El_Energy_Daily` (
  `Apt` int(11) NOT NULL,
  `Phase` char(1) NOT NULL,
  `Ch1` double unsigned DEFAULT NULL,
  `Ch2` double unsigned DEFAULT NULL,
  `AUX1` double unsigned DEFAULT NULL,
  `AUX2` double unsigned DEFAULT NULL,
  `AUX3` double unsigned DEFAULT NULL,
  `AUX4` double unsigned DEFAULT NULL,
  `AUX5` double unsigned DEFAULT NULL,
  `Date` date NOT NULL DEFAULT '0000-00-00',
  PRIMARY KEY (`Date`,`Apt`,`Phase`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `El_Energy_Hourly`
--

CREATE TABLE IF NOT EXISTS `El_Energy_Hourly` (
  `TS` varchar(18) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `apt` int(11) NOT NULL,
  `phase` char(1) NOT NULL,
  `Ch1` double unsigned DEFAULT NULL,
  `Ch2` double unsigned DEFAULT NULL,
  `AUX1` double unsigned DEFAULT NULL,
  `AUX2` double unsigned DEFAULT NULL,
  `AUX3` double unsigned DEFAULT NULL,
  `AUX4` double unsigned DEFAULT NULL,
  `AUX5` double unsigned DEFAULT NULL,
  PRIMARY KEY (`TS`,`apt`,`phase`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `El_Energy_Monthly`
--

CREATE TABLE IF NOT EXISTS `El_Energy_Monthly` (
  `Apt` int(11) NOT NULL,
  `Phase` char(1) NOT NULL,
  `Ch1` double unsigned DEFAULT NULL,
  `Ch2` double unsigned DEFAULT NULL,
  `AUX1` double unsigned DEFAULT NULL,
  `AUX2` double unsigned DEFAULT NULL,
  `AUX3` double unsigned DEFAULT NULL,
  `AUX4` double unsigned DEFAULT NULL,
  `AUX5` double unsigned DEFAULT NULL,
  `Year` int(4) NOT NULL DEFAULT '0',
  `Month` int(2) NOT NULL DEFAULT '0',
  PRIMARY KEY (`Year`,`Month`,`Apt`,`Phase`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `El_Energy_Weekly`
--

CREATE TABLE IF NOT EXISTS `El_Energy_Weekly` (
  `Apt` int(11) NOT NULL,
  `Phase` char(1) NOT NULL,
  `Ch1` double unsigned DEFAULT NULL,
  `Ch2` double unsigned DEFAULT NULL,
  `AUX1` double unsigned DEFAULT NULL,
  `AUX2` double unsigned DEFAULT NULL,
  `AUX3` double unsigned DEFAULT NULL,
  `AUX4` double unsigned DEFAULT NULL,
  `AUX5` double unsigned DEFAULT NULL,
  `Year` int(4) NOT NULL DEFAULT '0',
  `Week` int(2) NOT NULL DEFAULT '0',
  PRIMARY KEY (`Year`,`Week`,`Apt`,`Phase`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `El_Energy_Yearly`
--

CREATE TABLE IF NOT EXISTS `El_Energy_Yearly` (
  `Apt` int(11) NOT NULL,
  `Phase` char(1) NOT NULL,
  `Ch1` double unsigned DEFAULT NULL,
  `Ch2` double unsigned DEFAULT NULL,
  `AUX1` double unsigned DEFAULT NULL,
  `AUX2` double unsigned DEFAULT NULL,
  `AUX3` double unsigned DEFAULT NULL,
  `AUX4` double unsigned DEFAULT NULL,
  `AUX5` double unsigned DEFAULT NULL,
  `Year` int(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`Year`,`Apt`,`Phase`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `Equations`
--

CREATE TABLE IF NOT EXISTS `Equations` (
  `Equation_ID` int(11) NOT NULL AUTO_INCREMENT,
  `Name` varchar(255) NOT NULL,
  `Value` varchar(255) NOT NULL,
  `Description` varchar(255) NOT NULL,
  `Type` int(11) NOT NULL,
  PRIMARY KEY (`Equation_ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=52 ;

--
-- Dumping data for table `Equations`
--

INSERT INTO `Equations` (`Equation_ID`, `Name`, `Value`, `Description`, `Type`) VALUES
(5, 'Cold Water', '$water_total$ - $water_hot$', 'Cold water usage', 9),
(37, 'Oven', '$elec_aux1_phasea$+$elec_aux1_phaseb$', 'Total electrical usage of the stove/oven', 3),
(38, 'HWT Total', '$elec_aux4_phaseb$ * 2', 'Total electrical usage of the Hot Water Tank', 3);

-- --------------------------------------------------------

--
-- Table structure for table `heating_cur`
--

CREATE TABLE IF NOT EXISTS `heating_cur` (
  `ts` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `apt` int(11) NOT NULL,
  `total_energy` double NOT NULL COMMENT '(Wh)',
  `total_vol` double NOT NULL COMMENT '(L)',
  `total_mass` double NOT NULL COMMENT '(g)',
  PRIMARY KEY (`ts`,`apt`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `Heating_Daily`
--

CREATE TABLE IF NOT EXISTS `Heating_Daily` (
  `Apt` int(11) NOT NULL,
  `Total_Energy` double DEFAULT NULL,
  `Total_Volume` double DEFAULT NULL,
  `Total_Mass` double DEFAULT NULL,
  `Date` date NOT NULL DEFAULT '0000-00-00',
  PRIMARY KEY (`Date`,`Apt`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `Heating_Hourly`
--

CREATE TABLE IF NOT EXISTS `Heating_Hourly` (
  `TS` varchar(18) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `apt` int(11) NOT NULL,
  `Total_Energy` double DEFAULT NULL,
  `Total_Volume` double DEFAULT NULL,
  `Total_Mass` double DEFAULT NULL,
  PRIMARY KEY (`TS`,`apt`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `Heating_Monthly`
--

CREATE TABLE IF NOT EXISTS `Heating_Monthly` (
  `Apt` int(11) NOT NULL,
  `Total_Energy` double DEFAULT NULL,
  `Total_Volume` double DEFAULT NULL,
  `Total_Mass` double DEFAULT NULL,
  `Year` int(4) NOT NULL DEFAULT '0',
  `Month` int(2) NOT NULL DEFAULT '0',
  PRIMARY KEY (`Year`,`Month`,`Apt`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `Heating_Water_Daily`
--

CREATE TABLE IF NOT EXISTS `Heating_Water_Daily` (
  `Apt` int(11) NOT NULL,
  `Current_Flow` double DEFAULT NULL,
  `Current_Temperature_1` double DEFAULT NULL,
  `Current_Temperature_2` double DEFAULT NULL,
  `Date` date NOT NULL DEFAULT '0000-00-00',
  PRIMARY KEY (`Date`,`Apt`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `Heating_Water_Hourly`
--

CREATE TABLE IF NOT EXISTS `Heating_Water_Hourly` (
  `TS` varchar(18) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `apt` int(11) NOT NULL,
  `Current_Flow` double DEFAULT NULL,
  `Current_Temperature_1` double DEFAULT NULL,
  `Current_Temperature_2` double DEFAULT NULL,
  PRIMARY KEY (`TS`,`apt`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `Heating_Water_Monthly`
--

CREATE TABLE IF NOT EXISTS `Heating_Water_Monthly` (
  `Apt` int(11) NOT NULL,
  `Current_Flow` double DEFAULT NULL,
  `Current_Temperature_1` double DEFAULT NULL,
  `Current_Temperature_2` double DEFAULT NULL,
  `Year` int(4) NOT NULL DEFAULT '0',
  `Month` int(2) NOT NULL DEFAULT '0',
  PRIMARY KEY (`Year`,`Month`,`Apt`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `Heating_Water_Weekly`
--

CREATE TABLE IF NOT EXISTS `Heating_Water_Weekly` (
  `Apt` int(11) NOT NULL,
  `Current_Flow` double DEFAULT NULL,
  `Current_Temperature_1` double DEFAULT NULL,
  `Current_Temperature_2` double DEFAULT NULL,
  `Year` int(4) NOT NULL DEFAULT '0',
  `Week` int(2) NOT NULL DEFAULT '0',
  PRIMARY KEY (`Year`,`Week`,`Apt`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `Heating_Water_Yearly`
--

CREATE TABLE IF NOT EXISTS `Heating_Water_Yearly` (
  `Apt` int(11) NOT NULL,
  `Current_Flow` double DEFAULT NULL,
  `Current_Temperature_1` double DEFAULT NULL,
  `Current_Temperature_2` double DEFAULT NULL,
  `Year` int(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`Year`,`Apt`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `Heating_Weekly`
--

CREATE TABLE IF NOT EXISTS `Heating_Weekly` (
  `Apt` int(11) NOT NULL,
  `Total_Energy` double DEFAULT NULL,
  `Total_Volume` double DEFAULT NULL,
  `Total_Mass` double DEFAULT NULL,
  `Year` int(4) NOT NULL DEFAULT '0',
  `Week` int(2) NOT NULL DEFAULT '0',
  PRIMARY KEY (`Year`,`Week`,`Apt`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `Heating_Yearly`
--

CREATE TABLE IF NOT EXISTS `Heating_Yearly` (
  `Apt` int(11) NOT NULL,
  `Total_Energy` double DEFAULT NULL,
  `Total_Volume` double DEFAULT NULL,
  `Total_Mass` double DEFAULT NULL,
  `Year` int(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`Year`,`Apt`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `heat_flux`
--

CREATE TABLE IF NOT EXISTS `heat_flux` (
  `ts` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `apt` int(11) NOT NULL,
  `stud` double NOT NULL COMMENT '(W/m²)',
  `insulation` double NOT NULL COMMENT '(W/m²)',
  PRIMARY KEY (`ts`,`apt`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `Heat_Flux_Daily`
--

CREATE TABLE IF NOT EXISTS `Heat_Flux_Daily` (
  `Apt` int(11) NOT NULL,
  `Heatflux_stud` double DEFAULT NULL,
  `HeatFlux_Insulation` double DEFAULT NULL,
  `Date` date NOT NULL DEFAULT '0000-00-00',
  PRIMARY KEY (`Date`,`Apt`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `Heat_Flux_Hourly`
--

CREATE TABLE IF NOT EXISTS `Heat_Flux_Hourly` (
  `TS` varchar(18) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `apt` int(11) NOT NULL,
  `Heatflux_stud` double DEFAULT NULL,
  `HeatFlux_Insulation` double DEFAULT NULL,
  PRIMARY KEY (`TS`,`apt`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `Heat_Flux_Monthly`
--

CREATE TABLE IF NOT EXISTS `Heat_Flux_Monthly` (
  `Apt` int(11) NOT NULL,
  `Heatflux_stud` double DEFAULT NULL,
  `HeatFlux_Insulation` double DEFAULT NULL,
  `Year` int(4) NOT NULL DEFAULT '0',
  `Month` int(2) NOT NULL DEFAULT '0',
  PRIMARY KEY (`Year`,`Month`,`Apt`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `Heat_Flux_Weekly`
--

CREATE TABLE IF NOT EXISTS `Heat_Flux_Weekly` (
  `Apt` int(11) NOT NULL,
  `Heatflux_stud` double DEFAULT NULL,
  `HeatFlux_Insulation` double DEFAULT NULL,
  `Year` int(4) NOT NULL DEFAULT '0',
  `Week` int(2) NOT NULL DEFAULT '0',
  PRIMARY KEY (`Year`,`Week`,`Apt`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `Heat_Flux_Yearly`
--

CREATE TABLE IF NOT EXISTS `Heat_Flux_Yearly` (
  `Apt` int(11) NOT NULL,
  `HeatFlux_stud` double DEFAULT NULL,
  `HeatFlux_Insulation` double DEFAULT NULL,
  `Year` int(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`Year`,`Apt`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `OutsideTemp_Daily`
--

CREATE TABLE IF NOT EXISTS `OutsideTemp_Daily` (
  `Date` date NOT NULL DEFAULT '0000-00-00',
  `OutSide_Temperature` double DEFAULT NULL,
  PRIMARY KEY (`Date`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `OutsideTemp_Hourly`
--

CREATE TABLE IF NOT EXISTS `OutsideTemp_Hourly` (
  `TS` varchar(18) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `OutSide_Temperature` double DEFAULT NULL,
  PRIMARY KEY (`TS`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `OutsideTemp_Monthly`
--

CREATE TABLE IF NOT EXISTS `OutsideTemp_Monthly` (
  `Year` int(4) NOT NULL DEFAULT '0',
  `Month` int(2) NOT NULL DEFAULT '0',
  `OutSide_Temperature` double DEFAULT NULL,
  PRIMARY KEY (`Year`,`Month`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `OutsideTemp_Weekly`
--

CREATE TABLE IF NOT EXISTS `OutsideTemp_Weekly` (
  `Year` int(4) NOT NULL DEFAULT '0',
  `Week` int(2) NOT NULL DEFAULT '0',
  `OutSide_Temperature` double DEFAULT NULL,
  PRIMARY KEY (`Year`,`Week`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `OutsideTemp_Yearly`
--

CREATE TABLE IF NOT EXISTS `OutsideTemp_Yearly` (
  `Year` int(4) NOT NULL DEFAULT '0',
  `OutSide_Temperature` double DEFAULT NULL,
  PRIMARY KEY (`Year`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `Outside_Temp`
--

CREATE TABLE IF NOT EXISTS `Outside_Temp` (
  `ts` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `Temp` double NOT NULL,
  KEY `ts` (`ts`),
  KEY `Temp` (`Temp`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `Resident`
--

CREATE TABLE IF NOT EXISTS `Resident` (
  `Resident_ID` int(11) NOT NULL AUTO_INCREMENT,
  `Name` varchar(100) NOT NULL,
  `Username` varchar(30) NOT NULL,
  `Room_Number` int(11) NOT NULL,
  `Location` varchar(100) NOT NULL,
  `Points` int(11) DEFAULT NULL,
  `Room_Status` varchar(10) DEFAULT NULL,
  `Score` int(11) DEFAULT NULL,
  PRIMARY KEY (`Resident_ID`),
  KEY `Username` (`Username`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `Resident`
--

INSERT INTO `Resident` (`Resident_ID`, `Name`, `Username`, `Room_Number`, `Location`, `Points`, `Room_Status`, `Score`) VALUES
(1, 'TEST', 'tsung', 1212, 'NE', 2323, 'TEST', 0),
(2, 'resident1', 'resident1', 413, 'SW', 123, 'Occupied', 0),
(3, 'Mr. Popo', 'test2', 42, 'NE', NULL, 'Occupied', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `Roles`
--

CREATE TABLE IF NOT EXISTS `Roles` (
  `Role_ID` int(11) NOT NULL,
  `Role` varchar(30) NOT NULL,
  PRIMARY KEY (`Role_ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `Roles`
--

INSERT INTO `Roles` (`Role_ID`, `Role`) VALUES
(1, 'DEV'),
(2, 'ADMIN'),
(3, 'MANAGER'),
(4, 'ENGINEER'),
(5, 'RESIDENT');

-- --------------------------------------------------------

--
-- Table structure for table `TotalElec_Daily`
--

CREATE TABLE IF NOT EXISTS `TotalElec_Daily` (
  `Total_P1` double DEFAULT NULL,
  `Total_HP` double DEFAULT NULL,
  `Date` date NOT NULL DEFAULT '0000-00-00',
  PRIMARY KEY (`Date`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `TotalElec_Hourly`
--

CREATE TABLE IF NOT EXISTS `TotalElec_Hourly` (
  `TS` varchar(18) NOT NULL DEFAULT '',
  `Total_P1` double DEFAULT NULL,
  `Total_HP` double DEFAULT NULL,
  PRIMARY KEY (`TS`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `TotalElec_Monthly`
--

CREATE TABLE IF NOT EXISTS `TotalElec_Monthly` (
  `Year` int(4) NOT NULL DEFAULT '0',
  `Month` int(2) NOT NULL DEFAULT '0',
  `Total_P1` double DEFAULT NULL,
  `Total_HP` double DEFAULT NULL,
  PRIMARY KEY (`Year`,`Month`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `TotalElec_Weekly`
--

CREATE TABLE IF NOT EXISTS `TotalElec_Weekly` (
  `Year` int(4) NOT NULL DEFAULT '0',
  `Week` int(2) NOT NULL DEFAULT '0',
  `Total_P1` double DEFAULT NULL,
  `Total_HP` double DEFAULT NULL,
  PRIMARY KEY (`Year`,`Week`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `TotalElec_Yearly`
--

CREATE TABLE IF NOT EXISTS `TotalElec_Yearly` (
  `Year` int(4) NOT NULL DEFAULT '0',
  `Total_P1` double DEFAULT NULL,
  `Total_HP` double DEFAULT NULL,
  PRIMARY KEY (`Year`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `Users`
--

CREATE TABLE IF NOT EXISTS `Users` (
  `User_ID` int(11) NOT NULL AUTO_INCREMENT,
  `Username` varchar(30) NOT NULL,
  `PW_Hash` varchar(255) NOT NULL,
  `Role_ID` int(11) NOT NULL,
  `Email` varchar(255) NOT NULL,
  `Reset_Token` varchar(32) DEFAULT NULL,
  PRIMARY KEY (`User_ID`),
  UNIQUE KEY `Username` (`Username`),
  UNIQUE KEY `Email` (`Email`),
  KEY `Role_ID` (`Role_ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=49 ;

--
-- Dumping data for table `Users`
--

INSERT INTO `Users` (`User_ID`, `Username`, `PW_Hash`, `Role_ID`, `Email`, `Reset_Token`) VALUES
(46, 'eng', '$2y$10$j1LMmRJJ3.RstM3xRbz81eJqiIE/Rpuxcyrece1tF0c5tMb2XSnIO', 4, 't@t.ca', NULL),
(47, 'mngr', '$2y$10$1MVcQ6XoOg/V.ZLBw6pzPufPipH0d2Rc36eRFbyutQrMP0Wbc6OrC', 3, 'd@d.ca', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `User_Alerts`
--

CREATE TABLE IF NOT EXISTS `User_Alerts` (
  `User_ID` int(11) NOT NULL,
  `Alert_ID` int(11) NOT NULL,
  PRIMARY KEY (`User_ID`,`Alert_ID`),
  KEY `Alert_ID` (`Alert_ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `User_Constants`
--

CREATE TABLE IF NOT EXISTS `User_Constants` (
  `User_ID` int(11) NOT NULL,
  `Constant_ID` int(11) NOT NULL,
  PRIMARY KEY (`User_ID`,`Constant_ID`),
  KEY `Constant_ID` (`Constant_ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `User_Equations`
--

CREATE TABLE IF NOT EXISTS `User_Equations` (
  `User_ID` int(11) NOT NULL,
  `Equation_ID` int(11) NOT NULL,
  PRIMARY KEY (`User_ID`,`Equation_ID`),
  KEY `Equation_ID` (`Equation_ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `Utilities_Prices`
--

CREATE TABLE IF NOT EXISTS `Utilities_Prices` (
  `Utility_ID` int(11) NOT NULL AUTO_INCREMENT,
  `Type` varchar(100) NOT NULL,
  `Price` float NOT NULL,
  `Start_Date` date NOT NULL,
  `End_Date` date NOT NULL,
  PRIMARY KEY (`Utility_ID`,`Type`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=34 ;

--
-- Dumping data for table `Utilities_Prices`
--

INSERT INTO `Utilities_Prices` (`Utility_ID`, `Type`, `Price`, `Start_Date`, `End_Date`) VALUES
(29, 'electricity', 0.1, '2013-03-01', '2013-04-30'),
(33, 'water', 0.00568, '2012-03-01', '2013-12-30');

-- --------------------------------------------------------

--
-- Table structure for table `V_Achievements`
--

CREATE TABLE IF NOT EXISTS `V_Achievements` (
  `Name` varchar(100) DEFAULT NULL,
  `Description` varchar(100) DEFAULT NULL,
  `Date_Earned` varchar(100) DEFAULT NULL,
  `Points` int(11) DEFAULT NULL,
  `Disabled_Icon` varchar(100) DEFAULT NULL,
  `Enabled_Icon` varchar(100) DEFAULT NULL,
  `Resident_ID` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `water_cur`
--

CREATE TABLE IF NOT EXISTS `water_cur` (
  `ts` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `apt` int(11) NOT NULL,
  `hot` int(11) NOT NULL,
  `total` int(11) NOT NULL,
  PRIMARY KEY (`ts`,`apt`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `Water_Daily`
--

CREATE TABLE IF NOT EXISTS `Water_Daily` (
  `Apt` int(11) NOT NULL,
  `Hot_Water` bigint(12) DEFAULT NULL,
  `Total_Water` bigint(12) DEFAULT NULL,
  `Date` date NOT NULL DEFAULT '0000-00-00',
  PRIMARY KEY (`Date`,`Apt`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `Water_Hourly`
--

CREATE TABLE IF NOT EXISTS `Water_Hourly` (
  `TS` varchar(18) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `apt` int(11) NOT NULL,
  `Hot_Water` bigint(12) DEFAULT NULL,
  `Total_Water` bigint(12) DEFAULT NULL,
  PRIMARY KEY (`TS`,`apt`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `Water_Monthly`
--

CREATE TABLE IF NOT EXISTS `Water_Monthly` (
  `Apt` int(11) NOT NULL,
  `Hot_Water` bigint(12) DEFAULT NULL,
  `Total_Water` bigint(12) DEFAULT NULL,
  `Year` int(4) NOT NULL DEFAULT '0',
  `Month` int(2) NOT NULL DEFAULT '0',
  PRIMARY KEY (`Year`,`Month`,`Apt`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `Water_Weekly`
--

CREATE TABLE IF NOT EXISTS `Water_Weekly` (
  `Apt` int(11) NOT NULL,
  `Hot_Water` bigint(12) DEFAULT NULL,
  `Total_Water` bigint(12) DEFAULT NULL,
  `Year` int(4) NOT NULL DEFAULT '0',
  `Week` int(2) NOT NULL DEFAULT '0',
  PRIMARY KEY (`Year`,`Week`,`Apt`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `Water_Yearly`
--

CREATE TABLE IF NOT EXISTS `Water_Yearly` (
  `Apt` int(11) NOT NULL,
  `Hot_Water` bigint(12) DEFAULT NULL,
  `Total_Water` bigint(12) DEFAULT NULL,
  `Year` int(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`Year`,`Apt`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `Weather_Forecast`
--

CREATE TABLE IF NOT EXISTS `Weather_Forecast` (
  `Year` int(11) NOT NULL,
  `Month` int(11) NOT NULL,
  `Day` int(11) NOT NULL,
  `Hour` varchar(11) NOT NULL,
  `External_Temperature` float DEFAULT NULL COMMENT '°C',
  `External_Relative_Humidity` float DEFAULT NULL COMMENT '%',
  `Wind_Speed` float DEFAULT NULL COMMENT 'KM/H',
  `Wind_Direction` varchar(12) DEFAULT NULL COMMENT 'Cardinals',
  PRIMARY KEY (`Year`,`Month`,`Hour`,`Day`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
