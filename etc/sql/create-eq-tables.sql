CREATE TABLE IF NOT EXISTS `Equations` (
  `Equation_ID` int(11) NOT NULL,
  `Name` varchar(255) NOT NULL,
  `Value` varchar(255) NOT NULL,
  `Description` varchar(255) NOT NULL,
  PRIMARY KEY (`Equation_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `Constants` (
  `Constant_ID` int(11) NOT NULL,
  `Name` varchar(255) NOT NULL,
  `Value` double NOT NULL,
  `Description` varchar(255) NOT NULL,
  PRIMARY KEY (`Constant_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `User_Equations` (
  `User_ID` int(11) NOT NULL,
  `Equation_ID` int(11) NOT NULL,
  PRIMARY KEY (`User_ID`),
  KEY `Equation_ID` (`Equation_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `User_Constants` (
  `User_ID` int(11) NOT NULL,
  `Constant_ID` int(11) NOT NULL,
  PRIMARY KEY (`User_ID`),
  KEY `Constant_ID` (`Constant_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `User_Constants`
  ADD CONSTRAINT `User_Constants_ibfk_2` FOREIGN KEY (`Constant_ID`) REFERENCES `Constants` (`Constant_ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `User_Constants_ibfk_1` FOREIGN KEY (`User_ID`) REFERENCES `Users` (`User_ID`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `User_Equations`
  ADD CONSTRAINT `User_Equations_ibfk_2` FOREIGN KEY (`User_ID`) REFERENCES `Users` (`User_ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `User_Equations_ibfk_1` FOREIGN KEY (`Equation_ID`) REFERENCES `Equations` (`Equation_ID`) ON DELETE CASCADE ON UPDATE CASCADE;