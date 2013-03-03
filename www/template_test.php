<?php

require_once __DIR__ . '/vendor/autoload.php';

$twig = TwigSingleton::getInstance();


$template = $_GET['t'];

echo $twig->render($template);
