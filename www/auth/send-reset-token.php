<?php

///
/// Initiates a request to send a new reset token to the user with the given email
///

ini_set('display_errors', 0); // Allows PHP to return response 500 on errors

require_once __DIR__ . "/../vendor/autoload.php";

if (!isset($_POST['email'])) {
    http_response_code(400);
    die;
}

$userProvider = new \UASmartHome\Auth\DefaultUserProvider();
$success = $userProvider->sendResetToken($_POST['email']);
if (!$success) {
    http_response_code(400);
    die;
}

