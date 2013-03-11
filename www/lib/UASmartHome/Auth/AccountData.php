<?php namespace UASmartHome\Auth;

require_once __DIR__ . '/../../../vendor/autoload.php';

class AccountData
{

    // Constraints
    const USERNAME_LENGTH_MIN = 1;
    const USERNAME_LENGTH_MAX = 30;
    const PASSWORD_LENGTH_MIN = 8;
    const PASSWORD_LENGTH_MAX = 255;
    
    // Fields
    const FIELD_USERNAME    = 'username';
    const FIELD_PASSWORD    = 'password';
    const FIELD_ROLE        = 'role';
    const FIELD_EMAIL       = 'email';
    
    public $username;
    public $roleID;
    public $email;
    
    public function __construct() { }
    
}
