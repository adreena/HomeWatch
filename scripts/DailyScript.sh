#!/bin/bash
# Script File to Install the DB 
# The  User Name and Password need to be written in the script to update DB tables automatically 
# This Script will run daily at 1 am in the morning to update the tables and views.

user=root
password=n342m8wu9
DB1=TEST1
DB2=TEST2

########################################################################
#Update the Views for the  Air,Water,Heating,Heat_flux and  El_Energy
#Each Table should get 4 Views  
#########################################################################
# A) Update the V Views  
mysql -u $user -p$password $DB<v0_views.sql
# A) Update the View for Air 
mysql -u $user -p$password $DB<Air_views.sql

# B)Update the view for Water 
mysql -u $user -p$password $DB<Water_views.sql

# C) Update the View for Heating 
mysql -u $user -p$password $DB<Heating_views.sql

# D) Update the View for Heat Flux
mysql -u $user -p$password $DB<Heatflux_views.sql

# E) Update the View for El Energy 
mysql -u $user -p$password $DB<Energy_views.sql

#################################################################
#Update the Tables for The  Required for the 
#################################################################

# A) Update Tables flow_minute ,temp_minute,boiler_loop_minute,Anaylze,COPCal
mysql -u $user -p$password $DB1<BasTables.sql
 
# B) Update the Views V0_energy and Energy_Minute
mysql -u $user -p$password $DB1<BasViews.sql
# C) Ipdate the MySQL Functions  calc_cop_test,calc_cop,EQ2_Part 1 
#and EQ2_Part2
mysql -u $user -p$password $D1B<Bascalculations.sql
##################################################################
