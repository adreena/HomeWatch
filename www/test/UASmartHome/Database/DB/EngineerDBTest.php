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
class EngineerDBTest extends DatabaseTestCase
{

    const APT_ID = 1;

    /** THIS IS AN EXAMPLE TEST! THIS DOES NOT REFLECT THE FINAL API! */
    function testPeriodAverageSingleApartment()
    {
        $db = new DB();

        $table = 'heating';
        $start = '2012-02-27';
        $end = '2013-02-27';
        $granularity = DB::MONTHLY;
        $averages = $db->periodAverage($table, APT_ID, $start, $end, $granularity);

        $this->assertArrayHasKey('Total_Energy', $info);
    }

}
