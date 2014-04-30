<?php namespace UASmartHome\Auth;

require_once __DIR__ . '/../../../vendor/autoload.php';

use \UASmartHome\Database\Connection;

///
/// The default UserProvider.
/// Provides users from the main DB.
///
/// Password hashing provided by Anthony Ferrara's ircmaxell/password_compact:
/// https://github.com/ircmaxell/password_compat
///
class DefaultUserProvider extends UserProvider
{

    // TODO: this needs to be in a configuration file
    const DOMAIN = 'https://smartcondo.ca';

    const PW_COST = 10; // CPU cost of password hashing algorithm (from 4 to 31)
    
    private $connection;
    
    // TODO: Pass in a DB or DB config object so that this class can be properly tested
    public function __construct()
    {
        $con = new Connection();
        $this->connection = $con->connect();
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
        $s = $this->connection->prepare("SELECT User_ID, Username, PW_Hash, Role_ID
                                         FROM Users
                                         WHERE Username = :Username");
        
        $s->bindParam(':Username', $username);
        
        try {
            $s->execute();
        } catch (\PDOException $e) {
            trigger_error("Failed to fetch user: " . $e->getMessage(), E_USER_WARNING);
            return null;
        }

        // Check if the user exists
        if ($s->rowCount() != 1) //no such user exists
            return null;

        $userData = $s->fetch(\PDO::FETCH_ASSOC);
        $userID = $userData['User_ID'];
        $pwhash = $userData['PW_Hash'];
        $roleID = $userData['Role_ID'];

        // Verify the password
        if ($password != null && !password_verify($password, $pwhash))
            return null;

        return new User($userID, $username, $roleID);
    }
    
    ///
    /// Attempts to register a new user with the given validated user data.
    /// $result should contain the RegistrationResult for the request.
    ///
    public function registerNewUserImpl($accountData, $result)
    {
        // Generate the password hash
        $pwhash = $this->generatePasswordHash($accountData->password);
        if ($pwhash == false) {
            $result->setResultCodeOverall(RegistrationResult::CODE_ERROR);
            return false;
        }

        try {
            $this->connection->beginTransaction();
            
            $s = $this->connection->prepare("INSERT INTO Users (Username, PW_Hash, Role_ID, Email)
                                             VALUES (:Username, :PW_Hash, :Role_ID, :Email);");

            $s->bindParam(':Username', $accountData->username);
            $s->bindParam(':PW_Hash', $pwhash);
            $s->bindParam(':Role_ID', $accountData->roleID);
            $s->bindParam(':Email', $accountData->email);
            
            $s->execute();
            
            $this->registerPerRoleInfo($accountData, $result);
            
            $this->connection->commit();
            return true;
        } catch (\PDOException $e) {
            trigger_error("Failed to register new user: " . $e->getMessage(), E_USER_WARNING);
            $result->setResultCodeOverall(RegistrationResult::CODE_ERROR, RegistrationResult::ERROR_SQL, $s->errorCode());
            $this->connection->rollback();
            return false;
        }
    }
    
    private function registerPerRoleInfo($accountData, $result)
    {
        // TODO: only per-role info for residents right now
        if ($accountData->roleID != User::ROLE_RESIDENT)
            return true;
        
        $s = $this->connection->prepare("INSERT INTO Resident (Name, Username, Room_Number, Location)
                                         VALUES (:Name, :Username, :Room_Number, :Location);");

        $s->bindParam(':Name', $accountData->roleData['name']);
        $s->bindParam(':Username', $accountData->username);
        $s->bindParam(':Room_Number', $accountData->roleData['roomnumber']);
        $s->bindParam(':Location', $accountData->roleData['location']);

        $s->execute();
    }
    
    public function validateUsername($username, $result) {
        parent::validateUsername($username, $result);
        
        $field = AccountData::FIELD_USERNAME;
        
        // Check if the username already exists
        $s = $this->connection->prepare("SELECT Username
                                         FROM Users
                                         WHERE Username = :Username");
        $s->bindParam(':Username', $username);

        try {
            $s->execute();
        } catch (\PDOException $e) {
            trigger_error("Failed to validate username: " . $e->getMessage(), E_USER_WARNING);
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
        $s = $this->connection->prepare("SELECT Role_ID
                                         FROM Roles
                                         WHERE Role_ID = :Role_ID");
        $s->bindParam(':Role_ID', $roleID);

        try {
            $s->execute();
        } catch (\PDOException $e) {
            trigger_error("Failed to validate role: " . $e->getMessage(), E_USER_WARNING);
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
        $s = $this->connection->prepare("SELECT Email
                                         FROM Users
                                         WHERE Email = :Email");
        $s->bindParam(':Email', $email);
        
        try {
            $s->execute();
        } catch (\PDOException $e) {
            trigger_error("Failed to validate email: " . $e->getMessage(), E_USER_WARNING);
            $result->setResultCode($field, RegistrationResult::CODE_ERROR, RegistrationResult::ERROR_SQL, $s->errorCode());
            return;
        }
        
        if ($s->rowCount() != 0) {
            $result->setResultCode($field, RegistrationResult::CODE_TAKEN);
            return;
        }
    }
    
    public function fetchUsername($email) {
        $s = $this->connection->prepare("SELECT Username FROM Users WHERE Email = :Email");
        $s->bindParam(':Email', $email);
        
        try {
            $s->execute();        
        } catch (\PDOException $e) {
            trigger_error("Failed to fetch username: " . $e->getMessage(), E_USER_WARNING);
            return null;
        }
        
        // Check if the user exists
        if ($s->rowCount() != 1)
            return null;
        
        $userData = $s->fetch(\PDO::FETCH_ASSOC);
        $username = $userData['Username'];
        
        return $username;
    }
    
    public function sendResetToken($email) {
        $username = $this->fetchUsername($email);
        if ($username == null)
            return false;
        
        $token = $this->generateActivationToken();
        $s = $this->connection->prepare("UPDATE Users SET Reset_Token = :Token WHERE Email = :Email");
        
        $s->bindParam(':Token', $token);
        $s->bindParam(':Email', $email);
        
        try {
            $s->execute();
        } catch (\PDOException $e) {
            trigger_error("Failed to set reset token: " . $e->getMessage(), E_USER_WARNING);
            return false;
        }

        if ($s->rowCount() == 0)
            return false;

        return $this->sendResetEmail($username, $email, $token);
    }
    
    private function sendResetEmail($username, $email, $token) {
        $to = $email;
        $subject = 'HomeWatch Password Reset';
        $message = "Please go to " . self::DOMAIN . "/HomeWatch/auth/reset-password.php?username=$username&token=$token to reset your password.";
        $headers = 'From: HomeWatch <donotreply@smartcondo.ca>';

        return mail($email, $subject, $message, $headers);
    }
    
    public function resetUserPassword($username, $token, $newpassword) {
        $pwhash = $this->generatePasswordHash($newpassword);
        if ($pwhash == null)
            return false;
        
        if ($token == null)
            return false;
        
        try {
            $this->connection->beginTransaction();
            
            $s = $this->connection->prepare("UPDATE Users
                                             SET PW_Hash = :PW_Hash
                                             WHERE Username = :Username AND Reset_Token = :Token");
            $s->bindParam(":Username", $username);
            $s->bindParam(":Token", $token);
            $s->bindParam(":PW_Hash", $pwhash);
            
            $s->execute();
            if ($s->rowCount() == 0)
                return false;
            
            if (!$this->clearResetToken($username))
                return false;
            
            $this->connection->commit();
        } catch (\PDOException $e) {
            trigger_error("Failed to reset user password: " . $e->getMessage(), E_USER_WARNING);
            $this->connection->rollback();
            return false;
        }

        return true;
    }
    
    public function setUserPassword($user, $newpassword) {
		if ($user == null || $user->getID() == null)
			return false;
    	$pwhash = $this->generatePasswordHash($newpassword);
    	if ($pwhash == null)
    		return false;
    
    	try {
    		$this->connection->beginTransaction();
    
    		$s = $this->connection->prepare("UPDATE Users
                                             SET PW_Hash = :PW_Hash
                                             WHERE User_ID = :Userid");
    		$s->bindParam(":Userid", $user->getID());
    		$s->bindParam(":PW_Hash", $pwhash);
    
    		$s->execute();
    		if ($s->rowCount() == 0)
    			return false;
    
    		$this->connection->commit();
    	} catch (\PDOException $e) {
    		trigger_error("Failed to set user password: " . $e->getMessage(), E_USER_WARNING);
    		$this->connection->rollback();
    		return false;
    	}
    
    	return true;
    }
    
    private function generatePasswordHash($password) {
        return password_hash($password, PASSWORD_DEFAULT, array("cost" => DefaultUserProvider::PW_COST));
    }
    
    private function clearResetToken($username) {
        $s = $this->connection->prepare("UPDATE Users
                                         SET Reset_Token = null
                                         WHERE Username = :Username");
        $s->bindParam(":Username", $username);
        
        $s->execute();
        if ($s->rowCount() == 0)
            return false;
        
        return true;
    }
}
