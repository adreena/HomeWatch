<?php

///
/// Handles a request to insert or edit a function in the Equations table
///

ini_set('display_errors', 0); // Allows PHP to return response 500 on errors

require_once __DIR__ . '/../vendor/autoload.php';

use \UASmartHome\Auth\Firewall;
Firewall::instance()->restrictAccess(Firewall::ROLE_ENGINEER);

use \UASmartHome\Database\Configuration\ConfigurationDB;
use \UASmartHome\Database\Configuration\Equation;

// Check that the request is valid
if (!(isset($_POST['name']) && isset($_POST['value']) && isset($_POST['description']))) {
    http_response_code(400);
    die;
}

function input_error($msg) {
	http_response_code(400);
	echo $msg;
	exit();
}
// Submit the request
$equation = new Equation();
$equation->id = isset($_POST['id']) ? $_POST['id'] : -1;
$equation->name = $_POST['name'];
$equation->body = $_POST['value'];
$equation->description = $_POST['description'];
if (!isset($_POST['data_type']) || ($_POST['data_type'] == ''))
	input_error('Please select data type.');
if ($_POST['data_type'] == 'data_type_new') {
	if (!isset($_POST['data_type_new_name']) || !isset($_POST['data_type_new_unit']))
		input_error("Please enter the name and unit for the new type.");
	$type_name = trim($_POST['data_type_new_name']);
	$type_unit = trim($_POST['data_type_new_unit']);
	if ($type_name == "" || $type_unit == "")
		input_error("Please enter the name and unit for the new type.");

	list($newTypeId, $error) = ConfigurationDB::insertNewDataType($type_name, $type_unit);
	if ($error === null)
		$equation->data_type = $newTypeId;
	else
		input_error($error);
}
else
	$equation->data_type = $_POST['data_type'];

if (!ConfigurationDB::submitFunction($equation)) {
    http_response_code(400);
}

