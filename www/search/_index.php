<?php

/*
 * Proposed index page for searching. Nowhere near done yet.
 */

require_once __DIR__ . '/../vendor/autoload.php';

$twig = \UASmartHome\TwigSingleton::getInstance();

echo $twig->render('manager/search.html');

