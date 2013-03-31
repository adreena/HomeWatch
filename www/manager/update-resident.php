<?php

///
/// Handles a request to insert or edit a utilty
///

ini_set('display_errors', 0); // Allows PHP to return response 500 on errors

require_once __DIR__ . '/../vendor/autoload.php';

use \UASmartHome\Auth\Firewall;
Firewall::instance()->restrictAccess(Firewall::ROLE_MANAGER);

use \UASmartHome\Database\ResidentDB;

// Check that the request is valid
if (!(isset($_POST['id']) && isset($_POST['name']) && isset($_POST['room']) && isset($_POST['location']) && isset($_POST['roomstatus']))) {
    http_response_code(400);
    die;
}

$id = $_POST['id'];
$name = $_POST['name'];
$username = $_POST['username'];
$location = $_POST['location'];
$roomstatus = $_POST['roomstatus'];

if (!ResidentDB::Resident_DB_Update($id, $roomstatus, $name, $username)) {
    http_response_code(400);
}
