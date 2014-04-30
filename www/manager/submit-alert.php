<?php

///
/// Handles a request to insert or edit an alert
///

ini_set('display_errors', 0); // Allows PHP to return response 500 on errors

require_once __DIR__ . '/../vendor/autoload.php';

use \UASmartHome\Auth\Firewall;
Firewall::instance()->restrictAccess(Firewall::ROLE_MANAGER, Firewall::ROLE_ENGINEER);

use \UASmartHome\Database\Configuration\ConfigurationDB;
use \UASmartHome\Database\Configuration\Alert;
use \UASmartHome\Database\Engineer;

// Check that the request is valid
if (!(isset($_POST['name']) && isset($_POST['value']) && isset($_POST['description']))) {
    http_response_code(400);
    die;
}

// Submit the request
$alert = new Alert();
$alert->id = isset($_POST['id']) ? $_POST['id'] : -1;
$alert->name = $_POST['name'];
$alert->value = $_POST['value'];
$alert->description = $_POST['description'];

if (!ConfigurationDB::submitAlert($alert)) {
    http_response_code(400);
}

Engineer::db_Delete_Alert(crc32($_POST['value']) . "_Alert");
