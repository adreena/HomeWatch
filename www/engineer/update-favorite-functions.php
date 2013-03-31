<?php

///
/// Handles a request to update user selected alerts
///

//ini_set('display_errors', 0); // Allows PHP to return response 500 on errors

require_once __DIR__ . '/../vendor/autoload.php';

use \UASmartHome\Auth\Firewall;
Firewall::instance()->restrictAccess(Firewall::ROLE_ENGINEER);

use \UASmartHome\Database\Configuration\ConfigurationDB;

$favorites = array();

// Check that the request is valid
if (isset($_POST['favorites'])) {
    $favorites = $_POST['favorites'];
}

if (!ConfigurationDB::updateFavoriteFunctions($favorites)) {
    http_response_code(400);
}

