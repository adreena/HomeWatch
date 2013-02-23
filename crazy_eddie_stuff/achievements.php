<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/view.php';

/* Twig configuration. */
$loader = new Twig_Loader_Filesystem(__DIR__ . '/templates');
$twig = new Twig_Environment($loader);

/* Initialize all of these! */
$model = new Model();
$controller = new Controller();
$view = new View($controller, $model);

$achievements = $view->getAchievements();

echo $twig->render('resident-achievements.html',
    array('achievements' => $achievements));

