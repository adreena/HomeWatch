<?php namespace UASmartHome;

require_once __DIR__ . '/../vendor/autoload.php';

use \UASmartHome\Auth\Firewall;
Firewall::instance()->restrictAccess(Firewall::ROLE_RESIDENT);

/* Setup Twig environment. */
$twig = \UASmartHome\TwigSingleton::getInstance();

/* Initialize all of these! */
$view = new \UASmartHome\View();

$currentInfo = $view->getCurrentInfo();
$rank = $view->getRank();

echo $twig->render('resident/home.html',
    array('currentinfo' => $currentInfo, 'rank' => $rank));

