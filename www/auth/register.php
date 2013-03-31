<?php

///
///
///

require_once __DIR__ .  "/../vendor/autoload.php";

use \UASmartHome\Auth\Firewall;
Firewall::instance()->restrictAccess(Firewall::ROLE_MANAGER);

$twig = \UASmartHome\TwigSingleton::getInstance();
echo $twig->render('manager/register.html');

