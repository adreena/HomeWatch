<?php namespace UASmartHome\Database\Configuration;

require_once __DIR__ . '/../../../../vendor/autoload.php';

use \UASmartHome\Database\Connection;
use \UASmartHome\Auth\User;

class ConfigurationDB {

    public function fetchConfigData()
    {            
        $con = new Connection();
        $con = $con->connect();
        
        $user = User::getSessionUser();
        $data['functions'] = ConfigurationDB::fetchFunctions($user, $con);
        $data['constants'] = ConfigurationDB::fetchConstants($user, $con);
        $data['alerts'] = ConfigurationDB::fetchAlerts($user, $con);
        
        return $data;
    }

    public function fetchConstants($user = null, $connection = null)
    {
        if ($connection == null)
            $connection = (new Connection())->connect();
        
        $s = null;
        if ($user == null) {
            $s = $connection->prepare("SELECT Constant_ID, Name, Value, Description, NULL as User
                                       FROM Constants");
        } else {
            $s = $connection->prepare("SELECT c.Constant_ID, Name, Value, Description, uc.User_ID as User
                                       FROM Constants c LEFT JOIN User_Constants uc
                                       ON c.Constant_ID = uc.Constant_ID
                                       AND uc.User_ID = :User_ID");
            
            $s->bindParam(':User_ID', $user->getID());
        }
        
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
                'description' => $constant['Description'],
                'favorite' => $constant['User'] == null ? 0 : 1
            ));
        }

        return $constants;
    }

    public function fetchFunctions($user = null, $connection = null)
    {
        if ($connection == null)
            $connection = (new Connection())->connect();
        
        $s = null;
        if ($user == null) {
            $s = $connection->prepare("SELECT Equation_ID, Name, Value, Description, NULL as User
                                       FROM Equations");
        } else {
            $s = $connection->prepare("SELECT e.Equation_ID, Name, Value, Description, ue.User_ID as User
                                       FROM Equations e LEFT JOIN User_Equations ue
                                       ON e.Equation_ID = ue.Equation_ID
                                       AND ue.User_ID = :User_ID");
            
            $s->bindParam(':User_ID', $user->getID());
        }
        
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
                'description' => $function['Description'],
                'favorite' => $function['User'] == null ? 0 : 1
            ));
        }

        return $functions;
    }
    
    public function fetchAlerts($user = null, $connection = null)
    {
        if ($connection == null)
            $connection = (new Connection())->connect();
        
        $s = null;
        if ($user == null) {
            $s = $connection->prepare("SELECT Alert_ID, Name, Value, Description, NULL as User
                                       FROM Alerts");
        } else {
            $s = $connection->prepare("SELECT a.Alert_ID, Name, Value, Description, ua.User_ID as User
                                       FROM Alerts a LEFT JOIN User_Alerts ua
                                       ON a.Alert_ID = ua.Alert_ID
                                       AND ua.User_ID = :User_ID");
            
            $s->bindParam(':User_ID', $user->getID());
        }
        
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
                'description' => $alert['Description'],
                'favorite' => $alert['User'] == null ? 0 : 1
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
    
        public function updateFavoriteFunctions($favorites)
    {
        $user = \UASmartHome\Auth\User::getSessionUser();
        if ($user == null)
            return false;
        
        $con = new Connection();
        $con = $con->connect();
        
        try {
            $con->beginTransaction();
            
            // Delete existing favorites
            $s = $con->prepare("DELETE FROM User_Equations
                                WHERE User_ID=:User_ID");
            
            $s->bindParam(':User_ID', $user->getID());
            $s->execute();
            
            // Add new favorites
            $s = $con->prepare("INSERT INTO User_Equations (User_ID, Equation_ID)
                                VALUES (:User_ID, :Equation_ID)");
            
            $s->bindParam(':User_ID', $user->getID());
            foreach ($favorites as $equationid) {
                // TODO: it should be possible to insert all of these in one query
                $s->bindParam(':Equation_ID', $equationid);
                $s->execute();
            }
            
            $con->commit();
            return true;
        } catch (\PDOException $e) {
            trigger_error("Failed to update favorites: " . $e->getMessage(), E_USER_WARNING);
            $con->rollback();
        }
        
        return false;
    }
    
    public function updateFavoriteConstants($favorites)
    {
        $user = \UASmartHome\Auth\User::getSessionUser();
        if ($user == null)
            return false;
        
        $con = new Connection();
        $con = $con->connect();
        
        try {
            $con->beginTransaction();
            
            // Delete existing favorites
            $s = $con->prepare("DELETE FROM User_Constants
                                WHERE User_ID=:User_ID");
            
            $s->bindParam(':User_ID', $user->getID());
            $s->execute();
            
            // Add new favorites
            $s = $con->prepare("INSERT INTO User_Constants (User_ID, Constant_ID)
                                VALUES (:User_ID, :Constant_ID)");
            
            $s->bindParam(':User_ID', $user->getID());
            foreach ($favorites as $constantid) {
                // TODO: it should be possible to insert all of these in one query
                $s->bindParam(':Constant_ID', $constantid);
                $s->execute();
            }
            
            $con->commit();
            return true;
        } catch (\PDOException $e) {
            trigger_error("Failed to update favorites: " . $e->getMessage(), E_USER_WARNING);
            $con->rollback();
        }
        
        return false;
    }
    
    public function updateFavoriteAlerts($favorites)
    {
        $user = \UASmartHome\Auth\User::getSessionUser();
        if ($user == null)
            return false;
        
        $con = new Connection();
        $con = $con->connect();
        
        try {
            $con->beginTransaction();
            
            // Delete existing favorites
            $s = $con->prepare("DELETE FROM User_Alerts
                                WHERE User_ID=:User_ID");
            
            $s->bindParam(':User_ID', $user->getID());
            $s->execute();
            
            // Add new favorites
            $s = $con->prepare("INSERT INTO User_Alerts (User_ID, Alert_ID)
                                VALUES (:User_ID, :Alert_ID)");
            
            $s->bindParam(':User_ID', $user->getID());
            foreach ($favorites as $alertid) {
                // TODO: it should be possible to insert all of these in one query
                $s->bindParam(':Alert_ID', $alertid);
                $s->execute();
            }
            
            $con->commit();
            return true;
        } catch (\PDOException $e) {
            trigger_error("Failed to update favorites: " . $e->getMessage(), E_USER_WARNING);
            $con->rollback();
        }
        
        return false;
    }   

}


