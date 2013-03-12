<?php namespace UASmartHome\Auth;

require_once __DIR__ . '/../../../vendor/autoload.php';

///
/// A generic user.
///
/// Overview:
/// - Fetch or register users with an IUserProvider
///   (ex. see DefaultUserProvider).
///
/// - Get the session user with User::getSessionUser()
///   (Equivalent to $_SESSION['user'])
///
/// - Set the session by logging a user in ($user->login())
///   There can be only one user logged in per machine (as stored in the session).
///
/// - Logging out the session user destroys their session.
///
class User
{

    // Roles
    // WARNING: These should match with the values in the Roles table in the DB
    const ROLE_DEV      = 1;
    const ROLE_ADMIN    = 2;
    const ROLE_MANAGER  = 3;
    const ROLE_ENGINEER = 4;
    const ROLE_RESIDENT = 5;
    
    private $username;
    private $roleID; // Only one role per user right now for simplicity

    ///
    /// Constructs a user with the given user data.
    ///
    /// NOTE: Do not create users arbitrarily. Fetch and register users with an IUserProvider.
    ///
    public function __construct($username, $roleID)
    {
        $this->username = $username;
        $this->roleID = $roleID;
    }

    ///
    /// Returns this user's username.
    ///
    public function getUsername()
    {
        return $this->username;
    }

    ///
    /// Returns this user's primary role ID.
    ///
    public function getRoleID()
    {
        return $this->roleID;
    }
    
    ///
    /// Causes this user to login and become the session user.
    /// If another user is logged in, they are logged out.
    ///
    public function login()
    {
        // Do nothing if already logged in
        if ($this->isLoggedIn())
            return;
        
        // Logout the session user, if any
        $sessionUser = User::getSessionUser();
        if ($sessionUser != null) {
            $sessionUser->logout();
        }

        // Become the session user
        $_SESSION['user'] = $this;
    }

    ///
    /// Causes this user to logout, destroying their session.
    /// Returns true if this user was logged in and false otherwise.
    ///
    public function logout()
    {
        // Do nothing if not logged in
        if (!$this->isLoggedIn())
            return false;
        
        User::destroySession();

        return true;
    }

    ///
    /// Returns whether this user is currently logged in (i.e. whether this user is the session user).
    ///
    public function isLoggedIn()
    {
        $sessionUser = User::getSessionUser();
        if ($sessionUser == null)
            return false;
        
        return $this === $sessionUser;
    }
    
    ///
    /// Returns the user logged in for this session.
    ///
    public static function getSessionUser()
    {
        @session_start();
        $user = isset($_SESSION['user']) ? $_SESSION['user'] : null;

        // Ensure the user is valid before returning it.
        if ($user !== null && !($user instanceof User)) {
            trigger_error("Invalid session user. Clearing session...", E_USER_WARNING);
            User::destroySession();
            return null;
        }

        return $user;
    }

    ///
    /// Destroys the session.
    ///
    private static function destroySession()
    {
        @session_start();
        session_unset();
        session_destroy();
    }
    
}
