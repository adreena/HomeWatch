<?php

// I'll admit, this is kind of much for this little bit of functionality.
require_once __DIR__ . '/../vendor/autoload.php';
use \UASmartHome\Auth\User;

User::LogoutSessionUser();

header("Location: /");

