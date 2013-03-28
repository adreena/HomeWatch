<?php

require_once __DIR__ . '/../../vendor/autoload.php';

use \UASmartHome\EquationParser;
use \UASmartHome\Database\Configuration\ConfigurationDB;

$context = array();
$context["startdate"] = "2012-02-29:0";
$context["enddate"] = "2012-03-01:0";
$context["apartment"] = 1;
$context["granularity"] = "Daily";
$context["functionname"] = $_GET['function'];

$fn = ConfigurationDB::fetchFunction($context["functionname"]); // Technically, this isn't necessary. The function body is already known.
$context['function'] = $fn['Value'];

$json = json_encode($context);

$result = EquationParser::getData($json);

var_dump($result);

