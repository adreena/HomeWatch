<?php

///
/// Handles login requests.
///

ini_set('display_errors', 0); // Allows PHP to return response 500 on errors

require_once __DIR__ . "/../vendor/autoload.php";

use \UASmartHome\Auth\DefaultUserProvider;
use \UASmartHome\Auth\User;

if (!isset($_POST['username']) || !isset($_POST['password'])) {
    http_response_code(400);
    die;
}

$userProvider = new DefaultUserProvider();
$user = $userProvider->fetchUser($_POST['username'], $_POST['password']);
if ($user == null) {
    http_response_code(400);
    die;
}

$user->login();

