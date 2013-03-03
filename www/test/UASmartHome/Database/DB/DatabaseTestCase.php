<?php 

// This line is terrifying, but neccesary.
require_once __DIR__ . '/../../../../vendor/autoload.php';

/**
 * An abstract class for testing database functionality. Extend this to write 
 * Database tests.
 *
 * This file is based on:
 * http://www.phpunit.de/manual/current/en/database.html#tip:-use-your-own-abstract-database-testcase
 *
 * As a side-effect, including this file autoloads all our classes.
 *
 * The connection is a singleton, so it's reused during testing.
 */
abstract class DatabaseTestCase extends PHPUnit_Extensions_Database_TestCase
{

    // only instantiate pdo once for test clean-up/fixture load
    static private $pdo = null;

    // only instantiate PHPUnit_Extensions_Database_DB_IDatabaseConnection once per test
    private $conn = null;

    /**
     * @return PHPUnit_Extensions_Database_DB_IDatabaseConnection
     */
   final public function getConnection()
    {
        if ($this->conn === null) {
            if (self::$pdo == null) {
                self::$pdo = new PDO($GLOBALS['DB_DSN'], $GLOBALS['DB_USER'], $GLOBALS['DB_PASSWD']);
            }

            $this->conn = $this->createDefaultDBConnection(self::$pdo, $GLOBALS['DB_DBNAME']);
            /* DBUnit doesn't set up the schema for us -- so do it now. */
            // I'm not terribly sure this is necessary!
            //$this->setupSchema(self::$pdo);
        }

        return $this->conn;
    }

    /**
     * Loads the schema creation SQL file and initializes the database with 
     * it. This is should be run the first time getConnection is called.
     */
    private function setupSchema($pdo) {
        $schemaLocation = __DIR__ . '/_files/schema.sql';
        $schema = file_get_contents($schemaLocation);

        $pdo->exec($schema);
    }

    /**
     * @return PHPUnit_Extensions_Database_DataSet_IDataSet
     */
    public function getDataSet()
    {
        return $this->createFlatXMLDataSet(__DIR__ . '/_files/smarthome-seed.xml');
    }

}
