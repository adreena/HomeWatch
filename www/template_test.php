<?php

/**
 * Template tester!
 * Simply pass the name of the path of the template via the 
 * 't' get variable and render your template!
 *
 * e.g.,
 * /template_test.php?t=manager/home.html
 *
 * You can also use the page itself to select from most templates.
 *
 */

require_once __DIR__ . '/vendor/autoload.php';

$twig = TwigSingleton::getInstance();

$template = '';
$args = array();

if (isset($_GET['t'])) {
    // Simply use the GET as the template.
    $template = $_GET['t'];

} else {

    // Else find all templates in the following dirs.
    // And present them as a list to whoever.

    $prefix = 'lib/templates';
    $dirs = array('', 'resident/', 'manager/', 'engineer/');

    $templates = array_reduce($dirs, function ($templates, $dir) use ($prefix) {

        $moreTemplates = glob("$prefix/$dir*.html");

        // Strip the prefix from the results.
        $withoutPrefix = array_map(function ($path) use ($prefix) {
            return substr($path, strlen($prefix), strlen($path));
        }, $moreTemplates);

        return array_merge($templates, $withoutPrefix);

    }, array());

    $template = 'temptest.html';
    $args = array('templates' => $templates);

}


echo $twig->render($template, $args);

