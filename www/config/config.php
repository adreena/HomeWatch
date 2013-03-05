<?php

/** The following are defines to setup the database.
 * The defaults here are set to the one on the DB developer's machine. */
<?php
defined('DB_TYPE') ? NULL : define('DB_TYPE', 'mysql');
defined('DB_HOST') ? NULL : define('DB_HOST', 'localhost');
defined('DB_USER') ? NULL : define('DB_USER', 'root');
defined('DB_PASS') ? NULL : define('DB_PASS', 'n342m8wu9');
defined('DB_NAME') ? NULL : define('DB_NAME', 'Smart_Condo');
//DB Connection
try {
    $conn = new PDO(DB_TYPE.':host='.DB_HOST.';dbname='.DB_NAME, DB_USER, DB_PASS);
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch (PDOException $e) {
    die('Failed to Connect' . $e->getMessage());
}
?>
