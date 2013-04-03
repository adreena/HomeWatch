<?php

///
/// Initiates a request to reset a users password
///

ini_set('display_errors', 0); // Allows PHP to return response 500 on errors

require_once __DIR__ . "/../vendor/autoload.php";

// If this is a get request, then assume the user is trying to reset their password using the link
// provided in the email
if (isset($_GET['username']) && isset($_GET['token'])) {

    $twig = \UASmartHome\TwigSingleton::getInstance();
    echo $twig->render('reset-password.html', array(
        'username' => $_GET['username'],
        'token' => $_GET['token']
    ));

// Otherwise, check the post data to see if there is enough information to actually reset the user's password
} else {

    if (!isset($_POST['username']) || !isset($_POST['token']) || !isset($_POST['password'])) {
        http_response_code(400);
        die;
    }

    $userProvider = new \UASmartHome\Auth\DefaultUserProvider();
    $success = $userProvider->resetUserPassword($_POST['username'], $_POST['token'], $_POST['password']);
    if (!$success) {
        http_response_code(400);
        die;
    }
}

