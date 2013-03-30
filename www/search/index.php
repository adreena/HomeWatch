<?php

/*
 * Proposed index page for searching. Nowhere near done yet.
 */

require_once __DIR__ . '/../vendor/autoload.php';

use \UASmartHome\Auth\User;
use \UASmartHome\Auth\Firewall;
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
foreach ($unfilteredCategories['alerts'] as $func)  {
    $categories['Alerts'][$func['id']] = $func['name'];
};

// TODO: Still need sensor and aparetment data!

$twig = \UASmartHome\TwigSingleton::getInstance();

echo $twig->render("manager/search.html", array(
    "categories" => json_encode($categories)
));

