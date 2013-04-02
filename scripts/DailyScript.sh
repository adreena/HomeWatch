#!/bin/bash
# Script File to Updates the DB 
# The  User Name and Password need to be written in the script to update DB tables automatically 
# This Script will run daily at 1 am in the morning to update the tables and views.

user=root
password=n342m8wu9
DB=TEST1
DB1=TEST2


#######################################################################
tar -xvf DBDaily.tar
#######################################################################
########################################################################
#Updates the Views for the  Air,Water,Heating,Heat_flux and  El_Energy
#Each Table should get 4 Views  
#########################################################################

# A) Updates the V0 views for all tables 
mysql -u $user -p$password $DB<v0_views.sql

# B) Updates the View for Air 
mysql -u $user -p$password $DB<Air_views.sql

# C)Updates the view for Water 
mysql -u $user -p$password $DB<Water_views.sql

# D) Updates the View for Heating 
mysql -u $user -p$password $DB<Heating_views.sql

# E) Updates the View for Heat Flux
mysql -u $user -p$password $DB<Heatflux_views.sql

# F) Updates the View for El Energy 
mysql -u $user -p$password $DB<Energy_views.sql

# G) Updates the Weather Forecast Pre-Historical Data
mysql -u $user -p$password $DB<WV.sql
#################################################################
#Updates the Tables for The  Required for the Bas tables
#################################################################

# A) Updates Tables flow_minute ,temp_minute,boiler_loop_minute,Anaylze,COPCal
mysql -u $user -p$password $DB1<BasTables.sql
 
# B) Updates the Views V0_energy and Energy_Minute
mysql -u $user -p$password $DB1<BasViews.sql

# C) Updates the MySQL Functions  calc_cop_test,calc_cop,EQ2_Part 1 
#and EQ2_Part2
mysql -u $user -p$password $DB1<Bascalculations.sql
##################################################################
