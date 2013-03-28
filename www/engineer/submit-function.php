<?php

///
/// Handles a request to insert or edit a function in the Equations table
///

ini_set('display_errors', 0); // Allows PHP to return response 500 on errors

require_once __DIR__ . '/../vendor/autoload.php';

use \UASmartHome\Auth\Firewall;
Firewall::instance()->restrictAccess(Firewall::ROLE_ENGINEER);

use \UASmartHome\Database\Configuration\ConfigurationDB;
use \UASmartHome\Database\Configuration\Equation;

// Check that the request is valid
if (!(isset($_POST['name']) && isset($_POST['value']) && isset($_POST['description']))) {
    http_response_code(400);
    die;
}

// Submit the request
$equation = new Equation();
$equation->id = isset($_POST['id']) ? $_POST['id'] : -1;
$equation->name = $_POST['name'];
$equation->body = $_POST['value'];
$equation->description = $_POST['description'];

if (!ConfigurationDB::submitFunction($equation)) {
    http_response_code(400);
}

