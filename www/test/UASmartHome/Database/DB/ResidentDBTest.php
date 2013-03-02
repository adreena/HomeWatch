<?php

require_once __DIR__ . '/DatabaseTestCase.php';

use \UASmartHome\Database\DB;

/**
 * Test resident database methods.
 *
 * SEE:
 * http://www.phpunit.de/manual/current/en/database.html#database-assertions-api
 *
 * FOR HOW TO WRITE TESTS FOR THIS!
 */
class ResidentDBTest extends DatabaseTestCase
{

    const RESIDENT_ID = 1;

    /** THIS IS AN EXAMPLE TEST! THIS DOES NOT REFLECT THE FINAL API! */
    function testResidentInfo()
    {
        $db = new DB();

        $info = $db->getResidentInfo(RESIDENT_ID);

        /* These are tentative key names! */
        $allowableKeys = array('username', 'location', 'points', 'status');

        foreach ($allowableKeys as $key) {
            $this->assertArrayHasKey('key', $info);
        }

        $this->assertArrayNotHasKey('id', $info);
    }
    
}
