<?php

/* Autoload all Composer-managed libraries. */
require_once __DIR__ . '/../vendor/autoload.php';

/** Just a sample test class that you can copy and derive your own unit 
 * tests. */
class DummyTestCase extends PHPUnit_Framework_TestCase {

    /** A dummy test case. One will fail and one will pass. */
    function testDummy() {
        /* Just assert true to make sure this is working. */
        $this->assertTrue(true);
        /* Ditto, but with false. THIS IS SUPPOSED TO FAIL! */
        $this->assertFalse(true);
    }

}
