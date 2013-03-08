<?php
include 'config.php';

class Resident_DB {


   public function Resident_DB_Read($resident_id)
	{
			   $result =array();
		       $Query=$GLOBALS['conn']->prepare("select Name ,Username,Room_Number ,Location,
			   Points,Room_Status from Resident where Resident_ID=:Res_ID") ;
			   $Query->bindValue(":Res_ID",$resident_id);
			   $Query->execute();
			   $row = $Query->fetch(PDO::FETCH_OBJ);
			   $result=(array)$row;
			   $a= $Query->rowCount();
			   echo $a;
			   echo "<br \>";
				return $result;
	}

	
	public function Resident_DB_Update ($resident_id,$Room_Status,$Name,$Username)
	{ //update set where
	
	 $Query=$GLOBALS['conn']->prepare("update Resident  
		set Room_Status= :RS , Name= :NM ,	Username= :US where Resident_ID= :Res_ID") ;
		$Query->bindValue(":Res_ID",$resident_id);
		$Query->bindValue(":RS",$Room_Status);
		$Query->bindValue(":NM",$Name);
		$Query->bindValue(":US",$Username);
		$Query->execute();
	}
	
	public 	function Resident_DB_Achievement ()
	{
			   $result =array();
		       $Query=$GLOBALS['conn']->prepare("select `Name`, `Description`, `Enabled_Icon`, `Disabled_Icon`, `Points` from Achievements ") ;
			   $Query->execute();
				while ($row = $Query->fetch(PDO::FETCH_OBJ))
				{
				$result[]=(array)$row;
				}
				return $result;
	}
	public 	function Resident_DB_Earned_Achievement ($resident_id)
	{
			   $result =array();
		       $Query=$GLOBALS['conn']->prepare("select * from `Earned_Achievements` where Resident_ID= :Res_ID") ;
				$Query->bindValue(":Res_ID",$resident_id);
				$Query->execute();
				while ($row = $Query->fetch(PDO::FETCH_OBJ))
				{
				$result[]=(array)$row;
				}
				return $result;
	}
	
	public 	function Resident_DB_Building ($resident_id)
	{
			    $result =array();
		        $Query=$GLOBALS['conn']->prepare("select * from Building where Resident_ID= :Res_ID") ;
				$Query->bindValue(":Res_ID",$resident_id);
				$Query->execute();
				$row = $Query->fetch(PDO::FETCH_OBJ);
				$result=(array)$row;
				$row_count= $Query->rowCount();
				$a= $Query->rowCount();
				return $result;
	}
	
	
}

// Example code
$testdb=new Resident_DB();
//Test Resident Read
echo "Test Resident Read : By Passing the Resident ID::";
echo "<br>";
$a0=$testdb->Resident_DB_Read(1);
print_r($a0);
echo "<br>";
echo "===========================";
echo "<br>";
//Test Resident Update
echo "Test Resident Update : By Passing the Resident Id ,Room Status,Name,Username::";
echo "<br>";
$a1=$testdb->Resident_DB_Update (1,'TEST','TEST','TEST');
$a0=$testdb->Resident_DB_Read(1);
print_r($a0);
echo "<br>";
echo "===========================";
echo "<br>";
//Test READ ALL achievements 
echo "Test Read Achievements ::";
echo "<br>";
$a3=$testdb->Resident_DB_Achievement() ;
print_r($a3);
echo "<br>";
echo "===========================";
echo "<br>";
//Test Read Earned Achievments
echo "Test Earned Achievments by Passing Resident ID::";
echo "<br>";
$a4=$testdb->Resident_DB_Earned_Achievement(1);
print_r($a4);
echo "<br>";
echo "===========================";
echo "<br>";
//Test Read Building
echo "Test Read Current Apt# Info .By Passing Resident ID::";
echo "<br>";
$a5=$testdb->Resident_DB_Building (1);
print_r($a5);
echo "<br>";
echo "===========================";
echo "<br>";


?>