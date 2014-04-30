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

    $extraOpts = array(
        "user" => $user
    );

    // Give the user the approrpiate home page based on role.
    switch ($user->getRoleID()) {
        case User::ROLE_RESIDENT:
            $homepage = 'resident/home.html';

            /*
             * The resident homepage needs to know a whole bunch of
             * stuff fo rendering. This should really be offset to some
             * other file, but we'll put it here for now.
             */
            $view = new \UASmartHome\View();

            $currentInfo = $view->getCurrentInfo();
            $rank = $view->getRank();

            $extraOpts['currentinfo'] = $currentInfo;
            $extraOpts['rank'] = $rank;

            break;

        case User::ROLE_DEV:
        case User::ROLE_ADMIN:
        case User::ROLE_MANAGER:
            //$homepage = 'manager/home.html';
            $homepage='manager/new_home.html';
            break;

        case User::ROLE_ENGINEER:
            $homepage = 'engineer/home.html';
            break;

        default:
            /* User is logged in but has no known role! */
            http_response_code(500);
            die;
    }

    echo $twig->render($homepage, $extraOpts);

} else {
    // No user session? Make 'em login.
    echo $twig->render('new_login.html');
}

