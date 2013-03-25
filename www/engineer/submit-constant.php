<?php namespace UASmartHome;

ini_set('display_errors', 0);

require_once __DIR__ . '/../vendor/autoload.php';

use \UASmartHome\Database\EquationDB;
use \UASmartHome\Database\Constant;

if (!(isset($_POST['name']) && isset($_POST['value']) && isset($_POST['description']))) {
    http_response_code(400);
} else {
    $constant = new Constant();
    $constant->id = isset($_POST['id']) ? $_POST['id'] : -1;
    $constant->name = $_POST['name'];
    $constant->value = $_POST['value'];
    $constant->description = $_POST['description'];

    if (!EquationDB::submitConstant($constant)) {
        http_response_code(500);
    }
}
