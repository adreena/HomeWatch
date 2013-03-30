
CREATE OR REPLACE VIEW `v0_air` AS select `air`.`apt` AS `Apt`,
                               cast(`air`.`ts` as date) AS `Date`,
							   year(`air`.`ts`) AS `Year`,month(`air`.`ts`) AS `Month`,
							   dayofmonth(`air`.`ts`) AS `Day`,
							   hour(`air`.`ts`) AS `Hour`,
							   week(`air`.`ts`,0) AS `Week`,
							   dayofweek(`air`.`ts`) AS `Day of Week`,
							   dayname(`air`.`ts`) AS `Day Name`,
							   `air`.`co2` AS `CO2`,`air`.`rh` AS `Relative_Humidity`,
							   `air`.`temperature` AS `Temperature` 
							   from `air`;


CREATE OR REPLACE VIEW Air_Yearly 
							AS select `v0_air`.`Apt` AS `Apt`,
                            avg(`v0_air`.`Temperature`) AS `Temperature`,
							avg(`v0_air`.`Relative_Humidity`) AS `Relative_Humidity`,
							avg(`v0_air`.`CO2`) AS `CO2`,
							`v0_air`.`Year` AS `Year` 
							from `v0_air` group by `v0_air`.`Apt`,`v0_air`.`Year`;

CREATE OR REPLACE VIEW Air_Monthly 
							AS select `v0_air`.`Apt` AS `Apt`,
                            avg(`v0_air`.`Temperature`) AS `Temperature`,
							avg(`v0_air`.`Relative_Humidity`) AS `Relative_Humidity`,
							avg(`v0_air`.`CO2`) AS `CO2`,
							`v0_air`.`Year` AS `Year`,
							`v0_air`.`Month` AS `Month`
							from `v0_air` group by `v0_air`.`Apt`,`v0_air`.`Year`,`v0_air`.`month`;	
							
CREATE OR REPLACE VIEW Air_Weekly 
							AS select `v0_air`.`Apt` AS `Apt`,
                            avg(`v0_air`.`Temperature`) AS `Temperature`,
							avg(`v0_air`.`Relative_Humidity`) AS `Relative_Humidity`,
							avg(`v0_air`.`CO2`) AS `CO2`,
							`v0_air`.`Year` AS `Year`, 
							`v0_air`.`Week` AS `Week`
							from `v0_air` group by `v0_air`.`Apt`,`v0_air`.`Year`,`v0_air`.`Week`;

CREATE OR REPLACE VIEW Air_Daily 
							AS select `v0_air`.`Apt` AS `Apt`,
                            avg(`v0_air`.`Temperature`) AS `Temperature`,
							avg(`v0_air`.`Relative_Humidity`) AS `Relative_Humidity`,
							avg(`v0_air`.`CO2`) AS `CO2`,
							`v0_air`.`Date` AS `Date`
							from `v0_air` group by `v0_air`.`Apt`,`v0_air`.`Date`;							

CREATE OR REPLACE VIEW Air_Hourly
							AS select `v0_air`.`Apt` AS `Apt`,
                            avg(`v0_air`.`Temperature`) AS `Temperature`,
							avg(`v0_air`.`Relative_Humidity`) AS `Relative_Humidity`,
							avg(`v0_air`.`CO2`) AS `CO2`,
							`v0_air`.`Date` AS `Date`,
							`v0_air`.`Hour` AS `Hour`
							from `v0_air` group by `v0_air`.`Apt`,`v0_air`.`Date`,`v0_air`.`Hour`;		