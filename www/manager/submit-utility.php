<?php

///
/// Handles a request to insert or edit a utilty
///

ini_set('display_errors', 0); // Allows PHP to return response 500 on errors

require_once __DIR__ . '/../vendor/autoload.php';

use \UASmartHome\Auth\Firewall;
Firewall::instance()->restrictAccess(Firewall::ROLE_MANAGER);

use \UASmartHome\Database\Utilities\UtilitiesDB;
use \UASmartHome\Database\Utilities\Utility;

// Check that the request is valid
if (!(isset($_POST['type']) && isset($_POST['price']) && isset($_POST['startdate']) && isset($_POST['enddate']))) {
    http_response_code(400);
    die;
}

// Submit the request
$utility = new Utility();
$utility->id = isset($_POST['id']) ? $_POST['id'] : -1;
$utility->type = $_POST['type'];
$utility->price = $_POST['price'];
$utility->startdate = $_POST['startdate'];
$utility->enddate = $_POST['enddate'];

if (!UtilitiesDB::submitUtility($utility)) {
    http_response_code(400);
}
