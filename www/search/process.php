<?php
header('Content-Type: application/json; charset=utf-8');

//require_once __DIR__ . '/../vendor/autoload.php';

$sensors = array();
$apartments = array();
$carbon_dioxide = array();

$SECONDS_PER_HOUR = 60*60;
$HOURS_PER_DAY = 24;
$DAYS_PER_WEEK = 7;
$WEEKS_PER_MONTH = 4;
$HOURLY_VIEW_MAX = 24;
$DAILY_VIEW_MAX = 14;
$WEEKLY_VIEW_MAX = 12;
$MONTHLY_VIEW_MAX = 12;

$date = true; //testing
$startdate = null; //testing
$enddate = null; //testing

//$period = $_GET["period"]; //Granularity of data

$period = "Daily";


//if(ISSET($_GET['startdate'] && $_GET['enddate']) {
if(ISSET($startdate && $enddate) {
	//$startdate = $_GET["startdate"];
	$startdate = "2012-01-01";
	//$enddate = $_GET['enddate'];
	$enddate = "2012-03-03";

	$start = date_create_from_format("d-M-Y", $startdate);
	$end = date_create_from_format("d-M-Y", $enddate);
	
	$diff = abs(strtotime($end) - strtotime($start));
	if ($diff < 0) {
		//ERROR: END DATE IS BEFORE START DATE
	} else {
		$hours = floor ($diff/$SECONDS_PER_HOUR);
		if ($hours > $HOURLY_VIEW_MAX && $period == "Hourly") {
			$period = "Daily";
		}
		$days = floor($diff / ($SECONDS_PER_DAY));
		if ($days > $DAILY_VIEW_MAX && $period == "Daily") {
			$period = "Weekly";
		} 
		$weeks = ceiling($days / $DAYS_PER_WEEK);
		if ($weeks > $WEEKLY_VIEW_MAX && $period == "Weekly") {
			$period = "Monthly";
		} 
		$months = ceiling($weeks / $WEEKS_PER_MONTH);
		if ($weeks > $MONTHLY_VIEW_MAX && $period == "Monthly") {
			$period = "Yearly";
		}
	}
	//TODO: Pull data between startdate and enddate
//} else if (ISSET($_GET['date'])) {	
} else if (ISSET($date)) {
	//$date = $_GET['date'];
	$date = "2012-03-01";

	/*$jsonResults = '"data": {';

	foreach ($apartments as $apartment) {
		$jsonResults += '"'+$apartment+'": {';
		foreach ($sensors as $sensor) {
			$jsonResults += '"'+$sensor+'": [';
			$data = db_pull_query($apartment, $sensor, $date, $period);
			foreach ($data as $d) {
				$jsonResults += '"'+$d+'", ';
			}
			$jsonResults += '],';
		}	
		$jsonResults += '],';

	}

	$jsonResults += '}';
	if (ISSET($startdate) && ($enddate)) {
		$jsonResults += ', "from": "'+$startdate+'", "to": "'+$enddate+'",';
	} else if (ISSET($date)) {
		$jsonResults += ', "date": "'+$date+'",';
	}
	$jsonResults += '"granularity" : "'+$period+'"';*/
	echo json_encode($date);
}

