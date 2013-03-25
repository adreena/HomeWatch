<?php

///
/// Handles a request to insert or edit an equation constant in the Constants table
///

ini_set('display_errors', 0); // Allows PHP to return response 500 on errors

require_once __DIR__ . '/../vendor/autoload.php';

use \UASmartHome\Database\EquationDB;
use \UASmartHome\Database\Constant;

// Check that the request is valid
if (!(isset($_POST['name']) && isset($_POST['value']) && isset($_POST['description']))) {
    http_response_code(400);
    die;
}

// Submit the request
$constant = new Constant();
$constant->id = isset($_POST['id']) ? $_POST['id'] : -1;
$constant->name = $_POST['name'];
$constant->value = $_POST['value'];
$constant->description = $_POST['description'];

if (!EquationDB::submitConstant($constant)) {
    http_response_code(400);
}

