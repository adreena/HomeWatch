<?php

///
/// Initiates a request to reset a users password
///

ini_set('display_errors', 0); // Allows PHP to return response 500 on errors

require_once __DIR__ . "/../vendor/autoload.php";

if (isset($_GET['username']) && isset($_GET['token'])) {
    $username = $_GET['username'];
    $token = $_GET['token'];
    
    $twig = \UASmartHome\TwigSingleton::getInstance();
    echo $twig->render('reset-password.html', array(
        'username' => $username,
        'token' => $token
    ));


} else {

    if (!isset($_POST['email'])) {
        http_response_code(400);
        die;
    }

    $userProvider = new \UASmartHome\Auth\DefaultUserProvider();
    $success = $userProvider->resetUserPassword($_POST['email']);
    if (!$success) {
        http_response_code(400);
        die;
    }
}

