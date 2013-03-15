<?php

require_once __DIR__ . '/../vendor/autoload.php';

/* Setup Twig environment. */
$twig = \UASmartHome\TwigSingleton::getInstance();

/* This one is "dynamic"; it doesn't need any data to be produced whilst 
 * rendering the template. */

echo $twig->render('resident/history.html');

