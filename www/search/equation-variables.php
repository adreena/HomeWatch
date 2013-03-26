<?php

ini_set('display_errors', 0); // Allows PHP to return response 500 on errors

require_once __DIR__ . '/../vendor/autoload.php';

use \UASmartHome\EquationParser;

header('Content-Type: application/json; charset=utf-8');

$equationVariables = EquationParser::getVariables();

$jsonEquationVariables = json_encode($equationVariables);

echo $jsonEquationVariables;

die;
