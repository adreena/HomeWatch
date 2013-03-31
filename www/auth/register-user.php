<?php

///
/// Handles a request to register a user
///

header('Content-Type: application/json; charset=utf-8');
//ini_set('display_errors', 0); // Allows PHP to return response 500 on errors

require_once __DIR__ . '/../vendor/autoload.php';

use \UASmartHome\Auth\Firewall;
Firewall::instance()->restrictAccess(Firewall::ROLE_MANAGER);

use \UASmartHome\Auth\AccountData;
use \UASmartHome\Auth\DefaultUserProvider;
use \UASmartHome\Auth\RegistrationResult;
use \UASmartHome\Auth\User;

if (!isset($_POST['accountdata'])) {
    http_response_code(400);
    die;
}

$postAccountData = $_POST['accountdata'];

$data = new AccountData();
$data->username = $postAccountData['username'];
$data->roleID = $postAccountData['role'];
$data->email = $postAccountData['email'];
$data->password = $postAccountData['password'];

if (isset($_POST['roledata'])) {
    $data->roleData = $_POST['roledata'];
}

// Attempt to register
$userProvider = new DefaultUserProvider();
$regResult = $userProvider->registerNewUser($data);

$result = array(
    'username' => $regResult->getUsername(),
    'message' => $regResult->getFriendlyResultOverall()
);

if ($regResult->getIsBad()) {
    http_response_code(400);
}

echo json_encode($result);
