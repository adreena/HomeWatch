<?php namespace UASmartHome;

/*
 * @author Devin Hanchar
 * Processes all queries from jSearch.php and returns the data as a multidimensional JSON array
 */

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__. '/../lib/UASmartHome/EquationParser.php';
require_once __DIR__. '/../lib/UASmartHome/Alerts.php';
require_once __DIR__ . '/../lib/UASmartHome/Auth/Firewall.php';

use \UASmartHome\Auth\Firewall;
Firewall::instance()->restrictAccess(Firewall::ROLE_ENGINEER, Firewall::ROLE_MANAGER);

use \UASmartHome\Database\Engineer;
use \UASmartHome\Database\Configuration\ConfigurationDB;

header('Content-Type: application/json; charset=utf-8');

//TESTING FLAG: SET TO FALSE TO USE DATA FROM SERVER
$test = true;
//$test = false;

$sensors = array();
$apartments = array();
$messages = array();

//If we are getting data from the front end, this flag will tell us to use that input instead of test data
if (ISSET($_GET['notest'])) {
	$test = false;
} 

//We need to grab all of this data from the request header to correctly handle it
if (ISSET($_GET['graph'])) {
    /* ALL of the information is just passed as a JSON object -- convert it 
     * into an associative array. */
	$graph = json_decode($_GET['graph'], true);
} else {
	array_push($messages, "No graph data received\n");
}

//TODO: We might need to handle this differently
if (ISSET($_GET['finances'])) {
	$finances = true;
} else {
	$finances = false;
}

if ($test == false && count($messages) > 0 ) {
	echo json_encode(array("messages"=>$messages));
	exit();
}


$W_PER_KW = 1000;

//Testing data for when not hooked up to the front end
if ($test) {
	$graph = array("startdate"=>"2012-03-01", "enddate"=>"2012-03-02", "xaxis" => "Time", "x"=>"time", "xtype"=>"time", "yaxis" => "Water_Usage", "y" => array("Total_Water", "Hot_Water"), "ytype"=> "sensorarray", "period"=>"Daily", "apartments" => array(1, 2)) ;
	$finances = true;
	$price_per_kwh = 1;
	$price_per_gallon = 1.5;
}

