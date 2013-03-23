<?php namespace UASmartHome;

require_once __DIR__ . '/../vendor/autoload.php';


/* Setup Twig environment. */
$twig = \UASmartHome\TwigSingleton::getInstance();

/* Initialize all of these! */
$view = new \UASmartHome\ManagerView();

echo $twig->render('manager/home.html');

