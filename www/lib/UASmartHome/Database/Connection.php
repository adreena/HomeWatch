<?php namespace UASmartHome\Database;

class Connection {
	 
	 
	 public function Connect()
	 {
	 try {
	 	$conn= new \PDO(DB_TYPE.':host='.DB_HOST.';dbname='.DB_NAME, DB_USER, DB_PASS);
	    $conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
		$a="Connected Successfully";
		//echo $a ;
	     }
	 catch (\PDOException $e) {
       die('Failed to Connect' . $e->getMessage());
	 }
	 return $conn;
	 }
	 
	 
	 }
?>
