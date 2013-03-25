<?php

///
/// Handles a request to delete a function from the Equations table
///

ini_set('display_errors', 0); // Allows PHP to return response 500 on errors

require_once __DIR__ . '/../vendor/autoload.php';

use \UASmartHome\Database\Equation\EquationDB;

// Check that the request is valid
if (!isset($_POST['id'])) {
    http_response_code(400);
    die;
}

// Perform the deletion
if (!EquationDB::deleteFunction($_POST['id'])) {
    http_response_code(500);
}

