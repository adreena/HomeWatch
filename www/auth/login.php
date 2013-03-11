<?php

///
/// Handles login requests.
/// If this request came from a form (login.html), then an attempt is made to login the user with the given post data.
/// Otherwise, the page is redirected back to the login page.
///

require_once __DIR__ . "/../vendor/autoload.php";

use \UASmartHome\Auth\DefaultUserProvider;
use \UASmartHome\Auth\User;

$errorCode = 0; // Error code sent back to the login page (no error yet)

// Form data
$username = isset($_POST['username']) ? $_POST['username'] : null;
$password = isset($_POST['password']) ? $_POST['password'] : null;

// If request came from a form (login.html) then try to login the user.
if (isset($_POST['submit'])) {
    $userProvider = new DefaultUserProvider();
    $user = $userProvider->fetchUser($username, $password);
    if ($user != null) {
        $user->login();
        header('Location: /'); // Send back to index for routing
        exit();
    }
    
    $errorCode = 1;
}

// Otherwise, go to the login page
$twig = \UASmartHome\TwigSingleton::getInstance();
echo $twig->render('login.html', array(
    "username" => $username,
    "errorCode" => $errorCode
));
    
