<?php

require_once __DIR__ . '/DatabaseTestCase.php';

use \UASmartHome\Database\DB;

/**
 * Test resident database methods.
 *
 * SEE:
 * http://www.phpunit.de/manual/current/en/database.html#database-assertions-api
 * FOR HOW TO WRITE TESTS FOR THIS!
 */
class ResidentDBTest extends DatabaseTestCase
{

    const RESIDENT_ID = 1;

    /** THIS IS AN EXAMPLE TEST! THIS DOES NOT REFLECT THE FINAL API! */
    function testResidentInfo()
    {
        $db = new DB();

        $info = $db->residentInfo(RESIDENT_ID);

        /* These are tentative key names! */
        $allowableKeys = array('username', 'location', 'points', 'status');

        foreach ($allowableKeys as $key) {
            $this->assertArrayHasKey('key', $info);
        }

        $this->assertArrayNotHasKey('id', $info);
    }

    function testResidentEarned()
    {
        $db = new DB();

        $earned = $db->residentEarned(RESIDENT_ID);

        /* Make sure we got back an array of achievements. */
        $this->assertType($earned, 'array');
        $this->assertType($earned[0], '\UASmartHome\Achievement');

    }

    /**
     * @depends testResidentInfo
     */
    function testResidentUpdate()
    {
        $db = new DB();
        
        /* Again, I have NO IDEA what the API will look like. */
        $updates = array('status' => 'home');
        $status = $db->residentUpdate(RESIDENT_ID, $updates);

        $this->assertTrue($status);

        $info = $db->residentInfo(RESIDENT_ID);
        $this->assertEqual($info['status'], 'home');

    }
    
}
