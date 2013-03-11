<?php

require_once __DIR__ . '/../vendor/autoload.php';

/* Setup Twig environment. */
$twig = \UASmartHome\TwigSingleton::getInstance();

/* Initialize all of these! */
$view = new \UASmartHome\View();

$score = $view->getMyScores();
$scores = $view->getAllScores();

echo $twig->render('resident/scoreboard.html',
    array('score' => $score),
	array('scores' => $scores));
