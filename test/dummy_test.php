<?php

/* Autoload all Composer-managed libraries. */
require_once dirname(__FILE__) . '/../vendor/autoload.php';
/* Autorun the test case. */
require_once dirname(__FILE__) . '/../vendor/vierbergenlars/simpletest/autorun.php';

class DummyTestCase extends UnitTestCase {

  function testDummy() {
    /* Just assert true to make sure this is working. */
    $this->assertTrue(true);
    /* Ditto, but with false. */
    $this->assertFalse(true);
  }

}
