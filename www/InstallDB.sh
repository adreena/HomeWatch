#!/bin/bash
# Script File to Install the DB 
# User Name and Password  to insert the  views in the Table DB
echo "#################################################################"
echo "This Script will store all the DB tables,views and functions that are essential for the Smart home project "
echo "#################################################################"
echo "Enter User name for the DB :"
read user
echo "Enter the Password for the DB :"
read password
echo "Enter DB name where air,water,el_energy,heating and heatflux installed in :"
read DB
echo "Enter DB name where Bas tables are instored in :"
read DB1

########################################################################
# Extract DB files
########################################################################

echo "Extracting DB Files"
tar -xvf DB.tar
echo "Extracted Files"

########################################################################
#Install the Login tables   
#########################################################################
#Stevein Add your stuff here //Follow same format as down

########################################################################
#Install the Constants,Equations Tables 
#########################################################################
##Steven add your stuff here 
########################################################################
#Install the Resident and Manager Tables  
#########################################################################
echo "Installing the Resident and Manager tables  (1 of 1)"
mysql -u $user -p$password $DB<ResidentManagerDB.sql

########################################################################
#Install the Views for the  Air,Water,Heating,Heat_flux and  El_Energy
#Each Table should get 4 Views  
#########################################################################

# A) Install the V0 views for all tables 
echo "Installing the V0 Views for all tables (1 of 7)"
mysql -u $user -p$password $DB<v0_views.sql

# B) Install the View for Air 
echo "Installing the Views for Air (2 of 7)"
mysql -u $user -p$password $DB<Air_views.sql

# C)Install the view for Water 
echo "Installing the Views for Water (3 of 7)"
mysql -u $user -p$password $DB<Water_views.sql

# D) Install the View for Heating 
echo "Installing the Views for Heating (4 of 7)"
mysql -u $user -p$password $DB<Heating_views.sql

# E) Install the View for Heat Flux
echo "Installing the Views for Heat Flux (5 of 7)"
mysql -u $user -p$password $DB<Heatflux_views.sql

# F) Install the View for El Energy 
echo "Installing the Views for El Energy (6 of 7)"
mysql -u $user -p$password $DB<Energy_views.sql

# G) Install the Weather Forecast Pre-Historical Data
echo "Installing the Table Weather Forecast with Pre-Historical Data (7 of 7)"
mysql -u $user -p$password $DB<WV.sql
#################################################################
#Install the Tables for The  Required for the Bas tables
#################################################################

# A) Install Tables flow_minute ,temp_minute,boiler_loop_minute,Anaylze,COPCal
echo "Installing the Bas Tables for Flow minute ,Temp Minute ,Boiler minute,Anaylze and CopCa (1 of 3)"
mysql -u $user -p$password $DB1<BasTables.sql
 
# B) Install the Views V0_energy and Energy_Minute
echo "Installing the Views for Energy and Eenergy_Minute (1 of 3)"
mysql -u $user -p$password $DB1<BasViews.sql

# C) Install the MySQL Functions  calc_cop_test,calc_cop,EQ2_Part 1 
#and EQ2_Part2
echo "Installing the MYSQL Functions (3 of 3)"
mysql -u $user -p$password $DB1<Bascalculations.sql
##################################################################
