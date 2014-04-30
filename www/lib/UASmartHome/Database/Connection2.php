<?php namespace UASmartHome\Database;

class Connection2 {


    public function Connect()
    {
        try {
            $conn= new \PDO(DB_TYPE.':host='.DB_HOST.';dbname='.DB_NAME2, DB_USER, DB_PASS,
										array(\PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
            $conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            set_time_limit(0);
        }
        catch (\PDOException $e) {
            die('Failed to Connect' . $e->getMessage());
        }
        return $conn;
    }


}

?>
