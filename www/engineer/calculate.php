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
    http_response_code(400;
    die;
}


$result = Engineer2::EQ(
    $startDate->format('Y-m-d H:i'),
    $endDate->format('Y-m-d H:i'),
    $FORMULA[$calculation],
    $_POST['energy']
);


foreach ($result as $calc=>$val) {

    if (is_null($val)) {
        $val = "null (no data)";
    }

    if ($calculation === "eq1") {
        echo "<br>" . $_POST['energyname'] . " Energy" . " = $val GJ <br>\n";
    } else if ($calculation === "eq4" || $calculation === "eq5") {
        echo "<br>$calc = $val <br>\n";
    } else {
        echo "<br>" . $_POST['name'] . " = $val <br>\n";
    }

}

