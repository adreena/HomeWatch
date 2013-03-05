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


// Start dat session, yo!
//$user = \UASmartHome\Auth\User::GetSessionUser();
session_start();

$twig = \TwigSingleton::getInstance();

// User is logged in...
if (isset($_SESSION['user'])) {
    // Shouldn't the user contain role information? Whatever...
	$user = $_SESSION['user'];
    // So... this won't work when the User class is working.
    $role = $user['role'];

    echo $twig->render("$role/home.html", array(
        "user" => $user
    ));
} else {
    // No user session? Make 'em login.
    echo $twig->render('login.html');
}

