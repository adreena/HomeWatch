<?php namespace UASmartHome\Database\Equation;

require_once __DIR__ . '/../../../../vendor/autoload.php';

use \UASmartHome\Database\Connection;

class EquationDB {

    public function fetchUserData()
    {
        $con = new Connection();
        $con = $con->connect();
        
        $userData['functions'] = EquationDB::fetchFunctions($con);
        $userData['constants'] = EquationDB::fetchConstants($con);
        
        return $userData;
    }
    
    public function submitFunction($function)
    {
        if ($function == null || !$function->isValid())
            return false;
        
        $con = new Connection();
        $con = $con->connect();
        
        if ($function->hasID()) {
            $s = $con->prepare('UPDATE Equations
                                SET Name=:Name, Value=:Value, Description=:Description
                                WHERE Equation_ID=:Equation_ID');
            
            $s->bindParam(':Equation_ID', $function->id);
        } else {
            $s = $con->prepare('INSERT INTO Equations (Name, Value, Description)
                                VALUES (:Name, :Value, :Description)');
        }
        $s->bindParam(':Name', $function->name);
        $s->bindParam(':Name', $function->name);
        $s->bindParam(':Value', $function->body);
        $s->bindParam(':Description', $function->description);
        $s->execute();
        
        if ($s->errorCode() != 0) {
            return false;
        }
        
        return true;
    }
    
    public function deleteFunction($functionID)
    {
        $con = new Connection();
        $con = $con->connect();
        
        $s = $con->prepare('DELETE FROM Equations
                            WHERE Equation_ID=:Equation_ID');
        
        $s->bindParam(':Equation_ID', $functionID);
        $s->execute();
        
        if ($s->errorCode() != 0) {
            return false;
        }
        
        return true;
    }
        
    public function submitConstant($constant)
    {
        if ($constant == null || !$constant->isValid())
            return false;
        
        $con = new Connection();
        $con = $con->connect();
        
        if ($constant->hasID()) {
            $s = $con->prepare('UPDATE Constants
                                SET Name=:Name, Value=:Value, Description=:Description
                                WHERE Constant_ID=:Constant_ID');
            
            $s->bindParam(':Constant_ID', $constant->id);
        } else {
            $s = $con->prepare('INSERT INTO Constants (Name, Value, Description)
                                VALUES (:Name, :Value, :Description)');
        }
        
        $s->bindParam(':Name', $constant->name);
        $s->bindParam(':Value', $constant->value);
        $s->bindParam(':Description', $constant->description);
        $s->execute();
        
        if ($s->errorCode() != 0) {
            return false;
        }
        
        return true;
    }
    
    public function deleteConstant($constantID)
    {
        $con = new Connection();
        $con = $con->connect();
        
        $s = $con->prepare('DELETE FROM Constants
                            WHERE Constant_ID=:Constant_ID');
        
        $s->bindParam(':Constant_ID', $constantID);
        $s->execute();
        
        if ($s->errorCode() != 0) {
            return false;
        }
        
        return true;
    }
    
    private function fetchConstants($connection)
    {
        $s = $connection->prepare("SELECT Constant_ID, Name, Value, Description
                                   FROM Constants");
        
        $s->execute();

        if ($s->errorCode() != 0) {
            trigger_error("Failed to fetch constants: " . $s->errorInfo(), E_USER_WARNING);
            return null;
        }

        $constants = array();        
        while ($constant = $s->fetch(\PDO::FETCH_ASSOC)) {
            array_push($constants, array(
                'id' => $constant['Constant_ID'],
                'name' => $constant['Name'],
                'value' => $constant['Value'],
                'description' => $constant['Description']
            ));
        }
        
        // TODO: fetch user constants?
        
        return $constants;
    }
    
    private function fetchFunctions($connection)
    {
        $s = $connection->prepare("SELECT Equation_ID, Name, Value, Description
                                   FROM Equations");
        
        $s->execute();

        if ($s->errorCode() != 0) {
            trigger_error("Failed to fetch functions: " . $s->errorInfo(), E_USER_WARNING);
            return null;
        }

        $functions = array();        
        while ($function = $s->fetch(\PDO::FETCH_ASSOC)) {
            array_push($functions, array(
                'id' => $function['Equation_ID'],
                'name' => $function['Name'],
                'value' => $function['Value'],
                'description' => $function['Description']
            ));
        }
        
        // TODO: fetch user functions
        
        return $functions;
    }
    
}


