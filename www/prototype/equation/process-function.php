<?php

require_once __DIR__ . '/../../vendor/autoload.php';

use \UASmartHome\EquationParser;

$context = array();
$context["startdate"] = "2012-02-29:0";
$context["enddate"] = "2012-03-01:0";
$context["apartment"] = 1;
$context["granularity"] = "Daily";
$context["function"] = "9 * (3+pi) * \$air_temperature$ + \$air_co2$ / 4";       //$_GET['function'];
$context["functionname"] = "functionname";

$json = json_encode($context);

$result = EquationParser::getData($json);

var_dump($result);