//This loop goes through each graph request from the front end, grabs the data, and sends it back to the front end in the form of a json array

	//parsing out the components of the graph
	$apartments = $graph['apartments'];
	$startdate = $graph['startdate'];
	$enddate = $graph['enddate'];
	$period = $graph['period'];
	$xtype = $graph['xtype'];
	$ytype = $graph['ytype'];
	$xaxis = $graph['xaxis'];
	$yaxis = $graph['yaxis'];
	$x = $graph['x'];
	$y = $graph['y'];
	$phase = null;
	$xdata = array();
	$ydata = array();

	$error = null;

	if ($apartments == null) {
		$error .= "No apartments selected. ";
	}	
	if ($startdate == null) {
		$error .= "No start date. ";
	}

	if ($enddate == null) {
		$error .= "No end date. ";
	}

	if ($period == null) {
		$error .= "No granularity. ";
	}

	if ($xtype == null) {
		$error .= "X-axis type not specified. ";
	}

	if ($ytype == null) {
		$error .= "Y-axis type not specified. ";
	}
	
	if ($x == null) {
		$error .= "No x-axis dataset selected. ";
	}

	if ($y == null) {
		$error .= "No y-axis dataset selected. ";
	}

	/*
	 *  Is it necessary to throw an error if the axis labels are not present?
         *
         
	if ($xaxis == null) {
		$error .= "No y-axis dataset selected. ";
	}

	if ($yaxis == null) {
		$error .= "No y-axis dataset selected. ";
	}*/


	//Check to make sure the query is over a reasonable data set
	if ($startdate != null && $enddate != null) {
		$error .= calculateRejection($startdate, $enddate, $period);	
	}

	//If any errors have occurred at this point there's no way we can process the query, so we spit out the query data, any error messages, and die
	if ($error != null) {
		array_push($messages, $error);
		$bigArray['granularity'] = $period;
		$bigArray['messages'] = $messages;
        	$json = json_encode($bigArray);
        	echo $json;
		die;
	}

	$phaseMapping = array("Mains (Phase A)" => "A", "Bedroom and hot water tank (Phase A)" => "A", "Oven (Phase A) and range hood" => "A", "Microwave and ERV controller" => "A", "Electrical duct heating" => "A", "Kitchen plugs (Phase A) and bathroom lighting" => "A", "Energy recovery ventilation" => "A", "Mains (Phase B)" => "B", "Kitchen plugs (Phase B) and kitchen counter" => "B", "Oven (Phase B)" => "B", "Bathroom" => "B", "Living room and balcony" => "B",  "Hot water tank (Phase B)" => "B", "Refrigerator" => "B");

	
	$bigArray["xaxis"] = $xaxis;
	$bigArray["yaxis"] = end($yaxis);

	foreach ($apartments as $apartment) {
		if ($ytype == "sensorarray") {
			foreach ($y as $sensor) {

				if (ISSET($phaseMapping[$sensor])) {
					$phase = $phaseMapping[$sensor];
				} else {
					$phase = null;
				}

				$ydata = Engineer::db_pull_query($apartment, $sensor, $startdate, $enddate, $period, $phase);

				foreach ($ydata as $date=>$yd) {

					if ($yd[$sensor] == null) {
						array_push ($messages, "No data found for apartment $apartment on the y-axis at time $date");
					}

					$bigArray['values'][$apartment][$date][$sensor]["y"] = $yd[$sensor];
					if ($xtype == "time") {
						$bigArray['values'][$apartment][$date][$sensor]["x"] = $date; //we populate the x-axis with time as we do the y-data to save time and memory
					}
				}
			}

		} else if ($ytype == "formula") {
            //get the actual function body from the function name
            $ydata = ConfigurationDB::fetchFunction($yaxis);

            // if fetchFunction returns false, this name doesn't exist in db
            if (!$ydata)
                array_push($messages, "No equation with the name $yaxis found");
            else {
                $ydata = $ydata["Value"];
                $function = parseFormulaToJson($ydata, $startdate, $enddate, $period, $apartment);

                $ydata = EquationParser::getData($function);
                foreach($ydata as $date=>$value) {
                    $bigArray['values'][$apartment][$date][$yaxis]["x"] = $date;
                    $bigArray['values'][$apartment][$date][$yaxis]["y"] = $value;
                }
                //var_dump($bigArray);
            }
		}

		
	
		if ($xtype == "sensorarray") {
			$x = end($x);
			if (ISSET($phaseMapping[$x])) {
				$phase = $phaseMapping[$x];
			} else {
				$phase = null;
			}

			$xdata = Engineer::db_pull_query($apartment, $x, $startdate, $enddate, $period);
			foreach ($xdata as $date=>$xd) {
				if ($xd[$x] == null) {
					array_push($messages, "No data found for apartment $apartment on the x-axis at time $date");
				}

				foreach ($bigArray['values'][$apartment][$date] as $sensor=>$sensordata) {
					//pushes the x data into each set of y values, because graphs need x,y pairs
					$sensordata["x"] = $xd[$x];
					$bigArray['values'][$apartment][$date][$sensor] = $sensordata;
				}
			}
			
		} else if ($xtype == "formula") {
			$function = parseFormulaToJson($xdata, $startdate, $enddate, $period, $apartment, $phase);
			$xdata = EquationParser::getData($function);
		} else {
			//The xdata array for time was actually built when running y
		}

		//$alerts = checkAlerts($startdate, $enddate, $x, $y, $apartment);
		//echo var_dump($alerts);
		//$messages = array_merge($messages, $alerts);

	}





	


	if (count($messages) == 0) {
		array_push($messages, "Success!");
	}

	$bigArray['granularity'] = $period;
	$bigArray['messages'] = $messages;
        $json = json_encode($bigArray);
        echo $json;





/*
 * This function determines what alerts should be shown alongside the graph.
 * Alerts could be things like high CO2, energy usage, or other.
 * Theoretically there is a plan for these to be specified by the user 
 * but for now they are hard-coded in.
 * As input, this method takes the x and y data being graphed,
 * the name of these data sets, the current apartment, and the error-logging 
 * variable "message".
 * The variable "message" passed as an argument is returned by this method,
 * with any additional alerts identified by the system appended to it.
 */


