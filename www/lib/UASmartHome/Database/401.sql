
SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `401`

--
-- DROP TABLE
--
DROP TABLE IF EXISTS  User_Role,`earned_achievements`,Resident,building,achievements,roles,users;


CREATE TABLE IF NOT EXISTS `Resident` (
  Resident_ID int NOT NULL,
  Name varchar(100) NOT NULL,
  Username varchar(30) NOT NULL,
  Room_Number int NOT NULL,
  Location varchar(100) NOT NULL,
  Points int DEFAULT NULL,
  Room_Status varchar(10) DEFAULT NULL,
  PRIMARY KEY (Resident_ID)
  ) ENGINE=InnoDB;

  

CREATE TABLE IF NOT EXISTS `Building` (
  `Building_ID` int(11) NOT NULL,
  `Resident_ID` int NOT NULL,
  `Building` varchar(100) NOT NULL,
  `Floor` varchar(100) NOT NULL,
  `Orientation` varchar(100) NOT NULL,
  `Layout` varchar(100) NOT NULL,
  PRIMARY KEY (`Building_ID`,Resident_ID),
  FOREIGN KEY (Resident_ID) REFERENCES Resident(Resident_ID)ON DELETE CASCADE ON UPDATE CASCADE
  ) ENGINE=InnoDB;


CREATE TABLE IF NOT EXISTS Achievements (
  Achievement_ID int NOT NULL,
  Name varchar(100) NOT NULL,
  Description varchar(100) DEFAULT NULL,
  Enabled_Icon varchar(100) DEFAULT NULL,
  Disabled_Icon varchar(100) DEFAULT NULL,
  Points int DEFAULT NULL,
  PRIMARY KEY (Achievement_ID)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `Earned_Achievements` (
  Resident_ID int NOT NULL,
  Achievement_ID int NOT NULL,
  Date_Earned varchar(100) DEFAULT NULL,
  PRIMARY KEY (Resident_ID,Achievement_ID),
  FOREIGN KEY (Resident_ID) REFERENCES Resident(Resident_ID)ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (Achievement_ID) REFERENCES Achievements(Achievement_ID)ON DELETE CASCADE ON UPDATE CASCADE 
  
) ENGINE=InnoDB;


CREATE TABLE IF NOT EXISTS Users (
  User_ID Int NOT NULL AUTO_INCREMENT,
  Username varchar(30) NOT NULL,
  Password varchar(64) NOT NULL,
  Salt varchar(3) NOT NULL,
  PRIMARY KEY (User_ID),
  UNIQUE KEY Username (Username)
) ENGINE=InnoDB AUTO_INCREMENT=1;

CREATE TABLE IF NOT EXISTS Roles (
  Role_ID int NOT NULL,
  Role varchar(30) NOT NULL,
  PRIMARY KEY (Role_ID)
) ENGINE=InnoDB ;

CREATE TABLE IF NOT EXISTS User_Role (
  Role_ID int NOT NULL,
  User_ID int NOT NULL,
  PRIMARY KEY (Role_ID,User_ID),
  FOREIGN KEY (Role_ID) REFERENCES Roles(Role_ID) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (User_ID) REFERENCES Users(User_ID) ON DELETE CASCADE ON UPDATE CASCADE
 ) ENGINE=InnoDB 


