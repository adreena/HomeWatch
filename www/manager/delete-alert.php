<?php

///
/// Handles a request to delete an alert
///

ini_set('display_errors', 0); // Allows PHP to return response 500 on errors

require_once __DIR__ . '/../vendor/autoload.php';

use \UASmartHome\Auth\Firewall;
Firewall::instance()->restrictAccess(Firewall::ROLE_MANAGER, Firewall::ROLE_ENGINEER);

use \UASmartHome\Database\Configuration\ConfigurationDB;
use \UASmartHome\Database\Engineer;

// Check that the request is valid
if (!isset($_POST['id'])) {
    http_response_code(400);
}

// Perform the deletion
if (!ConfigurationDB::deleteAlert($_POST['id'])) {
    http_response_code(500);
}

Engineer::db_Delete_Alert(crc32($_POST['value']) . "_Alert");
