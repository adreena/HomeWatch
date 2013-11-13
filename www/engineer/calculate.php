<?php

///
/// Handles a request for a calculation
///

ini_set('display_errors', 0); // Allows PHP to return response 500 on errors

require_once __DIR__ . '/../vendor/autoload.php';

use \UASmartHome\Auth\Firewall;
Firewall::instance()->restrictAccess(Firewall::ROLE_ENGINEER);

use \UASmartHome\Database\Engineer2;

// Check that the request is valid
if (!(isset($_GET['calculation'])
    && isset($_GET['startdate'])
    && isset($_GET['enddate'])
    && isset($_GET['starthour'])
    && isset($_GET['endhour'])))
{
    http_response_code(400);
    die;
}

static $FORMULA =  array(
    "eq1" => 1,
    "eq2" => 2,
    "eq3" => 3
);

$calculation = $_GET['calculation'];

/* Stupid PHP and its stupid timebutts. */
date_default_timezone_set('America/Edmonton');
$startDate = \DateTime::createFromFormat('Y-m-d H:i', $_GET['startdate'] .
    ' ' . $_GET['starthour']);
$endDate = \DateTime::createFromFormat('Y-m-d H:i', $_GET['enddate'] .
    ' ' . $_GET['endhour']);

/* Die because we couldn't parse the date format. */
if ($startDate === false || $endDate === false) {
    http_response_code(400);
    echo "Please select start and end dates and times.\n";
    die;
}


/* Do the awesome query! */
$result = Engineer2::EQ(
    $startDate->format('Y-m-d H:i'),
    $endDate->format('Y-m-d H:i'),
    $FORMULA[$calculation]
);

static $prettyColumnNames = array(
    'COP1' => array("COP of Heat Pumps", ""),
    'COP2' => array("COP of Solar+DWHR+Geo Field+Heat Pumps", ""),
    'COP3' => array("COP of Entire Heating System", ""),
    'NUM1' => array("Heat Energy COP1", ""),
    'NUM2' => array("Heat Energy COP2", ""),
    'NUM3' => array("Heat Energy COP3", ""),
    'P-1-1' => array("Geo P1-1", "KWH"),
    'P-1-2' => array("Geo P1-2", "KWH"),
    'HP1' => array("Heat Pump HP1", "KWH"),
    'HP2' => array("Heat Pump HP2", "KWH"),
    'HP3' => array("Heat Pump HP3", "KWH"),
    'HP4' => array("Heat Pump HP4", "KWH"),
    'Hours' => array("Hours In Period", "hours"),
    'SHTS' => array("Solar", "KWH"),
    'P7_1' => array("DWHR P7-1", "KWH"),
    'P8' => array("DWHR P8", "KWH"),
    'P2_1' => array("HP Circ Pump P2-1", "KWH"),
    'P2_2' => array("HP Circ Pump P2-2", "KWH"),
    'P2_3' => array("HP Circ Pump P2-3", "KWH"),
    'P2_4' => array("HP Circ Pump P2-4", "KWH"),
    'P4_1' => array("Boilers P4-1", "KWH"),
    'P4_2' => array("Boilers P4-2", "KWH"),
    'BLR_1' => array("Boiler 1", "KWH"),
    'BLR_2' => array("Boiler 2", "KWH"),
    'P3_1' => array("Heat Loop P3-1", "KWH"),
    'P3_2' => array("Heat Loop P3-2", "KWH"),
    'DOM1' => array("Total Elect COP1", ""),
    'DOM2' => array("Total Elect COP2", ""),
    'DOM3' => array("Total Elect COP3", "")
);

function getColumnName($uglyName) {
    global $prettyColumnNames;

    return isset($prettyColumnNames[$uglyName])
        ? $prettyColumnNames[$uglyName]
        : $uglyName;
}

$energyNames = Engineer2::getEnergyColumns();
/* Select which fun formatting function will be used. */
$func = null;
switch ($calculation) {
    case 'eq1':
        $func = function ($calc, $val) use ($energyNames) {
				if ($calc == 'Energy5_6') $name = $energyNames['Energy5'] . ' + ' . $energyNames['Energy6'];
				else if (isset($energyNames[$calc]))
					$name = $energyNames[$calc];
				else
					$name = $calc;
				$val = sprintf("%.02f", $val);
            return array(
                'key' => "$name Energy",
                'val' => "$val GJ"
            );
        };
        break;

    case 'eq2':
    case 'eq3':
        $func = function ($calc, $val) {
            $labels = getColumnName($calc);
            $name = $labels[0];
            $unit = $labels[1];
            $val = sprintf("%.02f", $val);
            return array(
                'key' => $name,
                'val' => "$val $unit"
            );
        };
        break;

    default:
        $name = $_GET['name'];
        $func = function ($calc, $val) use ($name) {
        	$val = sprintf("%.02f", $val);
            return array(
                'key' => $name,
                'val' => "$val"
            );
        };
        break;
}

if ($calculation == 'eq1')
	$heatPumps = $result['Energy4']-($result['Energy1']+$result['Energy3']);
$formatted = array();
foreach ($result as $calc => $val) {
    if (is_null($val)) {
        $val = "null (no data)";
    }

    $f = $func($calc, $val);
    $formatted[] = $f;
    if ($calculation == 'eq1' && $calc == 'Energy4')
    	$formatted[] = $func('Heat Pumps', $heatPumps);
}

if ($calculation == 'eq1') {
	$pieNames = "DWHR+Geo|Heat Pumps|Solar";
	$pieValues = array($result['Energy3'], $heatPumps, $result['Energy1']);
	if ($result['Energy5_6'] > 0.00001) {
		$pieNames .= "|Boilers";
		$pieValues[] = $result['Energy5_6'];
	}
	
	$pieNames = urlencode($pieNames);
	$pieValues = array_map(function($v) {return $v / 1e6;}, $pieValues);
	$formatted[] = array('key' => 'pie', 'names' => $pieNames, 'values' => $pieValues);
}

echo json_encode($formatted);

