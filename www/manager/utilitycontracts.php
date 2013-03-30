<?php

///
/// Renders the page for adding, editing, and deleting utility costs
///

require_once __DIR__ . '/../vendor/autoload.php';

use \UASmartHome\Auth\Firewall;
Firewall::instance()->restrictAccess(Firewall::ROLE_MANAGER);

use \UASmartHome\Database\Utilities\UtilitiesDB;

$utilitiesData = UtilitiesDB::fetchUtilityData();

$twig = \UASmartHome\TwigSingleton::getInstance();
echo $twig->render('manager/utilitycontracts.html', array(
    "utilitiesData" => $utilitiesData
));

