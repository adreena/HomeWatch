<?php

//ini_set('display_errors', 0); // Allows PHP to return response 500 on errors

require_once __DIR__ . '/../vendor/autoload.php';

use \UASmartHome\Database\Equation\EquationDB;

header('Content-Type: application/json; charset=utf-8');

$equationData = EquationDB::fetchUserData();

$jsonEquationData = json_encode($equationData);

echo $jsonEquationData;

die;
