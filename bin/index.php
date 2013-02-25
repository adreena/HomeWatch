<?php
echo "<!DOCTYPE HTML>";
echo "<html><head>";
echo '<link rel="stylesheet" type="text/css" href="css/default.css" /></head>';
echo "<body>";
	   
//TODO: header should be defined in header.php, and header.php should be renamed to something like auth.php


//require 'auth/header.php';  TODO: Commented out for testing, since we have no database working yet.
$role = 'RESIDENT'; //Defining user as resident until we get DB up
//$role = 'MANAGER';
//$role = 'ENGINEER';


$user = null;
if (ISSET($_SESSION['user'])) {
	$user = $_SESSION['user']->get_username();
}  	
echo "<div id='content-div'><h1>";
if ($user) {
	echo "Welcome " . $_SESSION['user']->get_username();
} else { 
	echo "You should not be here just yet."; //TODO: This is testing only, should be removed eventually
}
echo "</h1> </div>";


//TODO: Embed "home page"

if ($role == 'MANAGER') {
	echo '<a href="search/jSearch.php"> Why not try a search?</a>';	
} 

if ($role == 'RESIDENT') {
	echo '<a href="resident_prototype/achievements.php"> Check your score</a>';
}




echo "</body>";
?>
