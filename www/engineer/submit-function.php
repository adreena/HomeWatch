<?php namespace UASmartHome;


require_once __DIR__ . '/../vendor/autoload.php';

use \UASmartHome\Database\EquationDB;
use \UASmartHome\Database\Equation;

if (!(isset($_POST['name']) && isset($_POST['value']) && isset($_POST['description']))) {
    http_response_code(400);
} else {
    $equation = new Equation();
    $equation->id = isset($_POST['id']) ? $_POST['id'] : -1;
    $equation->name = $_POST['name'];
    $equation->body = $_POST['value'];
    $equation->description = $_POST['description'];

    if (!EquationDB::submitFunction($equation)) {
        http_response_code(500);
    }
}
