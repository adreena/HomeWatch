<?php

///
/// Handles user registration requests.
/// If this request came from a form (register.html), then an attempt is made to register a new user with the given post data.
/// Otherwise, the page is redirected back to the registration page.
///

require_once __DIR__ .  "/../vendor/autoload.php";

use \UASmartHome\Auth\AccountData;
use \UASmartHome\Auth\DefaultUserProvider;
use \UASmartHome\Auth\RegistrationResult;
use \UASmartHome\Auth\User;

$regResult = null;

// Form data
$data = new AccountData();
$data->username = isset($_POST['username']) ? $_POST['username'] : null;
$data->roleID = isset($_POST['role']) ? $_POST['role'] : null;
$data->email = isset($_POST['email']) ? $_POST['email'] : null;
$data->password = isset($_POST['password']) ? $_POST['password'] : null;

// If request came from a form (register.html) then try to register a new user.
if (isset($_POST['submit'])) {

    // Attempt to register
    $userProvider = new DefaultUserProvider();
    $regResult = $userProvider->registerNewUser($data);
    
    if ($regResult->getIsOK()) {
        
        // Logout the current user, if any
        $sessionUser = User::getSessionUser();
        if ($sessionUser != null) {
            $sessionUser->logout();
        }
        
        // Send the new user to login
        header('Location: /');
        exit();
    }
}

// Otherwise, show the register page
$twig = \UASmartHome\TwigSingleton::getInstance();
echo $twig->render('register.html', array(
    "account" => $data,
    "result" => $regResult
));
    
