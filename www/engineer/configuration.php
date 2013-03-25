<?php namespace UASmartHome;

require_once __DIR__ . '/../vendor/autoload.php';

use \UASmartHome\Database\EquationDB;

/*
use \UASmartHome\Database\Equation;
use \UASmartHome\Database\Constant;

echo "SET? " . var_dump($_POST);

if (isset($_POST['submit-function'])) {
    $equation = new Equation();
    $equation->id = $_POST['id'];
    $equation->name = $_POST['name'];
    $equation->body = $_POST['value'];
    $equation->description = $_POST['description'];
    
    EquationDB::submitFunction($equation);
} else if (isset($_POST['submit-constant'])) {
    $constant = new Constant();
    $constant->id = $_POST['id'];
    $constant->name = $_POST['name'];
    $constant->value = $_POST['value'];
    $constant->description = $_POST['description'];
    
    EquationDB::submitConstant($constant);
} else if (isset($_POST['delete-function'])) {
    EquationDB::deleteFunction($_POST['id']);
} else if (isset($_POST['delete-constant'])) {
    EquationDB::deleteConstant($_POST['id']);
}
*/

$equationData = EquationDB::fetchUserData();

$twig = \UASmartHome\TwigSingleton::getInstance();
echo $twig->render('engineer/configuration.html', array(
    "equationData" => $equationData
));

