<?php namespace UASmartHome\Database\Configuration;

require_once __DIR__ . '/../../../../vendor/autoload.php';

use \UASmartHome\Database\Connection;

class ConfigurationDB {

    public function fetchConfigData()
    {
        $con = new Connection();
        $con = $con->connect();
        
        $data['functions'] = ConfigurationDB::fetchFunctions($con);
        $data['constants'] = ConfigurationDB::fetchConstants($con);
        $data['alerts'] = ConfigurationDB::fetchAlerts($con);
        
        return $data;
    }
    
    public function fetchConstants($connection)
    {
        if ($connection == null)
            $connection = (new Connection())->connect();
        
        $s = $connection->prepare("SELECT Constant_ID, Name, Value, Description
                                   FROM Constants");
        
        try {
            $s->execute();
        } catch (\PDOException $e) {
            trigger_error("Failed to fetch constants: " . $e->getMessage(), E_USER_WARNING);
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
    
    public function fetchFunctions($connection)
    {
        if ($connection == null)
            $connection = (new Connection())->connect();
        
        $s = $connection->prepare("SELECT Equation_ID, Name, Value, Description
                                   FROM Equations");
        
        try {
            $s->execute();
        } catch (\PDOException $e) {
            trigger_error("Failed to fetch functions: " . $e->getMessage(), E_USER_WARNING);
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
    
    public function fetchAlerts($connection)
    {
        if ($connection == null)
            $connection = (new Connection())->connect();
        
        $s = $connection->prepare("SELECT Alert_ID, Name, Value, Description
                                   FROM Alerts");
        
        try {
            $s->execute();
        } catch (\PDOException $e) {
            trigger_error("Failed to fetch alerts: " . $e->getMessage(), E_USER_WARNING);
            return null;
        }

        $alerts = array();
        while ($alert = $s->fetch(\PDO::FETCH_ASSOC)) {
            array_push($alerts, array(
                'id' => $alert['Alert_ID'],
                'name' => $alert['Name'],
                'value' => $alert['Value'],
                'description' => $alert['Description']
            ));
        }
        
        return $alerts;
    }

    public function fetchAlert($alert_name)
    {
        $con = new Connection();
        $con = $con->connect();
        $s = $con->prepare("SELECT Alert_ID, Name, Value, Description
                                   FROM Alerts WHERE Name=:Alert_Name");

        $s->bindParam(':Alert_Name', $alert_name);

        try {
            $s->execute();
        } catch (\PDOException $e) {
            trigger_error("Failed to fetch function: " . $e->getMessage(), E_USER_WARNING);
            return null;
        }

        $alert = $s->fetch(\PDO::FETCH_ASSOC);

        return $alert;
    }

    public function fetchFunction($function_name)
    {
        $con = new Connection();
        $con = $con->connect();
        $s = $con->prepare("SELECT Equation_ID, Name, Value, Description
                                   FROM Equations WHERE Name=:Equation_Name");

        $s->bindParam(':Equation_Name', $function_name);

        try {
            $s->execute();
        } catch (\PDOException $e) {
            trigger_error("Failed to fetch function: " . $e->getMessage(), E_USER_WARNING);
            return null;
        }

        $function = $s->fetch(\PDO::FETCH_ASSOC);

        return $function;
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
        
        try {
            $s->execute();
        } catch (\PDOException $e) {
            trigger_error("Failed to submit function: " . $e->getMessage(), E_USER_WARNING);
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
        
        try {
            $s->execute();
        } catch (\PDOException $e) {
            trigger_error("Failed to delete function: " . $e->getMessage(), E_USER_WARNING);
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
        
        try {
            $s->execute();
        } catch (\PDOException $e) {
            trigger_error("Failed to submit constant: " . $e->getMessage(), E_USER_WARNING);
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

        try {
            $s->execute();
        } catch (\PDOException $e) {
            trigger_error("Failed to delete constant: " . $e->getMessage(), E_USER_WARNING);
            return false;
        }
        
        return true;
    }
    
    public function submitAlert($alert)
    {
        if ($alert == null || !$alert->isValid())
            return false;
        
        $con = new Connection();
        $con = $con->connect();
        
        if ($alert->hasID()) {
            $s = $con->prepare('UPDATE Alerts
                                SET Name=:Name, Value=:Value, Description=:Description
                                WHERE Alert_ID=:Alert_ID');
            
            $s->bindParam(':Alert_ID', $alert->id);
        } else {
            $s = $con->prepare('INSERT INTO Alerts (Name, Value, Description)
                                VALUES (:Name, :Value, :Description)');
        }
        
        $s->bindParam(':Name', $alert->name);
        $s->bindParam(':Value', $alert->value);
        $s->bindParam(':Description', $alert->description);

        try {
            $s->execute();
        } catch (\PDOException $e) {
            trigger_error("Failed to submit alert: " . $e->getMessage(), E_USER_WARNING);
            return false;
        }
        
        return true;
    }
    
    public function deleteAlert($alertID)
    {
        $con = new Connection();
        $con = $con->connect();
        
        $s = $con->prepare('DELETE FROM Alerts
                            WHERE Alert_ID=:Alert_ID');
        
        $s->bindParam(':Alert_ID', $alertID);

        try {
            $s->execute();
        } catch (\PDOException $e) {
            trigger_error("Failed to submit alert: " . $e->getMessage(), E_USER_WARNING);
            return false;
        }
        
        return true;
    }
    
}


