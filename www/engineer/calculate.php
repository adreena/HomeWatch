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
if (!(isset($_POST['calculation']) && isset($_POST['energy']) && isset($_POST['startdate']) && isset($_POST['enddate']))) {
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
$startDate = \DateTime::createFromFormat('Y-m-d', $_POST['startdate']);
$endDate = \DateTime::createFromFormat('Y-m-d', $_POST['enddate']);

/* Die because we couldn't parse the date format. */
if ($startDate === false || $endDate === false) {
    http_response_code(400);
    die;
}


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
    'NUM1' => "Heat_Energy_GJ_COP1",
    'NUM2' => "Heat_Energy_GJ_COP2",
    'NUM3' => "Heat_Energy_GJ_COP3",
    'P11-@P110' => "Geo_KWH_P1_1",
    'P12-@P120' => "Geo_KWH_P1_2",
    'HPx1-@HPx10' => "Heat_Pump_KWH_HP1",
    'HPx2-@HPx20' => "Heat_Pump_KWH_HP2",
    'HPx3-@HPx30' => "Heat_Pump_KWH_HP3",
    'HPx4-@HPx40' => "Heat_Pump_KWH_HP4",
    'Hours' => "Hours_In_Period",
    'SHTS' => "Solar_SHTS_KWH",
    'P7_1' => "Elect_Usage_KWH_DWHR_P7_1",
    'P8' => "Elect_Usage_KWH_DWHR_P8",
    'P2_1' => "Elect_Usage_KWH_HP_P2_1",
    'P2_2' => "Elect_Usage_KWH_HP_P2_2",
    'P2_3' => "Elect_Usage_KWH_HP_P2_3",
    'P2_4' => "Elect_Usage_KWH_HP_P2_4",
    'P4_1' => "Elect_Usage_KWH_Boilers_P4_1",
    'P4_2' => "Elect_Usage_KWH_Boilers_P4_2",
    'BLR_1' => "Elect_Usage_KWH_Boilers_BLR_1",
    'BLR_2' => "Elect_Usage_KWH_Boilers_BLR_2",
    'P3_1' => "Elect_Usage_KWH_Heat_Loop_P3_1",
    'P3_2' => "Elect_Usage_KWH_Heat_Loop_P3_2",
    'DOM1' => "Total_Elect_KWH_COP1",
    'DOM2' => "Total_Elect_KWH_COP2",
    'DOM3' => "Total_Elect_KWH_COP3"
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
        getColumnName($calc);
        echo "<br>$cols[$calc] = $val <br>\n";
    } else {
        echo "<br>" . $_POST['name'] . " = $val <br>\n";
    }

}

