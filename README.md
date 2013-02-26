SmartHome
=========

Smart Home Sensor Visualization Project


## Setup

I'm using the [Composer][] dependency manager to automatically track
PHP library dependencies (for example, [SimpleTest][], [Twig][], plus
any other PHP libraries we may want). The link given should give you
enough information for how to setup Composer on your machine.
Essentially, it's just:

```sh
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
```

Then in `bin/` or `www/` or whatever we're going to call it,
simply:

```sh
composer install
```

To install all dependencies.

[Composer]: http://getcomposer.org/doc/00-intro.md
[SimpleTest]: http://www.simpletest.org/en/first_test_tutorial.html
[Twig]: http://twig.sensiolabs.org/

## PHP Tests

For now, I'm just dumping them all in the `test/` directory. Check out
`dummy_test.php` to give you the template for all of your unit tests.
Essentially, just include the autorunner and define a subclass of
`UnitTestCase`. To run it, simply navigate to the test in your browser
or run it from the command line with PHP (e.g.,  `php
tests/some_test.php`).

