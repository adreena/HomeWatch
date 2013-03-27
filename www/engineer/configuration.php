<?php

///
/// Renders the configuration page for inserting and editing new functions and constants
///

require_once __DIR__ . '/../vendor/autoload.php';

use \UASmartHome\Database\Equation\EquationDB;
use \UASmartHome\EquationParser;

$equationData = EquationDB::fetchUserData();

$twig = \UASmartHome\TwigSingleton::getInstance();
echo $twig->render('engineer/configuration.html', array(
    "equationData" => $equationData/*,
    // STEVEN, IS THIS WHAT YOU WANTED?
    "sensors" => EquationParser::$DBVARS*/
));

