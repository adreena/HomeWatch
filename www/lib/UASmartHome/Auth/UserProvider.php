<?php namespace UASmartHome\Auth;

require_once __DIR__ . '/../../../vendor/autoload.php';

///
/// A provider (registrar) of users.
///
/// Overview:
/// - Fetch existing user with fetchUser($username, $password)
/// - Register new users with registerNewUser($accountData, $password)
///
abstract class UserProvider
{
    
    public function __construct()
    {
    
    }
    
    ///
    /// Returns the user with the given credentials, or null if no such user exists.
    ///
    abstract public function fetchUser($username, $password);
    
    ///
    /// Attempts to register a new user with the given validated user data.
    /// $result should contain the RegistrationResult for the request.
    ///
    abstract public function registerNewUserImpl($accountData, $result);
    
    abstract public function sendResetToken($email);
    
    abstract public function resetUserPassword($user, $token, $newpassword);
    
    ///
    /// Returns a random string suitable as an activation or reset token
    ///
    public function generateActivationToken() {
        return md5(microtime());
    }
    
    ///
    /// Attempts to register a new user with the given non-validated data.
    /// The account data is validated before registration.
    /// Returns the RegistrationResult for the request.
    ///
    final public function registerNewUser($accountData) {
        $result = new RegistrationResult($accountData);
        
        $this->validateAccountData($accountData, $result);
        if ($result->getIsOK()) {
            $this->registerNewUserImpl($accountData, $result);
        }
        
        return $result;
    }

    final public function validateAccountData($accountData, $result)
    {
        $this->validateUsername($accountData->username, $result);
        $this->validatePassword($accountData->password, $result);
        $this->validateRole($accountData->roleID, $result);
        $this->validateEmail($accountData->email, $result);
    }
    
    public function validateUsername($username, $result) {
        $field = AccountData::FIELD_USERNAME;
        $resultCode = $result->getResultCode($field);
        
        $this->checkNotNull($username, $resultCode) &&
        $this->checkLength($username, $resultCode, AccountData::USERNAME_LENGTH_MIN, AccountData::USERNAME_LENGTH_MAX);
        
        $result->setResultCode($field, $resultCode);
    }
    
    public function validatePassword($password, $result) {
        $field = AccountData::FIELD_PASSWORD;
        $resultCode = $result->getResultCode($field);
        
        $this->checkNotNull($password, $resultCode) &&
        $this->checkLength($password, $resultCode, AccountData::PASSWORD_LENGTH_MIN, AccountData::PASSWORD_LENGTH_MAX);
        
        $result->setResultCode($field, $resultCode);
    }
    
    public function validateRole($roleID, $result) {
        $field = AccountData::FIELD_ROLE;
        $resultCode = $result->getResultCode($field);
        
        $this->checkNotNull($roleID, $resultCode);
        
        $result->setResultCode($field, $resultCode);
    }
    
    public function validateEmail($email, $result) {
        $field = AccountData::FIELD_EMAIL;
        $resultCode = $result->getResultCode($field);
        
        $this->checkNotNull($email, $resultCode);
        
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $resultCode = RegistrationResult::CODE_INVALID;
        }
        
        $result->setResultCode($field, $resultCode);
    }
    
    private function checkNotNull($fieldValue, &$code) {
        if ($fieldValue == null) {
            $code = RegistrationResult::CODE_INVALID;
            return false;
        }
        
        return true;
    }
    
    private function checkLength($fieldValue, &$code, $minLength, $maxLength) {
        $length = mb_strlen($fieldValue, 'utf8');
        if ($length < $minLength || $length > $maxLength) {
            $code = RegistrationResult::CODE_INVALID;
            return false;
        }
        
        return true;
    }

}
