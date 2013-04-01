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
if (!(isset($_POST['calculation'])
    && isset($_POST['energy'])
    && isset($_POST['startdate'])
    && isset($_POST['enddate'])
    && isset($_POST['starthour'])
    && isset($_POST['endhour'])))
{
    http_response_code(400);
    die;
}

static $FORMULA =  array(
    "eq1" => 1,
    "eq2" => 2,
    "eq3" => 3,
    "eq4" => 4,
    "eq5" => 5
);

$calculation = $_POST['calculation'];

/* Stupid PHP and its stupid butts. */
date_default_timezone_set('America/Edmonton');
$startDate = \DateTime::createFromFormat('Y-m-d H:i', $_POST['startdate'] .
    ' ' . $_POST['starthour']);
$endDate = \DateTime::createFromFormat('Y-m-d H:i', $_POST['enddate'] .
    ' ' . $_POST['endhour']);

/* Die because we couldn't parse the date format. */
if ($startDate === false || $endDate === false) {
    http_response_code(400);
    die;
}


print_r($startDate);
echo '<br/>';
print_r($endDate);

$result = Engineer2::EQ(
    $startDate->format('Y-m-d H:i'),
    $endDate->format('Y-m-d H:i'),
    $FORMULA[$calculation],
    $_POST['energy']
);

$cols = array(
    'COP1' => "COP of Solar+DWHR+Geo Field+Heat Pumps",
    'COP2' => "COP of Entire Heating System",
    'COP3' => "COP of Heat Pumps",
    'NUM1' => "Heat Energy GJ COP1",
    'NUM2' => "Heat Energy GJ COP2",
    'NUM3' => "Heat Energy GJ COP3",
    'P11-P110' => "Geo KWH P1 1",
    'P12-P120' => "Geo KWH P1 2",
    'HPx1-HPx10' => "Heat Pump KWH HP1",
    'HPx2-HPx20' => "Heat Pump KWH HP2",
    'HPx3-HPx30' => "Heat Pump KWH HP3",
    'HPx4-HPx40' => "Heat Pump KWH HP4",
    'Hours' => "Hours In Period",
    'SHTS' => "Solar SHTS KWH",
    'P7_1' => "Elect Usage KWH DWHR P7 1",
    'P8' => "Elect Usage KWH DWHR P8",
    'P2_1' => "Elect Usage KWH HP P2 1",
    'P2_2' => "Elect Usage KWH HP P2 2",
    'P2_3' => "Elect Usage KWH HP P2 3",
    'P2_4' => "Elect Usage KWH HP P2 4",
    'P4_1' => "Elect Usage KWH Boilers P4 1",
    'P4_2' => "Elect Usage KWH Boilers P4 2",
    'BLR_1' => "Elect Usage KWH Boilers BLR 1",
    'BLR_2' => "Elect Usage KWH Boilers BLR 2",
    'P3_1' => "Elect Usage KWH Heat Loop P3 1",
    'P3_2' => "Elect Usage KWH Heat Loop P3 2",
    'DOM1' => "Total Elect KWH COP1",
    'DOM2' => "Total Elect KWH COP2",
    'DOM3' => "Total Elect KWH COP3"
);

function getColumnName($uglyName) {
    if (isset($cols[$uglyName])) {
        return $cols[$uglyName];
    } else {
        return $uglyName;
    }
};

foreach ($result as $calc => $val) {

    if (is_null($val)) {
        $val = "null (no data)";
    }

    if ($calculation === "eq1") {
        echo "<br>" . $_POST['energyname'] . " Energy" . " = $val GJ <br>\n";
    } else if ($calculation === "eq4" || $calculation === "eq5") {
        $col = getColumnName($calc);
        echo "<br><strong>$col  </strong> = $val <br>\n";
    } else {
        echo "<br><strong> " . $_POST['name'] . " </strong> = $val <br>\n";
    }

}

