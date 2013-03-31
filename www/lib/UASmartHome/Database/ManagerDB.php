<?php namespace UASmartHome\Database;

class ManagerDB {


   public function Manager_DB_Read($resident_id)
	{
			   $result =array();
			   $conn=new Connection ();
		     $Query=$conn->connect()->prepare("select Name ,Username,Room_Number ,Location,
			  Points,Room_Status from Resident where Resident_ID=:Res_ID") ;
				$Query->bindValue(":Res_ID",$resident_id);
				$Query->execute();
				$row = $Query->fetch(\PDO::FETCH_OBJ);
				$result=(array)$row;
				$a= $Query->rowCount();
				echo $a;
				echo "<br \>";
				return $result;
	}

	public function Manager_DB_Update_Score($resident_id, $Score)
	{
	    $conn=new Connection ();
	    $Query=$conn->connect()->prepare("update Resident set Score= :SC where Resident_ID= :Res_ID");
		$Query->bindValue(":Res_ID",$resident_id);
		$Query->bindValue(":SC",$Score);
		$Query->execute();
	}

	public function Manager_DB_Update  ($resident_id,$Room_Number,$Name,$Username,$Room_Status,$Location)
	{ //update Resdient
	
	$conn=new Connection ();
	   $Query=$conn->connect()->prepare("update Resident  
		set Room_Status= :RS,Room_Number= :RN ,Name= :NM,Username= :US ,Location= :LO where Resident_ID= :Res_ID") ;
		$Query->bindValue(":Res_ID",$resident_id);
		$Query->bindValue(":RS",$Room_Status);
		$Query->bindValue(":RN",$Room_Number);
		$Query->bindValue(":NM",$Name);
		$Query->bindValue(":US",$Username);
		$Query->bindValue(":LO",$Location);
		 $Query->execute();
	}
	//// WARNING DELETES EVERYTHING RELATED TO RESIDENT\\\\\
	public function Manager_DB_Delete_Super ($UserID)
	{
	$conn=new Connection ();
	$Query=$GLOBALS['conn']->prepare("Delete From Users where User_ID= :UD") ;
	$Query->bindValue(":UD",$UserID);
	$Query->execute();
	}
	

	public function Manager_DB_Create ($resident_id,$Room_Status,$Name,$Username,$Location,$Points,$Room_Number)
	{
	$conn=new Connection ();
	$Query=$GLOBALS['conn']->prepare("INSERT INTO Resident (Resident_ID , Name ,Username ,
	Room_Number ,Location ,Points ,Room_Status) VALUES  (:Res_ID,:NM,:US,:RN,:LO,:PO,:RS)") ;
	$Query->bindValue(":Res_ID",$resident_id);
	$Query->bindValue(":RS",$Room_Status);
	$Query->bindValue(":NM",$Name);
	$Query->bindValue(":US",$Username);
	$Query->bindValue(":LO",$Location);
	$Query->bindValue(":PO",$Points);
	$Query->bindValue(":RN",$Room_Number);
	$Query->execute();
	}
	public 	function Manager_DB_Achievement_Read ()
	{
			   $result =array();
			   $conn=new Connection ();
		      $Query=$conn->connect()->prepare("select Name ,Description ,
	           Enabled_Icon ,Disabled_Icon,Points from Achievements ") ;
				$Query->execute();
				
				while ($row = $Query->fetch(\PDO::FETCH_OBJ))
				{
				$result[]=(array)$row;
				}
				return $result;
	}
	public 	function Manager_DB_Achievement_Update ($Achievement_id,$Name,$Description,$Enabled_Icon,$Disabled_Icon,$Points)
	{
			   $result =array();
			   $conn=new Connection ();
		       $Query=$conn->connect()->prepare("update Achievements  
		set Name= :NM ,	Description= :DS, Enabled_Icon= :EI ,Disabled_Icon= :DI,Points= :PO where Achievement_ID= :Ach_ID ") ;
			   $Query->bindValue(":Ach_ID",$Achievement_id);
				$Query->bindValue(":NM",$Name);
				$Query->bindValue(":DS",$Description);
				$Query->bindValue(":EI",$Enabled_Icon);
				$Query->bindValue(":DI",$Disabled_Icon);
				$Query->bindValue(":PO",$Points);
				$Query->execute();
	}
	public 	function Manager_DB_Achievement_Create ($Achievement_id,$Name,$Description,$Enabled_Icon,$Disabled_Icon,$Points)
	{
			   $result =array();
			   $conn=new Connection ();
			  $Query=$conn->connect()->prepare("INSERT INTO Achievements (Achievement_ID , Name ,Description ,
	           Enabled_Icon ,Disabled_Icon,Points) VALUES  (:Ach_ID,:NM,:DS,:EI,:DI,:PO)") ;
				$Query->bindValue(":Ach_ID",$Achievement_id);
				$Query->bindValue(":NM",$Name);
				$Query->bindValue(":DS",$Description);
				$Query->bindValue(":EI",$Enabled_Icon);
				$Query->bindValue(":DI",$Disabled_Icon);
				$Query->bindValue(":PO",$Points);
				$Query->execute();
				
	}
	public 	function Manager_DB_Achievement_Delete ($Achievement_id)
	{
			   $result =array();
			   $conn=new Connection ();
		      $Query=$conn->connect()->prepare("delete from Achievements  
		       where Achievement_ID= :Ach_ID") ;
			$Query->bindValue(":Ach_ID",$Achievement_id);
				$Query->execute();
	}
	public 	function Manager_DB_Building ($resident_id)
	{
			    $result =array();
				$conn=new Connection ();
		       $Query=$conn->connect()->prepare("select * from Building where Resident_ID= :Res_ID") ;
				$Query->bindValue(":Res_ID",$resident_id);
				$Query->execute();
				$row = $Query->fetch(\PDO::FETCH_OBJ);
				$result=(array)$row;
				$row_count= $Query->rowCount();
				$a= $Query->rowCount();
				return $result;
	}
	public function Manager_DB_Building_Create ($Building_ID ,$Resident_ID,$Building ,$Floor,$Orientation,$Layout){
	          $result =array();
			  $conn=new Connection ();
			  $Query=$conn->connect()->prepare("INSERT INTO Building (Building_ID ,Resident_ID,Building ,
	           Floor ,Orientation,Layout) VALUES  (:BD_ID,:Res_ID,:BD,:FL,:OI,:Ly)") ;
				$Query->bindValue(":BD_ID",$Building_ID);
				$Query->bindValue(":Res_ID",$Resident_ID);
				$Query->bindValue(":BD",$Building);
				$Query->bindValue(":FL",$Floor);
				$Query->bindValue(":OI",$Orientation);
				$Query->bindValue(":Ly",$Layout);
				$Query->execute();
	}
	
	public function Manager_DB_Building_Update ($Building_ID ,$Resident_ID,$Building ,$Floor,$Orientation,$Layout)
	{
	        $result =array();
			$conn=new Connection ();
		       $Query=$conn->connect()->prepare("update Building  
		       set Building_ID= :BD_ID ,Resident_ID= :Res_ID, Building= :BD ,Floor= :FL,Orientation= :OI ,Layout= :Ly where Resident_ID= :Res_ID ") ;
		   	   $Query->bindValue(":BD_ID",$Building_ID);
				$Query->bindValue(":Res_ID",$Resident_ID);
				$Query->bindValue(":BD",$Building);
				$Query->bindValue(":FL",$Floor);
				$Query->bindValue(":OI",$Orientation);
				$Query->bindValue(":Ly",$Layout);
				$Query->execute();
	}
		public 	function Utilities_Delete ($Month,$Year)
	{
			   $conn=new Connection ();
		       $Query=$conn->connect()->prepare("delete from Utilities_Prices  
		       where Month= :MT AND Year= :YR" ) ;
			$Query->bindValue(":MT",$Month);
			$Query->bindValue(":YR",$Year);
				$Query->execute();
	}
	public 	function Utilities_Insert ($Type,$Month,$Year,$Price)
	{
	
	 $conn=new Connection ();
	    $Query=$conn->connect()->prepare("INSERT INTO Utilities_Prices (Type,Month,Year,
	           Price ) VALUES  (:TP,:MT,:YR,:PC)") ;
			$Query->bindValue(":TP",$Type);
			$Query->bindValue(":MT",$Month);
			$Query->bindValue(":YR",$Year);
			$Query->bindValue(":PC",$Price);
			$Query->execute();
	}
	public 	function Utilities_Update ($Month,$Year,$Price)
	{
			   $conn=new Connection ();
		      $Query=$conn->connect()->prepare("update Utilities_Prices  
		       set Price= :PC where Month= :MT AND Year= :YR") ;
			$Query->bindValue(":MT",$Month);
			$Query->bindValue(":YR",$Year);
			$Query->bindValue(":PC",$Price);
			$Query->execute();
	}
	
}

// Example code
// must code
//Test Manager Read Row
/*
echo " 1. Test Resident Read : By Passing the Resident ID::";
echo "<br>";
$a=$testdb->Manager_DB_Read(1);
print_r($a);
echo "<br>";
echo "===========================";
echo "<br>";
//Test Manager Update
echo "2. Test Manager Update Resident : By Passing the Resident ID,Room_Number,Name,Username,Room_Status,Location::";
echo "<br>";
$testdb->Manager_DB_Update (1,4,'TEST','TEST','TEST','TEST');
echo "<br>";
echo "===========================";
echo "<br>";
//Test Create
echo "3. Test Manager Create Resident by Passing Resident ID,Room_Number,Name,Username,Room_Status,Location, Points ";
$testdb->Manager_DB_Create (66,  'Vacc','JOJ',  'JOJ#e','SW',203,  212);
echo "<br>";
echo "===========================";
echo "<br>";
//Test SUPER DELETE
 $testdb->Manager_DB_Delete_Super(66);
 echo "4.  Super Delete:This function deletes all rows related to this Resident ID ///Not";
echo "<br>";
echo "===========================";
echo "<br>";
//Test Read Achievment
echo "5. Test Read Achievements ::";
echo "<br>";
$a4=$testdb->Manager_DB_Achievement_Read ();
print_r($a4);
echo "<br>";
echo "===========================";
echo "<br>";
//Test Create Achievment
echo "6. Test Create new Achievements  4,  'TEST','TEST',  'TEST','TEST',60000::";
echo "<br>";
$testdb->Manager_DB_Achievement_Create (5,  'TEST','TEST',  'TEST','TEST',60000);
$a4=$testdb->Manager_DB_Achievement_Read ();
print_r($a4);
echo "<br>";
echo "===========================";
echo "<br>";
//Test Update Achievment
echo "7. Test Update Achievements  5,  'UP','UP',  'UP','UP',10000::";
echo "<br>";
$testdb->Manager_DB_Achievement_Update(5,  'UP','UP',  'UP','UP',10000);
$a4=$testdb->Manager_DB_Achievement_Read ();
print_r($a4);
echo "<br>";
echo "===========================";
echo "<br>";
//Test Delete Achievment
echo "8. Test Delete Achievements  4";
echo "<br>";
$testdb->Manager_DB_Achievement_Delete (5);
$a4=$testdb->Manager_DB_Achievement_Read ();
print_r($a4);
echo "<br>";
echo "===========================";
echo "<br>";

echo "9. Test Insert Building  ";
echo "<br>";
$testdb->Manager_DB_Building_Create (1 ,1,'Windosr' ,'2608','NW','DD');

echo "<br>";
echo "===========================";
echo "<br>";

echo "9. Test Update Building  ";
echo "<br>";
$testdb->Manager_DB_Building_Create (1 ,1,'Windosr' ,'2608','NW','DD');
echo "<br>";
echo "===========================";
echo "<br>";
*/
?>
