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

$categories = array();

/* Fetch all of the crazy formula and alerts data. */
$config = new ConfigurationDB();
$unfilteredCategories = $config->fetchConfigData();

/* SUPER NOTE: When a value is the empty string, the client should use the key 
 * as both the name to send to the server AND the display name. */

$categories['Formulae'] = array();
foreach ($unfilteredCategories['functions'] as $func)  {
    /* Alerts and formulas are only applicable on the "y" axis. */
    $categories['Formulae'][$func['name']] = array(
        "displayName" => $func['name'],
        "applicableAxes" => "y"
    );
};

// Add the energy column equations
$energyCols = Engineer2::getEnergyColumns();
$categories['Energy'] = array();
foreach ($energyCols as $col => $description) {
    $categories['Energy'][$col] = array(
        "displayName" => $description,
        "applicableAxes" => "y"
    );
}

$categories['Alerts'] = array();
foreach ($unfilteredCategories['alerts'] as $alert)  {
    $categories['Alerts'][$alert['name']] = array(
        "displayName" => $func['name'],
        "applicableAxes" => "y"
    );
};

/* There should probably be a better source for the sensor names, but ah well. */
$categories['Sensors'] = array();
foreach (array_unique(EquationParser::$DBVARS) as $DBName) {
    $categories['Sensors'][$DBName] = "";
};

/* Get all of the apartments. */
$apartments = Engineer::db_apt_list();

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

echo $twig->render("manager/search.html", array(
    "categories" => json_encode($categories),
    "apartments" => json_encode($apartments),
    "userRole" => $role
));

