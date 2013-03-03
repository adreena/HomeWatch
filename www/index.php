<?php
	   
require_once __DIR__ . '/vendor/autoload.php';

$role = 'resident'; //Defining user as resident until we get DB up
//$role = 'manager';
//$role = 'engineer';

// TODO: Perhaps get Twig Environment with user.
$twig = \TwigSingleton::getInstance();

// User is logged in...
if (isset($_SESSION['user'])) {
    // Shouldn't the user contain role information? Whatever...
	$user = $_SESSION['user'];

    echo $twig->render("$role/home.html", array(
        "user" => $user
    ));
} else {
    // No user session? Make 'em login.
    echo $twig->render('login.html');
}

