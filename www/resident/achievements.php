<?php namespace UASmartHome;

require_once __DIR__ . '/../vendor/autoload.php';

use \UASmartHome\Auth\Firewall;
Firewall::instance()->restrictAccess(Firewall::ROLE_RESIDENT);

$user = \UASmartHome\Auth\User::getSessionUser();

/* Setup Twig environment. */
$twig = \UASmartHome\TwigSingleton::getInstance();

/* Initialize all of these! */
$view = new \UASmartHome\View();

$achievements = $view->getAchievements();

echo $twig->render('resident/achievements.html',
    array('user' => $user, 'achievements' => $achievements));

