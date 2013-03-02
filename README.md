SmartHome
=========

Smart Home Sensor Visualization Project


## Setup

I'm using the [Composer][] dependency manager to automatically track
PHP library dependencies (for example, [PHPUnit][], [Twig][], plus
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
composer install --dev
```

To install all dependencies.

[Composer]: http://getcomposer.org/doc/00-intro.md
[PHPUnit]: http://www.phpunit.de/manual/3.6/en/writing-tests-for-phpunit.html
[Twig]: http://twig.sensiolabs.org/

## PHP Tests

For now, I'm just dumping them all in the `test/` directory. Check out
`DummyTest.php` to give you the template for all of your unit tests.
Essentially, just include define a subclass of
`PHPUnit_Framework_TestCase`. To run it, you need the `phpunit`
exeucturable somewhere in your path. Then, you can simply run the test
in the command line (e.g.,  `phpunit tests/DummyTest.php`).

