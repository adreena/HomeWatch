<?php

require_once __DIR__ . '/../../../vendor/autoload.php';

use \UASmarthome\Auth\AccountData;
use \UASmartHome\Auth\DefaultUserProvider;
use \UASmartHome\Auth\RegistrationResult;

///
/// WARNING: Assumes the DB is empty (should be using a test DB)
///
class UserProviderTest extends PHPUnit_Framework_TestCase
{

    /**
     * @dataProvider loginProvider
     */
    public function testFetchUser($username, $password, $exists)
    {
        $userProvider = new DefaultUserProvider();
        
        if ($exists) {
            $this->assertNotNull($userProvider->fetchUser($username, $password));
        } else {
            $this->assertNull($userProvider->fetchUser($username, $password));
        }
    }
    
    /**
     * @dataProvider registerProvider
     */
    public function testRegisterUser($accountData, $resultCode)
    {
        $userProvider = new DefaultUserProvider();
        $result = $userProvider->registerNewUser($accountData);
        $this->assertEquals($resultCode, $result->getResultCodeOverall());
    }
    
    /**
     * @dataProvider usernameProvider
     */
    public function testUsernameValidation($username, $resultCode)
    {
        $userProvider = new DefaultUserProvider();
        $result = new RegistrationResult();
        
        $userProvider->validateUsername($username, $result);
        $this->assertEquals($resultCode, $result->getResultCode(AccountData::FIELD_USERNAME));
    }
    
    /**
     * @dataProvider passwordProvider
     */
    public function testPasswordValidation($password, $resultCode)
    {
        $userProvider = new DefaultUserProvider();
        $result = new RegistrationResult();
        
        $userProvider->validatePassword($password, $result);
        $this->assertEquals($resultCode, $result->getResultCode(AccountData::FIELD_PASSWORD));
    }

    /**
     * @dataProvider roleProvider
     */
    public function testRoleValidation($role, $resultCode)
    {
        $userProvider = new DefaultUserProvider();
        $result = new RegistrationResult();
        
        $userProvider->validateRole($role, $result);
        $this->assertEquals($resultCode, $result->getResultCode(AccountData::FIELD_ROLE));
    }
    
    /**
     * @dataProvider emailProvider
     */
    public function testEmailValidation($email, $resultCode)
    {
        $userProvider = new DefaultUserProvider();
        $result = new RegistrationResult();
        
        $userProvider->validateEmail($email, $result);
        $this->assertEquals($resultCode, $result->getResultCode(AccountData::FIELD_EMAIL));
    }
    
    public function loginProvider()
    {
        $validPassword = "Pa%%w0rDs0r9";
        
        // TODO: Need a test DB...
        return array(
            array(null, null, false),
            array(null, $validPassword, false),
            array("1234test5678", null, false),
            array("1234test5678", $validPassword, false)
        );
    }
    
    public function registerProvider()
    {
        $emptyAccount = new \UASmartHome\Auth\AccountData();
    
        // TODO: Need a test DB... Also it's hard to provide new AccountData like this
        return array(
            array($emptyAccount, RegistrationResult::CODE_INVALID)
        );
    }
    
    public function usernameProvider()
    {
        return array(
            array("pylon1234", RegistrationResult::CODE_OK),
            array("mystupidlylongusername12345678", RegistrationResult::CODE_OK),
            array(null, RegistrationResult::CODE_INVALID),
            array("mystupidlylongusername123456789", RegistrationResult::CODE_INVALID)
        );
    }
    
    public function passwordProvider()
    {
        return array(
            array("password", RegistrationResult::CODE_OK),
            array("myreallyreallyreallyreallyreallyreallyreallyreallyreallyreallyreallyreallyreallyreallyreallyreallyreallyreallymyreallyreallyreallyreallyreallyreallyreallyreallyreallyreallyreallyreallyreallyreallyreallyreallyreallyreallyreallyreallyreallylongpassword12345", RegistrationResult::CODE_OK),
            array(null, RegistrationResult::CODE_INVALID),
            array("myreallyreallyreallyreallyreallyreallyreallyreallyreallyreallyreallyreallyreallyreallyreallyreallyreallyreallymyreallyreallyreallyreallyreallyreallyreallyreallyreallyreallyreallyreallyreallyreallyreallyreallyreallyreallyreallyreallyreallylongpassword123456", RegistrationResult::CODE_INVALID),
            array("test", RegistrationResult::CODE_INVALID)
            
        );
    }
    
    public function roleProvider()
    {
        // TODO: should really query the DB for these...
        return array(
            array(1, RegistrationResult::CODE_OK),
            array(2, RegistrationResult::CODE_OK),
            array(3, RegistrationResult::CODE_OK),
            array(4, RegistrationResult::CODE_OK),
            array(5, RegistrationResult::CODE_OK),
            array(null, RegistrationResult::CODE_INVALID),
            array(0, RegistrationResult::CODE_INVALID),
            array(6, RegistrationResult::CODE_INVALID),
            array("a", RegistrationResult::CODE_INVALID)
        );
    }
    
    public function emailProvider()
    {
        return array(
            array("test1234@email.com", RegistrationResult::CODE_OK),
            array("myreallylongemailaddresssthaticanneverrememberwhatitisbutiuseit4@myreallyreallyreallyreallyreallyreallyreallylongdomainname12345.com", RegistrationResult::CODE_OK),
            array(null, RegistrationResult::CODE_INVALID),
            array("test", RegistrationResult::CODE_INVALID),
            array("test@emailcom", RegistrationResult::CODE_INVALID),
            array("test@email.", RegistrationResult::CODE_INVALID),
            array("@email.com", RegistrationResult::CODE_INVALID),
            array("test@@email.com", RegistrationResult::CODE_INVALID),
            array("t@st@email.com", RegistrationResult::CODE_INVALID),
            array("t@st@email.com", RegistrationResult::CODE_INVALID)
        );
    }
    


}
