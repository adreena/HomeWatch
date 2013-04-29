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
use \UASmartHome\Database\Engineer2;
use \UASmartHome\Database\Configuration\ConfigurationDB;
use \UASmartHome\EquationParser;

header('Content-Type: application/json; charset=utf-8');

//TESTING FLAG: SET TO FALSE TO USE DATA FROM SERVER
//This is overwritten by a "don't use test" flag from the front-end so this can be left true
$test = true;

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
	$startdate = $graph['startdate']; //Startdate input: yyyy-mm-dd (for non-hourly) or yyyy-mm-dd:h (for hourly)
	$enddate = $graph['enddate']; //Enddate input (same format as startdate)
	$period = $graph['period']; //String literal: one of "Hourly" "Weekly" "Monthly" "Yearly" "Daily"
	$xtype = $graph['xtype']; //String literal: one of "time" "formula" "sensorarray" "alert" "energy" "utility"
	$ytype = $graph['ytype']; //String literal: same format as xtype
	$xaxis = $graph['xaxis']; //String literal: x-axis label. Returned untouched to the frontend
	$yaxis = $graph['yaxis']; //String literal: y-axis label. Returned untouched to the frontend
	$x = $graph['x']; //The dataset to graph. This is the formula, alert, a string "time", an array of sensor names, or the utility/energy graph being called
	$y = $graph['y']; //As above
	$phase = null;
	$xdata = array();
	$ydata = array();



   if ($apartments == null && needsApartment($ytype, $yaxis)) {
		array_push($messages, "No apartments selected. ");
	}
	if ($startdate == null) {
		array_push($messages, "No start date. ");
	}

	if ($enddate == null) {
		array_push($messages, "No end date. ");
	}

	if ($period == null) {
		array_push($messages, "No granularity. ");
	}

	if ($xtype == null) {
		array_push($messages, "X-axis type not specified. ");
	}

	if ($ytype == null) {
		array_push($messages, "Y-axis type not specified. ");
	}

	if ($x == null) {
		array_push($messages, "No x-axis dataset selected. ");
	}

	if ($y == null) {
		array_push($messages, "No y-axis dataset selected. ");
	}

	//Check to make sure the query is over a reasonable data set
	if ($startdate != null && $enddate != null) {
		array_push($messages, calculateRejection($startdate, $enddate, $period));
	}


    if ($period != "Hourly") {
        $startdate .= " ".date("G");
        $enddate .= " ".date("G");
    }


	//If any errors have occurred at this point there's:00 no way we can process the query, so we spit out the query data, any error messages, and die
	if (count($messages) > 0 && $messages[0] != null) {
		$bigArray['granularity'] = $period;
		$bigArray['messages'] = $messages;
        $json = json_encode($bigArray);
        echo $json;
		die;
	}

    //This array is for determining what phase electrical sensors belong to from the name, passed from the front end
	$phaseMapping = array("Mains (Phase A)" => "A", "Bedroom and hot water tank (Phase A)" => "A", "Oven (Phase A) and range hood" => "A", "Microwave and ERV controller" => "A", "Electrical duct heating" => "A", "Kitchen plugs (Phase A) and bathroom lighting" => "A", "Energy recovery ventilation" => "A", "Mains (Phase B)" => "B", "Kitchen plugs (Phase B) and kitchen counter" => "B", "Oven (Phase B)" => "B", "Bathroom" => "B", "Living room and balcony" => "B",  "Hot water tank (Phase B)" => "B", "Refrigerator" => "B");

    $channelMapping = array("Mains (Phase A)" => "Ch1", "Bedroom and hot water tank (Phase A)" => "Ch2", "Oven (Phase A) and range hood" => "AUX1", "Microwave and ERV controller" => "AUX2", "Electrical duct heating" => "AUX3", "Kitchen plugs (Phase A) and bathroom lighting" => "AUX4", "Energy recovery ventilation" => "AUX5", "Mains (Phase B)" => "Ch1", "Kitchen plugs (Phase B) and kitchen counter" => "Ch1", "Oven (Phase B)" => "AUX1", "Bathroom" => "AUX2", "Living room and balcony" => "AUX3",  "Hot water tank (Phase B)" => "AUX4", "Refrigerator" => "AUX5");

    //Xaxis is always a single variable, but multiple variables might be plotted along the y-axis. Here we set the x-axis label to what we received and the y-axis label to the last array name
	$bigArray["xaxis"] = $xaxis;
	$bigArray["yaxis"] = is_array($yaxis) ? end($yaxis) : $yaxis;
    
    if ($xtype == "sensorarray") {
        $x = end($x);
    }

    if (!needsApartment($ytype, $yaxis))
		$apartments[0] = -1;
    //This code block pulls data from a sensor or array of sensors and adds it to the JSON array
	foreach ($apartments as $apartment) {
		if ($ytype == "sensorarray") {

			foreach ($y as $sensor) {

				if (ISSET($phaseMapping[$sensor])) {
					$phase = $phaseMapping[$sensor];
				} else {
					$phase = null;
				}

                if (ISSET($channelMapping[$sensor])) {
					$sensor = $channelMapping[$sensor];
				} 

				$ydata = Engineer::db_pull_query($apartment, $sensor, $startdate, $enddate, $period, $phase);

				foreach ($ydata as $date=>$yd) {

					if ($yd[$sensor] == null) {
						array_push ($messages, "No data found for apartment $apartment on the y-axis at time $date");
					}
                    if ($phase == null) {
					    $bigArray['values'][$apartment][$date][$sensor]["y"] = $yd[$sensor];
    					if ($xtype == "time") {
    						$bigArray['values'][$apartment][$date][$sensor]["x"] = $date; //we populate the x-axis with time as we do the y-data to save time and memory
    					}
                    } else {
                        $bigArray['values'][$apartment][$date][$sensor.$phase]["y"] = $yd[$sensor];
    					if ($xtype == "time") {
    						$bigArray['values'][$apartment][$date][$sensor.$phase]["x"] = $date; //we populate the x-axis with time as we do the y-data to save time and memory
    					}
                    }
				}
			}

        //If the frontend has requested a formula then we fetch the formula and process that, then add it to the JSON array
		} else if ($ytype == "formula") {

            //get the actual function body from the function name
            $ydata = ConfigurationDB::fetchFunction($yaxis);

            // if fetchFunction returns false, this name doesn't exist in db
            if (!$ydata)
                array_push($messages, "No equation with the name $yaxis found");
            else {
                $ydata = $ydata["Value"];
                $function = parseFormulaToJson($ydata, $startdate, $enddate, $period, $apartment);

                try {
                    $ydata = EquationParser::getData($function);
                } catch (\Exception $e) {
                    $ydata = array();
                    $messages[] = $e->getMessage();
                }

                foreach($ydata as $date=>$value) {
                    $bigArray['values'][$apartment][$date][$yaxis]["x"] = $date;
                    $bigArray['values'][$apartment][$date][$yaxis]["y"] = $value;
                }

            }
        //Alerts are handled here
		} else if ($ytype == "alert") {
            $ydata = ConfigurationDB::fetchAlert($yaxis);

            // if fetchAlert returns false, this name doesn't exist in db
            if (!$ydata)
                array_push($messages, "No alert with the name $yaxis found");
            else {
                $ydata = $ydata["Value"];
                $function = parseFormulaToJson($ydata, $startdate, $enddate, "Hourly", $apartment);

                try {
                    $ydata = Alerts::getAlerts($function);
                } catch (\DomainException $e) {
                    $ydata = array();
                    $messages[] = $e->getMessage();
                }

                foreach($ydata as $date=>$value) {
                    $bigArray['values'][$apartment][$date][$yaxis]["x"] = $date;
                    $bigArray['values'][$apartment][$date][$yaxis]["y"] = $value;
                }
            }
        //Energy data has to be handled differently
		} else if ($ytype == "energy") {

            $dateFormat = 'Y-m-d G';

            $d1 = date_create_from_Format($dateFormat, $startdate);
            $d2 = date_create_from_Format($dateFormat, $enddate);

            $ydata = Engineer2::getEnergyColumnData($d1, $d2,$y[0], $period);
            

            foreach ($ydata as $date=>$value) {
                $bigArray['values'][$apartment][$date][$yaxis]["x"] = $date;
                $bigArray['values'][$apartment][$date][$yaxis]["y"] = $value;
            }
        //This is where we process the cost of energy data
		} else if ($ytype == "utility") {

            $function = parseFormulaToJson($ydata, $startdate, $enddate, $period, $apartment, $yaxis);
            $ydata = EquationParser::getUtilityCosts($function);
            foreach($ydata as $date=>$value) {
                $bigArray['values'][$apartment][$date]["$yaxis Cost"]["y"] = $value;
                $bigArray['values'][$apartment][$date]["$yaxis Cost"]["x"] = $date;
            }
        }



		if ($xtype == "sensorarray") {
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



    if (!isset($bigArray['values'])) {
        $messages[] = 'No data found for the given date range.';
    }




	if (count($messages) == 0) {
		array_push($messages, "Success!");
	}

	$bigArray['granularity'] = $period;
	$bigArray['messages'] = $messages;

	$unit = "";

	if ($ytype == 'energy') //calculated from flow and temp. difference
		$unit = "(KJ)";
	elseif (isset($channelMapping[$yaxis]) || isBasEnergy($yaxis)) //from energy monitors
		$unit = "(J)";
	elseif ($yaxis == "Outside_Temperature")
		$unit = "(°C)";

	$bigArray['unit'] = $unit;

    $json = json_encode($bigArray);
    echo $json;








/*
 * This returns a json array used for formula/function parsing.
 * Startdate, enddate: YYYY-mm-dd:hh Date strings
 * Apartment: Apartment ID of the apartment queried on
 * Granularity: String, one of Daily, Monthly, Hourly, Weekly, Yearly
 * Data: The function to be parsed
 * Name: The name of the function (maybe unnecesary)?
 */
function parseFormulaToJson ($data, $startdate, $enddate, $period, $apartment, $type = null) {
	$functionArray = array();
	$functionArray["startdate"] = $startdate;
	$functionArray["enddate"] = $enddate;
	$functionArray["apartment"] = $apartment;
	$functionArray["granularity"] = $period;
	$functionArray["function"] = $data;
    $functionArray["type"] = $type;

	return json_encode($functionArray);
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


    if ($startdate == null || $enddate == null) {
        return "No start date or end date selected, unable to graph.";
    }

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

function isBasEnergy($name) {
	$dbvars = EquationParser::getVariables();
	return isset($dbvars["bas_energy_$name"]);
}

function needsApartment($ytype, $yaxis) {
	if ($ytype == "energy" || isBasEnergy($yaxis) || $yaxis == "tempdifference" || ($ytype == "utility" && $yaxis == "HP_Electricity") || $yaxis == "Total_HP")
		return 0;
	return 1;
}

