<?php namespace UASmartHome\Database;

//require_once '../auth/config.php';

class DB {

private $conn;
//private static $conn ;
//Database Connections 
     public  function db_open_connection() {
  	 $this->conn = mysql_connect(DB_HOST, DB_USER, DB_PASS);
		
		if(!$this->conn)
			die('Could not connect : ' . mysql_error());
		echo 'Connected Successfully <br />';

	}
	 function db_query($table,$apt,$column,$Date,$Hour)
	{
	   //$conn=mysql_connect('localhost', 'root', '');
	    mysql_select_db(DB_NAME, $this->conn) or die( "Unable to select database");
		$sql="select * from ".$table." where Apt=".$apt."  AND Date= '".$Date."'AND hour=".$Hour."" ;
		$result = mysql_query($sql,$this->conn);
		return $result;
	}
//Insert Temp
 public function db_insert_temp ($Date,$temp){
  //Apt ,Time,Temp
  mysql_select_db("401", $this->conn) or die( "Unable to select database");
  $stmt ="INSERT INTO Temp VALUES('".$Date."',".$temp.")";
  mysql_query($stmt,$this->conn);
  }
	
    public  function db_close_connection() {
		{
			mysql_close($this->conn);
				
		}
	}

  ///
  /// Returns a new PDO connection to the database.
  /// Dies if the connection cannot be established.
  ///
  public static function OpenPDOConnection() {
    try {
      return new \PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASS);
    } catch (\PDOException $e) {
      die("Could not connect to database.");
    }
  }
}

