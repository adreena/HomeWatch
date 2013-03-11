<?php namespace UASmartHome\Auth;

require_once __DIR__ . '/../../../vendor/autoload.php';

///
/// Restricts access to pages.
///
/// EX:
/// >> use \UASmartHome\Auth\Firewall;
/// >> Firewall::instance()->restrictAccess(Firewall::ROLE_MANAGER, Firewall::ROLE_ENGINEER);
///
class Firewall
{

    // Roles (for convenience)
    const ROLE_DEV      = User::ROLE_DEV;
    const ROLE_ADMIN    = User::ROLE_ADMIN;
    const ROLE_MANAGER  = User::ROLE_MANAGER;
    const ROLE_ENGINEER = User::ROLE_ENGINEER;
    const ROLE_RESIDENT = User::ROLE_RESIDENT;
    
    private function __construct()
    {
    
    }
    
    ///
    /// Singleton for now.
    ///
    public function instance()
    {
        static $instance = null;
        if ($instance === null) {
            $instance = new Firewall();
        }
        
        return $instance;
    }

    ///
    /// Restrict access to the current page to users with any of the given roles.
    /// (ex. Firewall::RestrictAccess(Firewall::ROLE_MANAGER, Firewall::ROLE_ENGINEER); )
    ///
    /// WARNING: Potentially insecure: http://stackoverflow.com/questions/5121766/can-a-user-alter-the-value-of-session-in-php
    ///    
    public function restrictAccess()
    {
        $args = func_get_args();
     
        // Get the session user. Block if no user is logged in.
        $sessionUser = User::getSessionuser();
        if (!$sessionUser) {
            $this->BlockAccess();
            return;
        }

        $role = $sessionUser->getRoleID();
        
        // Dev's have access to all pages during development
        // TODO: Guard with if (ISDEVMODE) 
        if ($role == Firewall::ROLE_DEV) {
            return;
        }
        
        // Admin's have access to all pages
        // TODO: May want to restrict this...
        if ($role == Firewall::ROLE_ADMIN) {
            return;
        }
        
        if (!in_array($role, $args)) {
            $this->BlockAccess();
            return;
        }
    }
    
    ///
    /// Blocks access to the current page.
    ///
    private function BlockAccess()
    {
        header('location: /');
    }
    
}
