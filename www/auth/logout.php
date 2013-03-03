<?php

require_once __DIR__ . '/../vendor/autoload.php';
use \UASmartHome\Auth\User;

/*
 * Okay, so this is disgusting right now.
 * Basically, once the database is up and running we can forget about all of 
 * this checking instanceof stuff and *just* use the static methods of the User 
 * class.
 */

$user = User::getSessionUser();

// My stupid hack makes the user not an instance of the User.
if ($user instanceof User) {
    User::LogoutSessionUser();
} else {
    session_unset();
    session_destroy();
}

header("Location: /");

