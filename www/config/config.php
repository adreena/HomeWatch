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

/** The following are defines to setup the database.
 * The defaults here are set to the one on the DB developer's machine. */
defined('DB_TYPE') ? NULL : define('DB_TYPE', 'mysql');
defined('DB_HOST') ? NULL : define('DB_HOST', 'localhost');
defined('DB_USER') ? NULL : define('DB_USER', 'root');
defined('DB_PASS') ? NULL : define('DB_PASS', 'n342m8wu9');
defined('DB_NAME') ? NULL : define('DB_NAME', 'Smart_Condo');

/**
 * This is the absolute path to the root directory of the web project.
 * This directory is the start of all web-related stuff including all of the 
 * PHP files.
 */
define('UASMARTHOME_ROOT_DIR', __DIR__ . '/..');

//DB Connection

//try {
//    $conn = new PDO(DB_TYPE.':host='.DB_HOST.';dbname='.DB_NAME, DB_USER, DB_PASS);
//  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
//}
//catch (PDOException $e) {
//    die('Failed to Connect' . $e->getMessage());
//}

