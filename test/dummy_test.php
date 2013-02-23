<?php

/* Autoload all Composer-managed libraries. */
require_once __DIR__ . '/../vendor/autoload.php';
/* Autorun the test case. */
require_once __DIR__ . '/../vendor/vierbergenlars/simpletest/autorun.php';

/** Just a sample test class that you can copy and derive your own unit 
 * tests. */
class DummyTestCase extends UnitTestCase {

    /** A dummy test case. One will fail and one will pass. */
    function testDummy() {
        /* Just assert true to make sure this is working. */
        $this->assertTrue(true);
        /* Ditto, but with false. */
        $this->assertFalse(true);
    }

}
