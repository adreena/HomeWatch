<?php

/*
 * Proposed index page for searching. Nowhere near done yet.
 */

require_once __DIR__ . '/../vendor/autoload.php';

use \UASmartHome\Auth\User;
use \UASmartHome\Auth\Firewall;
use \UASmartHome\EquationParser;
use \UASmartHome\Database\Configuration\ConfigurationDB;

//TODO: UNCOMMENT THE FOLLOWING LINE:
Firewall::instance()->restrictAccess(Firewall::ROLE_ENGINEER, Firewall::ROLE_MANAGER);

$categories = array();

/* Fetch all of the crazy formula and alerts data. */
$config = new ConfigurationDB();
$unfilteredCategories = $config->fetchConfigData();

$categories['Formulae'] = array();
foreach ($unfilteredCategories['functions'] as $func)  {
    /* For some strange reason, it took me three tries to bury this name = name 
    * crap. I *actually* modified all other foreach loops before I got the 
    * right one because I am THAT absent-minded. */
    $categories['Formulae'][$func['name']] = $func['name'];
};

$categories['Alerts'] = array();
foreach ($unfilteredCategories['alerts'] as $alert)  {
    $categories['Alerts'][$alert['name']] = $alert['name'];
};

$categories['Sensors'] = array();
foreach (EquationParser::$DBVARS as $grossName => $DBName) {
    $categories['Sensors'][$DBName] = $grossName;
};

// TODO: Still need apartment data!


/* Get session user ID. */
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

echo $twig->render("manager/search.html", array(
    "categories" => json_encode($categories),
    // TODO: un-hardcode this
    "apartments" => json_encode(array(1,2,3,4,5,6)),
    "userRole" => $role
));

