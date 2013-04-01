<?php

///
/// Renders the configuration page for inserting and editing new functions and constants
///

require_once __DIR__ . '/../vendor/autoload.php';

use \UASmartHome\Auth\Firewall;
Firewall::instance()->restrictAccess(Firewall::ROLE_ENGINEER);

use \UASmartHome\Database\Engineer2;

$energyColumns = Engineer2::getEnergyColumns();

$twig = \UASmartHome\TwigSingleton::getInstance();
echo $twig->render('engineer/calculations.html', array(
    "energycolumns" => $energyColumns
));

