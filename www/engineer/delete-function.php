<?php namespace UASmartHome;

ini_set('display_errors', 0);

require_once __DIR__ . '/../vendor/autoload.php';

use \UASmartHome\Database\EquationDB;

if (!isset($_POST['id'])) {
    http_response_code(400);
} else {
    if (!EquationDB::deleteFunction($_POST['id'])) {
        http_response_code(500);
    }
}

