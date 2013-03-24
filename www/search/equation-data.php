<?php

require_once __DIR__ . '/../vendor/autoload.php';

use \UASmartHome\Database\EquationDB;

header('Content-Type: application/json; charset=utf-8');

$equationData = EquationDB::fetchUserData();

$jsonEquationData = json_encode($equationData);

echo $jsonEquationData;

die;
