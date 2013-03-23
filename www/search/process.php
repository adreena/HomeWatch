<?php

/* Remember to `composer install` in /www to generate the
 * autoload files! */
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__. '/../lib/UASmartHome/Database/Engineer.php';

/* Aw yea, baby. We just gon' spit out sum JSON. */
header('Content-Type: application/json; charset=utf-8');


//TESTING FLAG: SET TO FALSE TO USE DATA FROM SERVER
$test = true;

$sensors = array();
$apartments = array();
$message = "";

//If we are getting data from the front end, this flag will tell us to use that input instead of test data
if (ISSET($_GET['notest'])) {
	$test = false;
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


if ($test) {

	$graphs = array(1 => array("startdate"=>"2012-02-29", "enddate"=>"2012-03-1", "x"=>"CO2", "xtype"=>"sensor", "y" => "Temperature", "ytype"=> "sensor", "period"=>"Yearly", "apartments" => array(1, 2)) );

	$message = "";

	$finances = true;
	$price_per_kwh = 1;
	$price_per_gallon = 1.5;
}

foreach ($graphs as $id=>$graph) {
	$apartments = $graph['apartments'];
	$startdate = $graph['startdate'].":0";
	$enddate = $graph['enddate'].":0";
	$period = $graph['period'];
	$xtype = $graph['xtype'];
	$ytype = $graph['ytype'];
	$x = $graph['x'];
	$y = $graph['y'];
	$xdata = array();
	$ydata = array();

	
	$bigArray['data'][$id]["x-axis"] = [$x];
	$bigArray['data'][$id]["y-axis"] = [$y];

	foreach ($apartments as $apartment) {
		if ($ytype == "sensor") {
			$ydata = Engineer::db_pull_query($apartment, $y, $startdate, $enddate, $period);
		} else if ($ytype == "function") {
			$function = parseFunctionToJson($y, $ydata, $startdate, $enddate, $period, $apartment);
			//TODO: $ydata = ???
		}

		foreach ($ydata as $date=>$yd) {
			if ($yd[$y] == null) {
				$message .= "No data found for graph $id apartment $apartment on the y-axis at time $date";
			}


			$bigArray['data'][$id][$apartment][$date]["y"] = $yd[$y];
			if ($xtype == "time") {
				$xdata[$date]['time'] = $date; //we populate the x-axis with time as we do the y-data to save time and memory
			}
		}

		if ($xtype == "sensor") {
			$xdata = Engineer::db_pull_query($apartment, $x, $startdate, $enddate, $period);
		} else if ($xtype == "function") {
			$function = parseFunctionToJson($y, $ydata, $startdate, $enddate, $period, $apartment);
			//TODO: $xdata = ???
		} else {
			//For "time" we do nothing
		}

		foreach ($xdata as $date=>$xd) {
			if ($xd[$x] == null) {
				$message .= "No data found for graph $id apartment $apartment on the x-axis at time $date";
			}

			$bigArray['data'][$id][$apartment][$date]['x'] = $xd[$x];
		}
		//echo var_dump ($xdata);


		$message = checkAlerts($xdata, $ydata, $x, $y, $message);






	}

}






/*
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


*/
		/*
		*
		*	TODO: Electricity sensors should be converted from wattseconds to kwh
		*
		*/

/*
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


		if ($waterTempDiff) {
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
*/

	if ($message == "") {
		$message = "Success!";
	}

	$bigArray['query']['granularity'] = $period;
	$bigArray['query']['message'] = $message;
        $json = json_encode($bigArray);
        echo $json;








function checkAlerts ($xdata, $ydata, $x, $y, $message) {
	$alerts = array();
	foreach ($ydata as $yd) {
		if ($y == "CO2" && $yd[$y] > 10) {
			$alerts["CO2_high"] = "CO2 levels are extremely high!";
		}
	}
	foreach ($xdata as $xd) {
		if ($x == "CO2" && $xd[$x] > 10) {
			$alerts["CO2_high"] = "CO2 levels are extremely high!";
		}
	}

	foreach ($alerts as $alert) {
		$message .= $alert;
	}
}

/*
 * This returns a json array used for formula/function parsing.
 * Startdate, enddate: YYYY-mm-dd:hh Date strings
 * Apartment: Apartment ID of the apartment queried on
 * Granularity: String, one of Daily, Monthly, Hourly, Weekly, Yearly
 * Function: The function to be parsed
 * Functionname: The name of the function (maybe unnecesary)?
 */
function parseFunctionToJson ($name, $data, $startdate, $enddate, $period, $apartment) {
	$functionArray = array();
	$functionArray["startdate"] => $startdate;
	$functionArray["enddate"] => $enddate;
	$functionArray["apartment"] => $apartment;
	$functionArray["granularity"] => $period;
	$functionArray["function"] => $data;
	$functionArray["functionname"] => $name;
	
	return json_encode($functionArray);
}






?>
