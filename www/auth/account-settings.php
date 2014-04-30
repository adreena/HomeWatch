<?php

///
/// Initiates a request to reset a users password
///

ini_set('display_errors', 0); // Allows PHP to return response 500 on errors

require_once __DIR__ . '/../vendor/autoload.php';

use \UASmartHome\Auth\Firewall;
use \UASmartHome\Auth\User;
Firewall::instance()->restrictAccess(Firewall::ROLE_ENGINEER, Firewall::ROLE_MANAGER, Firewall::ROLE_RESIDENT);


if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $twig = \UASmartHome\TwigSingleton::getInstance();
    echo $twig->render('account-settings.html');
} else {
    if (!isset($_POST['curpassword']) || !isset($_POST['password'])) {
        http_response_code(400);
        die;
    }
    
    $user = User::getSessionUser();
    if (!$user) {
    	http_response_code(400);
    	echo "Not logged in\n";
    	die;
    }
    
    $userProvider = new \UASmartHome\Auth\DefaultUserProvider();
    $fuser = $userProvider->fetchUser($user->getUsername(), $_POST['curpassword']);
    if (!$fuser) {
    	http_response_code(400);
    	echo "Current password incorrect.\n";
    	die;
    }
    
    if ($fuser != $user) {
    	$sessuid = $user->getID();
    	$fuserid = $fuser->getID();
    	trigger_error("Account settings: fetchUser returned different user! (s: $sessuid, f: $fuserid)", E_USER_WARNING);
    	http_response_code(400);
    	echo "Internal error\n";
    	die;
    }
    
    $success = $userProvider->setUserPassword($user, $_POST['password']);
    if (!$success) {
        http_response_code(400);
        trigger_error("Failed to set user password");
        echo "Internal error\n";
        die;
    }
}

