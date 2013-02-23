<?php

require_once __DIR__ . '/../vendor/autoload.php';

/* Twig configuration. */
$loader = new Twig_Loader_Filesystem(__DIR__ . '/templates');
$twig = new Twig_Environment($loader);

/* This one is "dynamic"; it doesn't need any data to be produced whilst 
 * rendering the template. */

echo $twig->render('resident-scoreboard.html');

