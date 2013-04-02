<?php

///
/// Initiates a request to reset a users password
///

ini_set('display_errors', 0); // Allows PHP to return response 500 on errors

require_once __DIR__ . "/../vendor/autoload.php";

use \UASmartHome\Auth\DefaultUserProvider;

if (!isset($_POST['email'])) {
    http_response_code(400);
    die;
}

$userProvider = new DefaultUserProvider();
$success = $userProvider->resetUserPassword($_POST['email']);
if (!$success) {
    http_response_code(400);
    die;
}

