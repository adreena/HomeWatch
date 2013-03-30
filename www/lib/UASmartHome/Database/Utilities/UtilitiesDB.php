<?php namespace UASmartHome\Database\Utilities;

require_once __DIR__ . '/../../../../vendor/autoload.php';

use \UASmartHome\Database\Connection;

class UtilitiesDB {

    public function fetchUtilityData()
    {
        $con = new Connection();
        $con = $con->connect();

        $data['utilities'] = UtilitiesDB::fetchUtilityCosts($con);

        return $data;
    }


    public function fetchUtilityCosts($connection)
    {
        if ($connection == null)
            $connection = (new Connection())->connect();

        $s = $connection->prepare("SELECT Type, Price, Start_Date, End_Date
                                   FROM Utilities_Prices");

        try {
            $s->execute();
        } catch (\PDOException $e) {
            trigger_error("Failed to fetch utility prices: " . $e->getMessage(), E_USER_WARNING);
            return null;
        }

        $utilities = array();
        while ($utility = $s->fetch(\PDO::FETCH_ASSOC)) {
            array_push($utilities, array(
                'type' => $utility['Type'],
                'price' => $utility['Price'],
                'startdate' => $utility['Start_Date'],
                'enddate' => $utility['End_Date']
            ));
        }

        return $utilities;
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

    public function submitUtility($utility)
    {
        if ($utility == null)
            return false;
        $con = new Connection();
        $con = $con->connect();

        $s = $con->prepare('INSERT INTO Utilities_Prices VALUES (:type, :price, :startdate, :enddate) ON DUPLICATE KEY UPDATE Type=:type, Price=:price, Start_Date=:startdate, End_Date=:enddate');

        $s->bindParam(':type', $utility->type);
        $s->bindParam(':price', $utility->price);
        $s->bindParam(':startdate', $utility->startdate);
        $s->bindParam(':enddate', $utility->enddate);

        try {
            $s->execute();
        } catch (\PDOException $e) {
            trigger_error("Failed to submit utility price: " . $e->getMessage(), E_USER_WARNING);
            return false;
        }

        return true;
    }

    public function deleteUtility($utility)
    {
        $con = new Connection();
        $con = $con->connect();

        $s = $con->prepare('DELETE FROM Utilities_Prices WHERE Type=:type AND Price=:price AND Start_Date=:startdate AND End_Date=:enddate');

        $s->bindParam(':type', $utility->type);
        $s->bindParam(':price', $utility->price);
        $s->bindParam(':startdate', $utility->startdate);
        $s->bindParam(':enddate', $utility->enddate);

        try {
            $s->execute();
        } catch (\PDOException $e) {
            trigger_error("Failed to delete utility cost configuration: " . $e->getMessage(), E_USER_WARNING);
            return false;
        }

        return true;
    }

}


