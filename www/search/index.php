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
//Firewall::instance()->restrictAccess(Firewall::ROLE_ENGINEER, Firewall::ROLE_MANAGER);

$categories = array();

/* Fetch all of the crazy formula and alerts data. */
$config = new ConfigurationDB();
$unfilteredCategories = $config->fetchConfigData();

$categories['Formulae'] = array();
foreach ($unfilteredCategories['functions'] as $func)  {
    $categories['Formulae'][$func['id']] = $func['name'];
};

$categories['Alerts'] = array();
foreach ($unfilteredCategories['alerts'] as $alert)  {
    $categories['Alerts'][$alert['id']] = $alert['name'];
};

$categories['Sensors'] = array();
foreach (EquationParser::$DBVARS as $grossName => $DBName) {
    $categories['Sensors'][$DBName] = $grossName;
};

// TODO: Still need apartment data!

$twig = \UASmartHome\TwigSingleton::getInstance();

echo $twig->render("manager/search.html", array(
    "categories" => json_encode($categories),
    "apartments" => json_encode(array(1,2,3,4,5,6))
));

