<?php namespace UASmartHome;

/*
 * @author Devin Hanchar
 * Processes all queries from jSearch.php and returns the data as a multidimensional JSON array
 */
date_default_timezone_set("America/Edmonton");

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
$test = false;

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
	$xtype = 'time';
	$ytypes = $graph['ytype']; //String literal: one of "time" "formula" "sensorarray" "alert" "energy" "utility"
	$xaxis = 'Time';
	$yaxises = array(); 
	if (isset($graph['yaxis']))
		$yaxises = $graph['yaxis']; //Array of strings: y-axis labels
	$x = 'time'; 
	$y = $graph['y']; //The dataset to graph. This is the formula, alert, a string "time", an array of sensor names, or the utility/energy graph being called
	$aptMultiple = $graph['aptMultiple']; //Whether apartments should be plotted seperately, averaged or summed
	$phase = null;
	$xdata = array();
	$ydata = array();
	$validPeriods = array_flip(array("Daily", "Monthly", "Hourly", "Weekly", "Yearly"));
	$validAptMultiple = array_flip(array("seperate", "avg", "sum"));
	
	
//TODO early check for valid period and valid apt multiple
	if ($startdate == null) {
		array_push($messages, "No start date. ");
	}

	if ($enddate == null) {
		array_push($messages, "No end date. ");
	}

	if ($period == null) {
		array_push($messages, "No granularity (period) specified. ");
	}
	
	if ($period != null && !isset($validPeriods[$period])) {
		array_push($messages, "Invalid granularity (period) specified.");
	}
	
	if ($period == "Hourly") {
		$dateFmtIn = "Y-m-d G";
		$dateFmtOut = "%Y-%m-%d %H";
	}
	else {
		$dateFmtIn = "Y-m-d";
		$dateFmtOut = "%Y-%m-%d";
	}
	
	if (date_create_from_Format($dateFmtIn, $startdate) === false)
		$startdate = strftime($dateFmtOut, strtotime($startdate));
	if (date_create_from_Format($dateFmtIn, $enddate) === false)
		$enddate = strftime($dateFmtOut, strtotime($enddate));

	if ($xtype == null) {
		array_push($messages, "X-axis type not specified. ");
	}

	if ($ytypes == null || !isset($ytypes[0])) {
		array_push($messages, "Y-axis type not specified. ");
	}

	if ($x == null) {
		array_push($messages, "No x-axis dataset selected. ");
	}

	if ($y == null) {
		array_push($messages, "No y-axis dataset selected. ");
	}
	
	if ($aptMultiple == null && count($apartments) > 1) 
		array_push($messages, "Multiple apartment handling type not selected. ");
	
	if ($aptMultiple != null && !isset($validAptMultiple[$aptMultiple]))
		array_push($messages, "Invalid aptMultiple option specified.");
	
	for ($i = 0; $i < count($y); $i++) {
		if (!isset($yaxises[$i]))
			$yaxises[$i] = ucwords($y[$i]);
	}
	
	if ($apartments == null) {
		for ($i = 0; $i < count($ytypes); $i++) {
			if (needsApartment($ytypes[$i], $y[$i], $yaxises[$i])) {
				array_push($messages, "No apartments selected. ");
				break;
			}
		}
		$apartments = array(-1);
	}

	$bigArray = array();
	bailIfErrors($messages, $period, $bigArray);
	
	//Check to make sure the query is over a reasonable data set
	if ($startdate != null && $enddate != null) {
		array_push($messages, calculateRejection($startdate, $enddate, $period));
	}


    if ($period != "Hourly") {
        $startdate .= " ".date("G");
        $enddate .= " ".date("G");
    }

	bailIfErrors($messages, $period, $bigArray);

    //This array is for determining what phase electrical sensors belong to from the name, passed from the front end
	$phaseMapping = array("Mains (Phase A)" => "A", "Bedroom and hot water tank (Phase A)" => "A", "Oven (Phase A) and range hood" => "A", "Microwave" => "A", "Electrical duct heating" => "A", "Kitchen plugs (Phase A) and bathroom lighting" => "A", "Energy recovery ventilation" => "A", "Mains (Phase B)" => "B", "Kitchen plugs (Phase B) and kitchen counter" => "B", "Oven (Phase B)" => "B", "Bathroom" => "B", "Living room and balcony" => "B",  "Hot water tank (Phase B)" => "B", "Refrigerator" => "B");

    $channelMapping = array("Mains (Phase A)" => "Ch1", "Bedroom and hot water tank (Phase A)" => "Ch2", "Oven (Phase A) and range hood" => "AUX1", "Microwave" => "AUX2", "Electrical duct heating" => "AUX3", "Kitchen plugs (Phase A) and bathroom lighting" => "AUX4", "Energy recovery ventilation" => "AUX5", "Mains (Phase B)" => "Ch1", "Kitchen plugs (Phase B) and kitchen counter" => "Ch1", "Oven (Phase B)" => "AUX1", "Bathroom" => "AUX2", "Living room and balcony" => "AUX3",  "Hot water tank (Phase B)" => "AUX4", "Refrigerator" => "AUX5");

    //Xaxis is always a single variable, but multiple variables might be plotted along the y-axis. Here we set the x-axis label to what we received and the y-axis label to the last array name
	$bigArray["xaxis"] = $xaxis;
    
    if ($xtype == "sensorarray") {
        $x = end($x);
    }
    $yDataTypes = array();
    
    for ($i = 0; $i < count($ytypes); $i++) {
    	$ytype = $ytypes[$i];
    	$yaxis = $yaxises[$i];

    //This code block pulls data from a sensor or array of sensors and adds it to the JSON array
	foreach ($apartments as $apartment) {
		if ($apartment != -1 && !needsApartment($ytype, $y[$i], $yaxis))
			$apartment = -1;
		if ($ytype == "sensorarray") {
			$sensor = $y[$i];

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

        //If the frontend has requested a formula then we fetch the formula and process that, then add it to the JSON array
		} else if ($ytype == "formula") {

            //get the actual function body from the function name
            $ydata = ConfigurationDB::fetchFunction($yaxis);

            // if fetchFunction returns false, this name doesn't exist in db
            if (!$ydata)
                array_push($messages, "No equation with the name $yaxis found");
            else {
            	$yDataTypes[$i] = $ydata['Type'];
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
				
	         $ydata = Engineer2::getEnergyColumnData($d1, $d2,$y[$i], $period);

				$energyColumns = Engineer2::getEnergyColumns();

	         foreach ($ydata as $date=>$value) {
	             $bigArray['values'][$apartment][$date][$energyColumns[$y[$i]]]["x"] = $date;
	             $bigArray['values'][$apartment][$date][$energyColumns[$y[$i]]]["y"] = $value;
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
  }

  $bigArray['yaxis'] = getYAxis($y, $ytypes, $yaxises, $yDataTypes);
	if ($bigArray['yaxis'] === false) {
		$messages[] = 'Incompatible sensor types have been selected';
		unset($bigArray['values']);
		$bigArray['messages'] = $messages;
		$json = json_encode($bigArray);
		echo $json;
		die;
	}

    if (!isset($bigArray['values'])) {
        $messages[] = 'No data found for the given date range.';
    }
	
    if ($aptMultiple == 'avg' || $aptMultiple == 'sum') {
    	$allApts = $apartments;
    	if (($key = array_search(-1, $allApts)) !== false)
    		unset($allApts[$key]);
    	
    	$allTS = array_keys($bigArray['values'][$allApts[0]]);
    	$allY =  array_keys($bigArray['values'][$allApts[0]][$allTS[0]]);
		
    	foreach ($allTS as $ts) {
    		foreach ($allY as $yItem) {
    			$aggrVal = 0;
    			foreach ($allApts as $apt)
    				if (isset($bigArray['values'][$apt][$ts][$yItem]))
    					$aggrVal += $bigArray['values'][$apt][$ts][$yItem]['y'];

    			if ($aptMultiple == 'avg')
    				$aggrVal /= count($allApts);
    			$bigArray['values'][-1][$ts][$yItem]['x'] = $ts;
    			$bigArray['values'][-1][$ts][$yItem]['y'] = $aggrVal;
    		}
    	}
    	
    	foreach ($allApts as $apt)
    		unset($bigArray['values'][$apt]);
    }
    
    foreach ($bigArray['values'] as $apt => $aptValues) {
    	foreach ($aptValues as $ts => $values) {
    		foreach ($values as $sens => $v) {
    			$bigArray['values'][$apt][$ts][$sens]['y'] = round($bigArray['values'][$apt][$ts][$sens]['y'], 3);
    		}
    	}
    }
        
	if (count($messages) == 0) {
		array_push($messages, "Success!");
	}

	$bigArray['granularity'] = $period;
	$bigArray['messages'] = $messages;


	$chPhMapping= array();
	foreach ($y as $yItem)
		if (isset($channelMapping[$yItem]) && isset($phaseMapping[$yItem]))
			$chPhMapping[$yItem] = $channelMapping[$yItem].$phaseMapping[$yItem];
	if (count($chPhMapping) > 0) {
		$chPhMapping = array_flip($chPhMapping);
		$bigArray['chPhMapping'] = $chPhMapping;
	}

    $json = json_encode($bigArray);
    echo $json;






function getYAxis($y, $ytypes, $yaxises, $yDataTypes = array()) {
	global $channelMapping;
	
	$typeDesc = Engineer::fetchDataTypes();
	$sensorTypes = array(
		"Relative_Humidity" => 7,
		"Outside_Temperature" => 8,
		"Temperature" => 8,
		"CO2" => 1,
		"Hot_Water" => 9,
		"Total_Water" => 9,
		"HeatFlux_Insulation" => 6,
		"HeatFlux_Stud" => 6,
		"Current_Temperature_1" => 8,
		"Current_Temperature_2" => 8,
		"Total_Energy" => 4,
		"Total_Volume" => 10,	
	);

	$types = array();
	for ($i = 0; $i < count($ytypes); $i++) {
		$ytype = $ytypes[$i];
		$yItem = $y[$i];
		$yAxis = $yaxises[$i];
		if (isset($yDataTypes[$i]))
			array_push($types, $yDataTypes[$i]);
		elseif ($ytype == 'energy') //calculated from flow and temp. difference
			array_push($types, 4);
		elseif ($ytype == 'utility')
			array_push($types, 2);
		elseif (isset($channelMapping[$yItem]) || isBasEnergy($yItem))
			array_push($types, 3);
		elseif (isset($typeDesc[$yAxis]))
			array_push($types, $yAxis);
		elseif (isset($sensorTypes[$yItem]))
			array_push($types, $sensorTypes[$yItem]);
		else
			array_push($types, 'Unknown');
	}

	$types = array_unique($types);	
	//for now if we have two incompatible types we return an error
	if (count($types) > 1)
		return false;
	if (count($types) == 0 || $types[0] == 'Unknown')
		return 'Unknown';
	
	return $typeDesc[$types[0]];
}

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

  
	if ($startdate == null || $enddate == null) {
		return "Invalid start date or end date given, unable to graph.";
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
 	$varname = strtolower("bas_energy_$name");
 	return isset($dbvars[$varname]);
}

function needsApartment($ytype, $y, $yaxis) {
	if ($ytype == "energy" || isBasEnergy($y) || $y == "Outside_Temperature" || $y == "Total_HP" || ($ytype == "utility" && $yaxis == "HP_Electricity"))
		return 0;
	return 1;
}

function bailIfErrors($messages, $period, $bigArray) {
	//If any errors have occurred at this point there's:00 no way we can process the query, so we spit out the query data, any error messages, and die
	if (count($messages) > 0 && $messages[0] != null) {
		$bigArray['granularity'] = $period;
		$bigArray['messages'] = $messages;
		$json = json_encode($bigArray);
		echo $json;
		die;
	}
}