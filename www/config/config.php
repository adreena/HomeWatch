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
defined('DB_NAME2') ? NULL : define('DB_NAME2', 'Smart_Bas');

//New DB
/*
defined('DB_HOST') ? NULL : define('DB_HOST', 'hypatia.cs.ualberta.ca');
defined('DB_USER') ? NULL : define('DB_USER', 'aghoneim');
defined('DB_PASS') ? NULL : define('DB_PASS', 'usy7f5zW');
defined('DB_NAME') ? NULL : define('DB_NAME', 'smarthome_apts');
defined('DB_NAME2') ? NULL : define('DB_NAME2', 'smarthome_bas');
*/
/**
 * This is the absolute path to the root directory of the web project.
 * This directory is the start of all web-related stuff including all of the 
 * PHP files.
 */
define('UASMARTHOME_ROOT_DIR', __DIR__ . '/..');