function checkAlerts ($startdate, $enddate, $x, $yarray, $apartment) {

	$listOfAlerts = array("CO2" => '$air_co2$ > 1000');

	$triggeredAlerts = array();

	array_push ($yarray, $x);


	foreach ($yarray as $y) {
		if ($y == "CO2") {
			$alertJson = parseAlertToJson($listOfAlerts[$y], $startdate, $enddate, $apartment);
	
			//echo var_dump($alertJson);

			$alerts = Alerts::getAlerts($alertJson);
				foreach ($alerts as $alertTime => $alertValue) {
					array_push($triggeredAlerts, "Apartment $apartment triggered alert $listOfAlerts[$y] at time $alertTime with value $alertValue");
				}

			//array_push($triggeredAlerts, Alerts::getAlerts($alertJson));
		}
	}



	return $triggeredAlerts;
}

/*
 * This returns a json array used for formula/function parsing.
 * Startdate, enddate: YYYY-mm-dd:hh Date strings
 * Apartment: Apartment ID of the apartment queried on
 * Granularity: String, one of Daily, Monthly, Hourly, Weekly, Yearly
 * Data: The function to be parsed
 * Name: The name of the function (maybe unnecesary)?
 */
function parseFormulaToJson ($data, $startdate, $enddate, $period, $apartment) {
	$functionArray = array();
	$functionArray["startdate"] = $startdate;
	$functionArray["enddate"] = $enddate;
	$functionArray["apartment"] = $apartment;
	$functionArray["granularity"] = $period;
	$functionArray["function"] = $data;
	//$functionArray["functionname"] = $name;
	
	return json_encode($functionArray);
}

function parseAlertToJson ($alertString, $startdate, $enddate, $apartment) {
	$alertArray = array();
	$alertArray["startdate"] = $startdate;
	$alertArray["enddate"] = $enddate;
	$alertArray["alert"] = $alertString;
	$alertArray["apartment"] = $apartment;

	return json_encode($alertArray);
}


/*
 * This function calculates whether a query should be rejected or not based on the size of the data set requested.
 * The [period]_VIEW_MAX variables can be configured to allow smaller or larger data sets.
 * Returns "Null" if the specified timeframe and granularity are allowed based on the _VIEW_MAX variable
 * Returns an error message and causes the system to return if the requested data set exceeds the _VIEW_MAX variable.
 */


function calculateRejection($startdate, $enddate, $period) {

$SECONDS_PER_HOUR = 3600;
$HOURS_PER_DAY = 24;
$DAYS_PER_WEEK = 7;
$WEEKS_PER_MONTH = 4;
$HOURLY_VIEW_MAX = 168;
$DAILY_VIEW_MAX = 155;
$WEEKLY_VIEW_MAX = 104;
$MONTHLY_VIEW_MAX = 120;


	//echo var_dump($startdate);	
	
	$error = null;
	if ($period == "Hourly") {
		$startdate = date_create_from_Format('Y-m-d G', $startdate);
		$enddate = date_create_from_Format('Y-m-d G', $enddate);
	}
	else {
		$startdate = date_create_from_Format('Y-m-d', $startdate);
		$enddate = date_create_from_Format('Y-m-d', $enddate);
	}

	$diff = ($enddate->format("U") - $startdate->format("U"));

	//echo var_dump($diff);

	if ($period == "Hourly") {
		$hours = ceil($diff/$SECONDS_PER_HOUR);
		if ($hours > $HOURLY_VIEW_MAX) {
			$error = "Time period of $hours hours is too large for hourly view.";
		}
	} else if ($period == "Daily") {
		$days = ceil($diff/$SECONDS_PER_HOUR/$HOURS_PER_DAY);
		if ($days > $DAILY_VIEW_MAX) {
			$error = "Time period of $days days is too large for daily view.";
		}
	} else if ($period == "Weekly") {
		$weeks = ceil($diff/$SECONDS_PER_HOUR/$HOURS_PER_DAY/$DAYS_PER_WEEK);
		if ($weeks > $WEEKLY_VIEW_MAX) {
			$error = "Time period of $weeks weeks is too large for weekly view.";
		}
	} else if ($period == "Monthly") {
		$months = ceil($diff/$SECONDS_PER_HOUR/$HOURS_PER_DAY/$DAYS_PER_WEEK/$WEEKS_PER_MONTH);
		if ($months > $MONTHLY_VIEW_MAX) {
			$error = "Time period of $months months is too large for monthly view.";
		}
	} 

	return $error;

}




?>
