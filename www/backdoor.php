<?php

/**
 * Login as any role. But you'll always be a goat.
 *
 * DELETE THIS FILE AND `lib/templates/backdoor-delete-me.html`
 * AS SOON AS THE DB and User CLASSES ARE WORKING!
 */

require_once __DIR__ . '/vendor/autoload.php';

$roles = array('resident', 'engineer', 'manager');

$role = isset($_REQUEST['role'])
      ? $_REQUEST['role']
      : '';

if (in_array($role, $roles)) {

    session_start();
    $_SESSION['user'] = array(
        'username' => 'goat',
        'role' => $role
    );

    header('Location: /');

} else {
    $twig = \UASmartHome\TwigSingleton::getInstance();
    echo $twig->render('backdoor-delete-me.html');
}

