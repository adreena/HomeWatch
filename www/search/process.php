<?php

/* Remember to `composer install` in /www to generate the
 * autoload files! */
require_once __DIR__ . '/../vendor/autoload.php';

/* We want to use the ENGINEER database access class as just 'Engineer'. */
//use www\lib\UASmartHome\Database\Engineer;

require_once __DIR__. '/../lib/UASmartHome/Database/Engineer.php';

/* Aw yea, baby. We just gon' spit out sum JSON. */
header('Content-Type: application/json; charset=utf-8');


//TESTING FLAG: SET TO FALSE TO USE DATA FROM SERVER
$test = true;

$sensors = array();
$apartments = array();
$message = "";

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

if (ISSET($_GET['finances'])) {
	$finances = true;
} else {
	$finances = false;
}

if (ISSET($_GET['phaseAsensors'])) {
	$phaseASensors = $_GET['phaseAsensors'];
} else {
	$phaseASensors = array();
}

if (ISSET($_GET['phaseBsensors'])) {
	$phaseASensors = $_GET['phaseBsensors'];
} else {
	$phaseBSensors = array();
}

if (ISSET($_GET['phaseAsum'])) {
	$phaseASum = true;
} else {
	$phaseASum = false;
}

if (ISSET($_GET['phaseBsum'])) {
	$phaseBSum = true;
} else {
	$phaseBSum = false;
}

if (ISSET($_GET['elecSum'])) {
	$elecSum = true;
} else {
	$elecSum = false;
}

if (ISSET($_GET['waterTempDiff'])) {
	$waterTempDiff = true;
} else {
	$waterTempDiff = false;
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
$W_PER_KW = 1000;
//TODO: COST PER GALLON OF WATER


if ($test) {
	$apartments = array(1, 2);
	$sensors = array("Temperature", "CO2", "Total_Water", "Total_Energy");
	//$sensors = array("Temperature", "CO2");
	//$phaseASensors = array("AUX1");
	//$phaseBSensors = array("AUX1", "AUX2", "AUX3", "AUX4", "AUX5");
	$period = "Daily";
	$message = "";
	$startdate = "2012-02-29";
	$enddate = "2012-03-01";
	
	$finances = true;
	$price_per_kwh = 1;
	$price_per_gallon = 1.5;
}

	$startdate .= ":0";
	$enddate .= ":0";
	
	$diff = abs(strtotime($enddate) - strtotime($startdate));
	if ($diff < 0) {
		//ERROR: END DATE IS BEFORE START DATE
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
                foreach ($sensors as $sensor) {
                        $data = Engineer::db_pull_query($apartment, $sensor, $startdate, $enddate, $period);
			foreach ($data as $date=>$d) {
				$cost = -1;
				if ($d[$sensor] == null) {
					$message .= "No data found for Apartment $apartment on date $date for sensor $sensor. ";
				}
					$bigArray['data'][$apartment][$date][$sensor] = $d[$sensor];

				if ($finances && $d[$sensor] != null) {
					if ($sensor == "Hot_Water" || $sensor == "Total_Water") {
						$cost = $d[$sensor] * $price_per_gallon;
					} else if ($sensor == "Total_Energy") {
						$cost = $d[$sensor] / $W_PER_KW * $price_per_kwh; //Total Energy is in watt hours
					} 
					if ($cost >= 0)	{
						$bigArray['finances'][$apartment][$date][$sensor] = round($cost, 2);
					}
				} 
			}
                }       



		/*
		*
		*	TODO: Electricity sensors should be converted from wattseconds to kwh
		*
		*/


		foreach ($phaseASensors as $sensor) {
        		$data = Engineer::db_pull_query($apartment, $sensor, $startdate, $enddate, $period, "A");
			foreach ($data as $date=>$d) {
				$cost = -1;
				if ($d[$sensor] == null) {
					$message .= "No data found for Apartment $apartment on date $date for sensor $sensor. ";
				} 
				$bigArray['data'][$apartment][$date]["PhaseA"][$sensor] = $d[$sensor];
				
	
				if ($finances && $d[$sensor] != null) {
					$cost = $d[$sensor] / $SECONDS_PER_HOUR / $W_PER_KW * $price_per_kwh; // These are in watt-seconds
					if ($cost >= 0)	{
						$bigArray['finances'][$apartment][$date][$sensor] = round($cost, 2);
					}
				} 
			}
		}       

		foreach ($phaseBSensors as $sensor) {
        		$data = Engineer::db_pull_query($apartment, $sensor, $startdate, $enddate, $period, "B");
			foreach ($data as $date=>$d) {
				$cost = -1;
				if ($d[$sensor] == null) {
					$message .= "No data found for Apartment $apartment on date $date for sensor $sensor. ";
				}
				$bigArray['data'][$apartment][$date]["PhaseB"][$sensor] = $d[$sensor];


				if ($finances && $d[$sensor] != null) {
					$cost = $d[$sensor] / $SECONDS_PER_HOUR / $W_PER_KW * $price_per_kwh; // These are in watt-seconds
					if ($cost >= 0)	{
						$bigArray['finances'][$apartment][$date][$sensor] = round($cost, 2);
					}
				} 
			}
		}     


		if ($waterTempSum) {
			$data = Engineer::db_get_data_placeholder();
			foreach ($data as $date=>$d) {
				$bigArray['data'][$apartment][$date]['waterTempDiff'] = $d[0];
			}
		}

		if ($elecSum) {
			$data = Engineer::db_get_data_placeholder();
			foreach ($data as $date=>$d) {
				$bigArray['data'][$apartment][$date]['elecDiff'] = $d[0];
			}
		}

		if ($phaseASum) {
			$data = Engineer::db_get_data_placeholder();
			foreach ($data as $date=>$d) {
				$bigArray['data'][$apartment][$date]["PhaseA"]['Sum'] = $d[0];
			}
		}

		if ($phaseBSum) {
			$data = Engineer::db_get_data_placeholder();
			foreach ($data as $date=>$d) {
				$bigArray['data'][$apartment][$date]["PhaseB"]['Sum'] = $d[0];
			}
		}

                
        }


	if ($message == "") {
		$message = "Success!";
	}
	$bigArray['query']['granularity'] = $period;
	$bigArray['query']['message'] = $message;
        $json = json_encode($bigArray);
        echo $json;
//} 
