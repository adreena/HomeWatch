<?php

require_once "Database.php";

///
/// A generic user.
///
/// This class also provides functions for user login and registration.
///
/// Security functions are based on:
/// (http://tinsology.net/2009/06/creating-a-secure-login-system-the-right-way/)
///
class User {

  const REG_ERROR   = -1;
  const REG_SUCCESS = 0;

  private $m_username;

  /// Constructs a user with the given user data.
  /// See FetchUser() to fetch an existing user, and RegisterNewUser() to register a new user.
  private function __construct($username) {
    $this->m_username = $username;
  }

  ///
  /// Returns this user's username.
  ///
  public function get_username() { return $this->m_username; }

  ///
  /// Causes this user to login.
  /// If another user is logged in, they are logged out and their session destroyed.
  ///
  public function login() {
    $this->logout();

    session_start();
    $_SESSION['user'] = $this;
  }

  ///
  /// Causes this user to logout, destroying its session.
  ///
  public function logout() {
    session_start();
    session_unset();
    session_destroy();
  }

  ///
  /// Returns the current user logged in for this session.
  ///
  public static function GetSessionUser() {
    session_start();
    return isset($_SESSION['user']) ? $_SESSION['user'] : null;
  }

  ///
  /// Attempts to login the user with the given credentials.
  /// If another user is logged in, they are logged out and their session destroyed.
  /// Returns the user on success, and null otherwise.
  ///
  public static function LoginUser($username, $password) {
    $user = User::FetchUser($username, $password);

    if ($user) {
      $user->Login();
    }

    return $user;
  }

  ///
  /// Causes the session user to logout, destroying the current session.
  ///
  public static function LogoutSessionUser() {
    $user = User::GetSessionUser();
    if ($user) {
      $user->logout();
    }
  }

  ///
  /// Attempts to register a new user with the given arguments.
  ///
  public static function RegisterNewUser($username, $password) {
    if(strlen($username) > 30) {
      return User::REG_ERROR;
    }

    $salt = User::GenerateSalt();
    $securepw = User::GenerateSecurePassword($password, $salt);

    $dbh = DB::OpenPDOConnection();

    $s = $dbh->prepare("INSERT INTO users (username, password, salt)
                        VALUES (:username, :securepw, :salt);");

    $s->bindParam(':username', $username);
    $s->bindParam(':securepw', $securepw);
    $s->bindParam(':salt', $salt);
    $s->execute();

    if ($s->errorCode() != 0)
      return User::REG_ERROR;

    return User::REG_SUCCESS;
  }

  ///
  /// Fetches the user with the given credentials.
  ///
  public static function FetchUser($username, $password) {
    $dbh = DB::OpenPDOConnection();

    $s = $dbh->prepare("SELECT password, salt FROM users WHERE username = :username");
    $s->bindParam(':username', $username);
    $s->execute();

    if ($s->errorCode() != 0)
      return null;

    // Check if the user exists
    if ($s->rowCount() != 1) //no such user exists
      return null;

    $userData = $s->fetch(PDO::FETCH_ASSOC);

    // Verify the password
    $securepw = User::GenerateSecurePassword($password, $userData['salt']);
    if ($securepw != $userData['password'])
      return null;

    return new User($username);
  }

  ///
  /// Generates a secure password to be stored in the DB.
  ///
  private static function GenerateSecurePassword($password, $salt) {
    return hash('sha256', $salt . hash('sha256', $password) );
  }

  ///
  /// Some spice for weak passwords.
  ///
  private static function GenerateSalt() {
    $string = md5(uniqid(rand(), true));
    return substr($string, 0, 3);
  }

}
