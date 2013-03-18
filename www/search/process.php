<?php

/* Remember to `composer install` in /www to generate the
 * autoload files! */
require_once __DIR__ . '/../vendor/autoload.php';

/* We want to use the ENGINEER database access class as just 'Engineer'. */
//use www\lib\UASmartHome\Database\Engineer;

require_once __DIR__. '/../lib/UASmartHome/Database/Engineer.php';

/* Aw yea, baby. We just gon' spit out sum JSON. */
header('Content-Type: application/json; charset=utf-8');


//echo json_encode($_GET);
//exit();


$test = true;

//echo var_dump($_GET);


$sensors = array();
$apartments = array();
$message = "";
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

	$bigArray =array(); 

	foreach ($apartments as $apartment) {
        //      $jsonResults += '"'+$apartment+'": {';
                foreach ($sensors as $sensor) {
                        //$jsonResults += '"'+$sensor+'": [';
			//echo var_dump($data);
                        $data = Engineer::db_pull_query($apartment, $sensor, $startdate, $enddate, $period);
			//echo var_dump($data);
			foreach ($data as $date=>$d) {
				//$date = $d['Date'];
				//echo var_dump($date);
				//echo var_dump($d);
				$bigArray[$apartment][$date][$sensor] = $d[$sensor];
			}
                        //$jsonResults += '],';
                }       
                //$jsonResults += '],';

        }
	$bigArray['granularity'] = $period;
	$bigArray['message'] = $message;
        $json = json_encode($bigArray);
        echo $json;
//} 
