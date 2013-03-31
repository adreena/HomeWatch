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

/*
if (!ConfigurationDB::submitAlert($alert)) {
    http_response_code(400);
}
*/
$result = Engineer2::EQ($_POST['startdate'], $_POST['enddate'], $FORMULA[$_POST['calculation']], $_POST['energy']);

foreach ($result as $calc=>$val) {
    if (is_null($val))
        echo "<br>$calc = null (no data) <br>\n";
    else
        echo "<br>$calc = $val <br>\n";
}
