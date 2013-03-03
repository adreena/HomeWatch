<?php
	   
require_once __DIR__ . '/vendor/autoload.php';

$role = 'resident'; //Defining user as resident until we get DB up
//$role = 'manager';
//$role = 'engineer';

$twig = \TwigSingleton::getInstance();

// Start dat session, yo!
//$user = \UASmartHome\Auth\User::GetSessionUser();
session_start();

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

