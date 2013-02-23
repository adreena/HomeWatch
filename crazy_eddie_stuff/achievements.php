<?php

require_once __DIR__ . '/../vendor/autoload.php';


/* Twig configuration. */
$loader = new Twig_Loader_Filesystem(__DIR__ . '/templates');
$twig = new Twig_Environment($loader);


echo $twig->render('resident-achievements.html');


