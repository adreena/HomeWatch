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

        $s = $connection->prepare("SELECT Utility_ID, Type, Price, Start_Date, End_Date
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
                'id' => $utility['Utility_ID'],
                'type' => $utility['Type'],
                'price' => $utility['Price'],
                'startdate' => $utility['Start_Date'],
                'enddate' => $utility['End_Date']
            ));
        }

        return $utilities;
    }

    public function submitUtility($utility)
    {
        if ($utility == null || $utility->isValid())
            return false;
        $con = new Connection();
        $con = $con->connect();

        if ($utility->hasID()) {
            $s = $con->prepare('UPDATE Utilities_Prices
                                SET Type=:type, Price=:price, Start_Date=:startdate, End_Date=:enddate
                                WHERE Utility_ID=:Utility_ID');

            $s->bindParam(':Utility_ID', $utility->id);
        } else {
            $s = $con->prepare('INSERT INTO Utilities_Prices (Type, Price, Start_Date, End_Date)
                                VALUES (:type, :price, :startdate, :enddate)');
        }

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

    public function deleteUtility($utilityID)
    {
        $con = new Connection();
        $con = $con->connect();

        $s = $con->prepare('DELETE FROM Utilities_Prices
                            WHERE Utility_ID=:utility_id');

        $s->bindParam(':utility_id', $utilityID);

        try {
            $s->execute();
        } catch (\PDOException $e) {
            trigger_error("Failed to delete utility cost configuration: " . $e->getMessage(), E_USER_WARNING);
            return false;
        }

        return true;
    }

}


