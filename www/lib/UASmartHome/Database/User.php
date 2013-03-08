<?php
include 'config.php';
class User_DB {


	function User_Login($Username)
	{
	           
			   
			   $result =array();
		        $sql=$GLOBALS['conn']->prepare("select Password from Users where Username= :US ") ;
				$sql->bindValue(":US",$Username);
				$sql->execute();
				$row_count= $sql->rowCount();
				$row = $sql->fetch(PDO::FETCH_OBJ);
				$result[]=(array)$row;
				$a= $sql->rowCount();
				return $result;
	}
	
	
	
	

 public function User_Role($ID){
 
				$result =array();
		        $sql=$GLOBALS['conn']->prepare("select Name,Username,Role from User_Role where ID= :ID ") ;
				$sql->bindValue(":ID",$ID);
				$sql->execute();
				$row_count= $sql->rowCount();
				$row = $sql->fetch(PDO::FETCH_OBJ);
				$result[]=(array)$row;
				return $result;
	
}
}
// Example code
// Test Login
$testdb=new User_DB ();
// Test Return Password to controller
echo "Test Returning the Password of given Username";
echo "<br>";
$row=$testdb->User_Login('aghoneim');
print_r ($row);
//echo count ($row);

?>