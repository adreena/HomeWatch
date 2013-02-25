<?php 

/* Twig configuration! Returns a twig environment. */

/* Twig configuration. */
$loader = new Twig_Loader_Filesystem(__DIR__ . '/templates');
return new Twig_Environment($loader);
