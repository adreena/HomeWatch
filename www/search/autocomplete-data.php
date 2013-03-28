<?php

header('Content-Type: application/json; charset=utf-8');
ini_set('display_errors', 0); // Allows PHP to return response 500 on errors

require_once __DIR__ . '/../vendor/autoload.php';

use \UASmartHome\Auth\Firewall;
Firewall::instance()->restrictAccess(Firewall::ROLE_ENGINEER, Firewall::ROLE_MANAGER);

use \UASmartHome\Database\Configuration\ConfigurationDB;
use \UASmartHome\EquationParser;

$constants = ConfigurationDB::fetchConstants(null);
$variables = EquationParser::getVariables();

$data = $variables;

// Add the constants
// NOTE: It is assumed that constants will override variables on name collisions
foreach ($constants as $constant) {
    $data[$constant['name']] = $constant['value'];
}

$json = json_encode($data);

echo $json;

die;
