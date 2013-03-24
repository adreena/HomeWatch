<?php namespace UASmartHome\Database;

require_once __DIR__ . '/../../../vendor/autoload.php';

require_once "Connection.php";

class EquationDB {

    public function fetchUserData()
    {
        $con = new \Connection();
        
        $userData['functions'] = EquationDB::fetchFunctions($con);
        $userData['constants'] = EquationDB::fetchConstants($con);
        
        return $userData;
    }
    
    private function fetchConstants($connection)
    {
        $s = $connection->connect()->prepare("SELECT Name, Value, Description
                                              FROM Constants");
        
        $s->execute();

        if ($s->errorCode() != 0) {
            trigger_error("Failed to fetch constants: " . $s->errorInfo(), E_USER_WARNING);
            return null;
        }

        $constants = array();        
        while ($constant = $s->fetch(\PDO::FETCH_ASSOC)) {
            array_push($constants, $constant);
        }
        
        // TODO: fetch user constants?
        
        return $constants;
    }
    
    private function fetchFunctions($connection)
    {
        $s = $connection->connect()->prepare("SELECT Name, Value, Description
                                              FROM Equations");
        
        $s->execute();

        if ($s->errorCode() != 0) {
            trigger_error("Failed to fetch functions: " . $s->errorInfo(), E_USER_WARNING);
            return null;
        }

        $functions = array();        
        while ($function = $s->fetch(\PDO::FETCH_ASSOC)) {
            array_push($functions, $function);
        }
        
        // TODO: fetch user functions
        
        return $functions;
    }
    
}


