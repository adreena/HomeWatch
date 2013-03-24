<?php

/**
 * index.php
 *
 * For logged out users, the login page.
 * For logged-in users, the appropriate home page for their role.
 *
 * Right now, the Twig template rendered is  $role/home.html,
 * (e.g., resident/home.html).
 *
 */
	   
require_once __DIR__ . '/vendor/autoload.php';

use \UASmartHome\Auth\User;

// Start dat session, yo!
session_start();
$user = User::getSessionUser();

$twig = \UASmartHome\TwigSingleton::getInstance();

// User is logged in...
if ($user != null) {
    $homepage = '';
    
    // Give the user the approrpiate home page based on role.
    switch ($user->getRoleID()) {
        case User::ROLE_RESIDENT:
            $homepage = 'resident/home.html';
            break;
        case User::ROLE_DEV:
        case User::ROLE_ADMIN:
        case User::ROLE_MANAGER:
            $homepage = 'manager/home.html';
            break;
        case User::ROLE_ENGINEER:
            $homepage = 'engineer/home.html';
            break;
        default:
            $homepage = 'resident/home.html';
            break;
    }
            
    echo $twig->render($homepage, array(
        "user" => $user
    ));

} else {
    // No user session? Make 'em login.
    echo $twig->render('login.html');
}

