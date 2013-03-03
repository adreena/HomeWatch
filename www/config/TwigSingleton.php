<?php

/**
 * Twig singleton!
 *
 * Holds the key to Twig environment. Just call
 * TwigSingleton::getInstance()!
 */

class TwigSingleton
{
    

    /** The fabled Twig instance. */
    private static $twig = null;

    /* Set up Twig for the very first time. */
    private static function setupTwig()
    {
        /* The location of the templates is pretty dang hard-coded here. */
        $templateLocation = __DIR__ . '/../lib/templates';

        /* Twig configuration. */
        $loader = new Twig_Loader_Filesystem($templateLocation);
        self::$twig = new Twig_Environment($loader);
    }

    /* Get the singleton Twig instance. */
    static function getInstance()
    {
        if (self::$twig == null) {
            self::setupTwig();
        }

        return self::$twig;
    }

}
