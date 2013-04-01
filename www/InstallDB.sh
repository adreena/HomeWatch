#!/bin/bash
# Script File to Install the DB 
# User Name and Password  to insert the  views in the Table DB

user=root
password=n342m8wu9
DB1=test
DB2=test2
########################################################################
#Install the Login tables   
#########################################################################
##Stevein Add your stuff here //Follow same format as down

########################################################################
#Install the Constants,Equations Tables 
#########################################################################
##Steven add your stuff here 
########################################################################
#Install the Resident and Manager Tables  
#########################################################################


########################################################################
#Install the Views for the  Air,Water,Heating,Heat_flux and  El_Energy
#Each Table should get 4 Views  
#########################################################################

# A) Install the View for Air 
echo "Installing the Views for Air"
mysql -u $user -p$password $DB<WV.sql

# B)Install the view for Water 
echo "Installing the Views for Water"
mysql -u $user -p$password $DB<WV.sql

# C) Install the View for Heating 
echo "Installing the Views for Heating"
mysql -u $user -p$password $DB<WV.sql

# D) Install the View for Heat Flux
echo "Installing the Views for Heat Flux"
mysql -u $user -p$password $DB<WV.sql

# E) Install the View for El Energy 
echo "Installing the Views for El Energy"
mysql -u $user -p$password $DB<WV.sql

# F) Install the Weather Forecast Pre-Historical Data
echo "Installing the Table Weather Forecast with Pre-Historical Data"
mysql -u $user -p$password $DB<WV.sql
#################################################################
#Install the Tables for The  Required for the 
#################################################################

# A) Install Tables flow_minute ,temp_minute,boiler_loop_minute,Anaylze,COPCal
echo "Installing the Bas Tables for Flow minute ,Temp Minute ,Boiler minute,Anaylze and CopCa"
mysql -u $user -p$password $DB1<WV.sql
 
# B) Install the Views V0_energy and Energy_Minute
echo "Installing the Views for Energy and Eenergy_Minute"
mysql -u $user -p$password $DB1<WV.sq

# C) Install the MySQL Functions  calc_cop_test,calc_cop,EQ2_Part 1 
#and EQ2_Part2
echo "Installing the MYSQL Functions "
mysql -u $user -p$password $DB<WV.sql
##################################################################
