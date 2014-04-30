<?php

/**
 * UASmartHome configuration file.
 *
 * This file is automatically included by everything that makes uses of the 
 * Composer-generated autoloader.
 *
 * This file is primarly defines configuration options for the database, but 
 * also provides some useful project-wide defines.
 */

/**
 * This is the absolute path to the root directory of the web project.
 * This directory is the start of all web-related stuff including all of the 
 * PHP files.
 */
define('UASMARTHOME_ROOT_DIR', __DIR__ . '/..');
date_default_timezone_set("America/Edmonton");

$localConfig = UASMARTHOME_ROOT_DIR . "/config/config.local.php";

if (!file_exists($localConfig)) {
	print "Local config (www/config/config.local.php) missing!";
	die;
}

require_once($localConfig);

if (defined('CUSTOM_LOG'))
	require_once("CustomLogging.php")
?>
