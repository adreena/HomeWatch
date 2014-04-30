<?php

/*
 * Index page for searching.
 *
 * Loads all of the sensor, formula, alert and apartment data,
 * and embeds the JSON in the page.
 */

require_once __DIR__ . '/../vendor/autoload.php';

use \UASmartHome\Auth\User;
use \UASmartHome\Auth\Firewall;
use \UASmartHome\EquationParser;
use \UASmartHome\Database\Engineer;
use \UASmartHome\Database\Engineer2;
use \UASmartHome\Database\Configuration\ConfigurationDB;

Firewall::instance()->restrictAccess(Firewall::ROLE_ENGINEER, Firewall::ROLE_MANAGER);

/* Get session user role. */
switch (User::getSessionUser()->getRoleID()) {
    case User::ROLE_DEV:
    case User::ROLE_ADMIN:
    case User::ROLE_MANAGER:
        $role = 'manager';
        break;
    case User::ROLE_ENGINEER:
        $role = 'engineer';
        break;
    default:
        $homepage = 'resident/home.html';
        die();
};


$twig = \UASmartHome\TwigSingleton::getInstance();

echo $twig->render("manager/air-quality/daily.html");

