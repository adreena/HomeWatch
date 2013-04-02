<?php

///
/// Renders the manager configuration page
///

require_once __DIR__ . '/../vendor/autoload.php';

use \UASmartHome\Auth\Firewall;
Firewall::instance()->restrictAccess(Firewall::ROLE_MANAGER);

use \UASmartHome\Database\Configuration\ConfigurationDB;

$configData = ConfigurationDB::fetchConfigData();

$twig = \UASmartHome\TwigSingleton::getInstance();
echo $twig->render('manager/configuration.html', array(
    "configData" => $configData
));

