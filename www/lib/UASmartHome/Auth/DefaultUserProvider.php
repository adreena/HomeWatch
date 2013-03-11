<?php namespace UASmartHome\Auth;

require_once __DIR__ . '/../../../vendor/autoload.php';

use \UASmartHome\Database\DB;

///
/// The default UserProvider.
/// Provides users from the main DB.
///
/// Password hashing provided by Anthony Ferrara's ircmaxell/password_compact:
/// https://github.com/ircmaxell/password_compat
///
class DefaultUserProvider extends UserProvider
{

    const PW_COST = 10; // CPU cost of password hashing algorithm (from 4 to 31)
        
    public function __construct()
    {
    
    }
    
    ///
    /// Returns the user with the given credentials, or null if no such user exists.
    ///
    public function fetchUser($username, $password)
    {
        // Avoid wasting time on invalid input
        if ($username == null || $password == null)
            return null;
        
        // Query the DB
        $dbh = DB::OpenPDOConnection();

        $s = $dbh->prepare("SELECT Username, PW_Hash, Role_ID
                            FROM Users
                            WHERE Username = :Username");
        
        $s->bindParam(':Username', $username);
        $s->execute();

        if ($s->errorCode() != 0)
            return null;

        // Check if the user exists
        if ($s->rowCount() != 1) //no such user exists
            return null;

        $userData = $s->fetch(\PDO::FETCH_ASSOC);
        $pwhash = $userData['PW_Hash'];
        $roleID = $userData['Role_ID'];

        // Verify the password
        if (!password_verify($password, $pwhash))
            return null;

        return new User($username, $roleID);
    }
    
    ///
    /// Attempts to register a new user with the given validated user data.
    /// $result should contain the RegistrationResult for the request.
    ///
    public function registerNewUserImpl($accountData, $result)
    {
        // Generate the password hash
        $pwhash = password_hash($accountData->password, PASSWORD_DEFAULT, ["cost" => DefaultUserProvider::PW_COST]);
        if ($pwhash == false) {
            $result->setResultCodeOverall(RegistrationResult::CODE_ERROR);
            return;
        }

        // Insert the new user into the DB
        $dbh = DB::openPDOConnection();

        $s = $dbh->prepare("INSERT INTO Users (Username, PW_Hash, Role_ID, Email)
                            VALUES (:Username, :PW_Hash, :Role_ID, :Email);");

        $s->bindParam(':Username', $accountData->username);
        $s->bindParam(':PW_Hash', $pwhash);
        $s->bindParam(':Role_ID', $accountData->roleID);
        $s->bindParam(':Email', $accountData->email);
        $s->execute();

        if ($s->errorCode() != 0) {
            $result->setResultCodeOverall(RegistrationResult::CODE_ERROR, RegistrationResult::ERROR_SQL, $s->errorCode());
            return;
        }
    }

    public function validateUsername($username, $result) {
        parent::validateUsername($username, $result);
        
        $field = AccountData::FIELD_USERNAME;
        
        // Check if the username already exists
        $dbh = DB::openPDOConnection();
        $s = $dbh->prepare("SELECT Username
                            FROM Users
                            WHERE Username = :Username");
        $s->bindParam(':Username', $username);
        $s->execute();

        if ($s->errorCode() != 0) {
            $result->setResultCode($field, RegistrationResult::CODE_ERROR, RegistrationResult::ERROR_SQL, $s->errorCode());
            return;
        }
        
        if ($s->rowCount() != 0) {
            $result->setResultCode($field, RegistrationResult::CODE_TAKEN);
            return;
        }
    }
    
    public function validateRole($roleID, $result) {
        parent::validateRole($roleID, $result);
        
        $field = AccountData::FIELD_ROLE;
        
        // Check if the username already exists
        $dbh = DB::openPDOConnection();
        $s = $dbh->prepare("SELECT Role_ID
                            FROM Roles
                            WHERE Role_ID = :Role_ID");
        $s->bindParam(':Role_ID', $roleID);
        $s->execute();

        if ($s->errorCode() != 0) {
            $result->setResultCode($field, RegistrationResult::CODE_ERROR, RegistrationResult::ERROR_SQL, $s->errorCode());
            return;
        }
        
        if ($s->rowCount() == 0) {
            $result->setResultCode($field, RegistrationResult::CODE_INVALID);
            return;
        }
    }
    
    public function validateEmail($email, $result) {
        parent::validateEmail($email, $result);
        
        $field = AccountData::FIELD_EMAIL;

        // Check if the username already exists
        $dbh = DB::openPDOConnection();
        $s = $dbh->prepare("SELECT Email
                            FROM Users
                            WHERE Email = :Email");
        $s->bindParam(':Email', $email);
        $s->execute();

        if ($s->errorCode() != 0) {
            $result->setResultCode($field, RegistrationResult::CODE_ERROR, RegistrationResult::ERROR_SQL, $s->errorCode());
            return;
        }
        
        if ($s->rowCount() != 0) {
            $result->setResultCode($field, RegistrationResult::CODE_TAKEN);
            return;
        }
    }
    
}
