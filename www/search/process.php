<?php

/* Remember to `composer install` in /www to generate the
 * autoload files! */
require_once __DIR__ . '/../vendor/autoload.php';

/* We want to use the ENGINEER database access class as just 'Engineer'. */
//use www\lib\UASmartHome\Database\Engineer;

require_once __DIR__. '/../lib/UASmartHome/Database/Engineer.php';

/* Aw yea, baby. We just gon' spit out sum JSON. */
header('Content-Type: application/json; charset=utf-8');


$test = true;


$sensors = array();
$apartments = array();
$message = "";

// Boolean: Whether the date should be converted to UNIX time * 1000
// Should probably be specified in query string, but whatever.
$UNIX_time = true;

//$period = "";
//$startdate = "";
//$enddate = "";

if (ISSET($_GET['sensors'])) {
	$sensors = $_GET['sensors'];
} else {
	$message .= "No sensors selected.\n";
}

if (ISSET($_GET['apartments'])) {
	$apartments = $_GET['apartments'];
} else {
	$message .= "No apartments selected\n";
}

if (ISSET($_GET['startdate'])) {
	$startdate = $_GET['startdate'];
} else {
	$message .= "No start date selected \n";
}

if (ISSET($_GET['enddate'])) {
	$enddate = $_GET['enddate'];
} else {
	$message .= "No end date selected\n";
}

if (ISSET($_GET['period'])) {
	$period = $_GET['period'];
} else {
	$message .= "No granularity selected\n";
}

//echo $message;

if ($test == false && $message > "" ) {
	echo json_encode(array("message"=>$message));
	exit();
}

$SECONDS_PER_HOUR = 60*60;
$HOURS_PER_DAY = 24;
$DAYS_PER_WEEK = 7;
$WEEKS_PER_MONTH = 4;
$HOURLY_VIEW_MAX = 24;
$DAILY_VIEW_MAX = 14;
$WEEKLY_VIEW_MAX = 12;
$MONTHLY_VIEW_MAX = 12;

//$onedate = false;
//$date = true; //testing
//$startdate = null; //testing
//$enddate = null; //testing

//$period = $_GET["period"]; //Granularity of data
//$apartments = $_GET['apartments'];
//$sensors = $_GET['sensors'];



if ($test) {
	$apartments = array(1, 2);
	$sensors = array("Temperature", "CO2");
//$data = array("Temperature", "CO2");
	$period = "Daily";
	$message = "Success!";
	$startdate = "2012-02-29";
	$enddate = "2012-03-01";
}

//if(ISSET($_GET['startdate'] && $_GET['enddate']) {
//if(!$onedate) {
	//$startdate = $_GET["startdate"];
	//$startdate = "2012-02-29";
	//$enddate = $_GET['enddate'];
	//$enddate = "2012-03-01";

	//$start = date_create_from_format("d-M-Y", $startdate);
	//$end = date_create_from_format("d-M-Y", $enddate);

	$diff = abs(strtotime($enddate) - strtotime($startdate));
	if ($diff < 0) {
		//ERROR: END DATE IS BEFORE START DATE
        // put the PHP HTTP status here!
        exit();
	} else {
		$hours = floor ($diff/$SECONDS_PER_HOUR);
		if ($hours > $HOURLY_VIEW_MAX && $period == "Hourly") {
			$period = "Daily";
			$message = "Too much data to display hourly, switching to daily view";
		}
		$days = floor($hours / ($HOURS_PER_DAY));
		if ($days > $DAILY_VIEW_MAX && $period == "Daily") {
			$period = "Weekly";
			$message = "Too much data for hourly view, switching to weekly view";
		}
		$weeks = ceil($days / $DAYS_PER_WEEK);
		if ($weeks > $WEEKLY_VIEW_MAX && $period == "Weekly") {
			$period = "Monthly";
			$message = "Too much data for weekly view, switching to monthly view";
		}
		$months = ceil($weeks / $WEEKS_PER_MONTH);
		if ($weeks > $MONTHLY_VIEW_MAX && $period == "Monthly") {
			$period = "Yearly";
			$message = "Too much data for monthly view, switching to yearly view";
		}
	}

// Create the big array that will contain the result of the query.
$bigArray = array();

// For each 
foreach ($apartments as $apartment) {
    foreach ($sensors as $sensor) {

        $data = Engineer::db_pull_query($apartment, $sensor, $startdate, $enddate, $period);

        foreach ($data as $date => $d) {
            // I've always wanted to have a date with a eunich
            if ($UNIX_time) {
                // Brent needs the date as a UNIX timestamp.
                $date = strtotime($date) * 1000;
            }

            $bigArray[$apartment][$date][$sensor] = $d[$sensor];
        }
    }
}


$metaData['granularity'] = $period;
$metaData['message'] = $message;

$resultArray = array(
    'query' => $metaData,
    'data' => $bigArray
);


echo json_encode($resultArray);

