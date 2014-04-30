<?php

///
/// Causes the session user to logout, destroying their session.
/// The user is sent back to the homepage.
///

require_once __DIR__ . '/../vendor/autoload.php';

use \UASmartHome\Auth\User;

// Logout the session user
$user = User::getSessionUser();
if ($user != null) {
    $user->logout();
}

// Direct the user back to the homepage
header("Location: /HomeWatch");

