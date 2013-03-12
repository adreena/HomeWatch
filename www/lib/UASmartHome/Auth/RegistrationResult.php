<?php namespace UASmartHome\Auth;

require_once __DIR__ . '/../../../vendor/autoload.php';

class RegistrationResult
{

    // Result Codes
    const CODE_OK       = 0;
    const CODE_ERROR    = 1;
    const CODE_INVALID  = 2;
    const CODE_TAKEN    = 3;
    
    // Error Types
    const ERROR_UNKNOWN = 0;
    const ERROR_SQL     = 1;
    
    private $resultCode;    // The overall registration result code (see above codes)
    private $resultCodes;   // Maps AccountData fields to registration result codes

    private $errorType; // The internal error code type
    private $errorCode; // The internal error code corresponding to the errorType

    public function __construct()
    {
        $resultCode = RegistrationResult::CODE_OK;
    }
    
    ///
    /// INTERNAL
    /// Sets the registration result code for the given AccountData field.
    ///
    public function setResultCode($field, $code, $errorType = RegistrationResult::ERROR_UNKNOWN, $errorCode = 0)
    {
        $this->resultCodes[$field] = $code;
        
        // Set the overall result from the field result
        if ($code != RegistrationResult::CODE_OK && $this->resultCode != RegistrationResult::CODE_ERROR) {
            $this->setResultCodeOverall($code, $errorType, $errorCode);
        }
    }
    
    ///
    /// Returns the registration result code for the given AccountData field.
    ///
    public function getResultCode($field)
    {
        return isset($this->resultCodes[$field]) ? $this->resultCodes[$field] : RegistrationResult::CODE_OK;
    }
    
    ///
    /// Returns the friendly result message string for the given AccountData field.
    ///
    public function getFriendlyResult($field)
    {
        $code = $this->getResultCode($field);
        switch ($code) {
            case RegistrationResult::CODE_OK:       return '';
            case RegistrationResult::CODE_INVALID:  return 'Invalid';
            case RegistrationResult::CODE_TAKEN:    return 'In use';
            default:                                return '';
        }
    }
    
    ///
    /// INTERNAL
    /// Sets the overall registration result code and errors.
    ///
    public function setResultCodeOverall($code, $errorType = RegistrationResult::ERROR_UNKNOWN, $errorCode = 0)
    {
        if ($this->resultCode == RegistrationResult::CODE_ERROR)
            return;

        $this->resultCode = $code;
        $this->errorType = $errorType;
        $this->errorCode = $errorCode;
    }
    
    ///
    /// Returns the overall registration result code.
    ///
    public function getResultCodeOverall()
    {
        return $this->resultCode;
    }
    
    ///
    /// Returns the friendly message string for the overall registration result.
    ///
    public function getFriendlyResultOverall()
    {
        switch ($this->resultCode) {
            case RegistrationResult::CODE_OK:       return '';
            case RegistrationResult::CODE_ERROR:    return "Internal error ($this->errorType:$this->errorCode)."; break;
            
            case RegistrationResult::CODE_INVALID:
                $message = 'Invalid info:';
        
                // TODO: This shouldn't be necessary. Need to know how to use twig.
                foreach ($this->resultCodes as $field => $code) {
                    if ($code == RegistrationResult::CODE_INVALID) {
                        $message .= " $field,";
                    }
                }
        
                return $message;
            
            case RegistrationResult::CODE_TAKEN:
                $message = 'Taken: ';
                
                // TODO: This shouldn't be necessary. Need to know how to use twig.
                foreach ($this->resultCodes as $field => $code) {
                    if ($code == RegistrationResult::CODE_TAKEN) {
                        $message .= " $field,";
                    }
                }
        
                return $message;
                
            default: return '';
        }
    }
    
    ///
    /// Returns whether this registration result is OK (i.e. registration was successful).
    ///
    public function getIsOK()
    {
        return $this->resultCode == RegistrationResult::CODE_OK;
    }
    
    ///
    /// Returns whether this registration result is not OK (i.e. registration was not successful).
    ///
    public function getIsBad()
    {
        return !$this->getIsOK();
    }
    
}
