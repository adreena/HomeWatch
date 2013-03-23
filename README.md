SmartHome
=========

Smart Home Sensor Visualization Project

## Setup

**NEW**: Try `first-time-setup.sh`. The following explains what that
script does.

### PHP

We're using the [Composer][] dependency manager to automatically track
PHP library dependencies (for example, [PHPUnit][], [Twig][], plus any
other PHP libraries we may want). The link given should give you enough
information for how to setup Composer on your machine.  Essentially,
it's just:

```sh
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
```

Then in  `www/` simply:

```sh
composer install --dev
```

To install all dependencies.

[Composer]: http://getcomposer.org/doc/00-intro.md
[PHPUnit]: http://www.phpunit.de/manual/3.6/en/writing-tests-for-phpunit.html
[Twig]: http://twig.sensiolabs.org/

### JavaScript

JavaScript modules are cloned into `www/js/` using [git-submodules][].

The first time you pull, you must use:

```sh
git submodule init
git submodule update
```

...to get the latest versions of all submodules. When somebody changes
the submodule version (you can check this using `git submodule status`),
you can update again using:

```sh
git submodule update
```

[git-submodules]: http://git-scm.com/book/en/Git-Tools-Submodules

## PHP Tests

For now, I'm just dumping them all in the `test/` directory. Check out
`DummyTest.php` to give you the template for all of your unit tests.
Essentially, just include define a subclass of
`PHPUnit_Framework_TestCase`. To run it, you need the `phpunit`
exeucturable somewhere in your path. Then, you can simply run the test
in the command line (e.g.,  `phpunit tests/DummyTest.php`).


# Credits

Using the [EvalMath][] PHP class by Miles Kaufmann.

[EvalMath]:
http://www.phpclasses.org/package/2695-PHP-Safely-evaluate-mathematical-expressions.html
