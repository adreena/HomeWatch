<?php

require_once __DIR__ . "/../vendor/autoload.php";

$twig = \UASmartHome\TwigSingleton::getInstance();
echo $twig->render('forgot-password.html');

