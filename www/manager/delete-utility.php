<?php

///
/// Handles a request to delete a utility
///

ini_set('display_errors', 0); // Allows PHP to return response 500 on errors

require_once __DIR__ . '/../vendor/autoload.php';

use \UASmartHome\Auth\Firewall;
Firewall::instance()->restrictAccess(Firewall::ROLE_MANAGER);

use \UASmartHome\Database\Utilities\UtilitiesDB;
use \UASmartHome\Database\Utilities\Utility;

// Check that the request is valid
if (!isset($_POST['id'])) {
    http_response_code(400);
    die;
}

if (!UtilitiesDB::deleteUtility($_POST['id'])) {
    http_response_code(400);
}
