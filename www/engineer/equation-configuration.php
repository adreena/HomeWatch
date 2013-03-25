<?php

///
/// Renders the configuration page for inserting and editing new functions and constants
///

require_once __DIR__ . '/../vendor/autoload.php';

use \UASmartHome\Database\Equation\EquationDB;

$equationData = EquationDB::fetchUserData();

$twig = \UASmartHome\TwigSingleton::getInstance();
echo $twig->render('engineer/configuration.html', array(
    "equationData" => $equationData
));

