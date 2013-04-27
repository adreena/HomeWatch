use smarthome_bas;
drop table EnergyD_Graph_t;
create table EnergyD_Graph_t  select * from EnergyD_Graph;
ALTER TABLE `EnergyD_Graph_t` ADD PRIMARY KEY ( `ts` , `building` ) ;

drop table EnergyH_Graph_t;
create table EnergyH_Graph_t  select * from EnergyH_Graph;
ALTER TABLE `EnergyH_Graph_t` ADD PRIMARY KEY ( `ts` , `building` ) ;

drop table Energy_Minute_t;
create table Energy_Minute_t   select * from Energy_Minute;
ALTER TABLE `Energy_Minute_t` ADD PRIMARY KEY ( `ts` , `building` ) ;
