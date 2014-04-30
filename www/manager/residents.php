<?php namespace UASmartHome;

require_once __DIR__ . '/../vendor/autoload.php';

use \UASmartHome\Auth\Firewall;
Firewall::instance()->restrictAccess(Firewall::ROLE_MANAGER);

$residents = \UASmartHome\Database\ResidentDB::fetchResidents();

$twig = \UASmartHome\TwigSingleton::getInstance();
echo $twig->render('manager/residents.html',
    array('residents' => $residents));

