<?php
include 'config.php';

class Engineer {

//Engineer
	function db_query_Yearly($apt,$table,$Year)
	{
	           
			   
			    $result =array();
			    $table .= '_Yearly';
		        $sql=$GLOBALS['conn']->prepare("select * from ".$table." where Apt= :Apt_Num AND Year= :Year ") ;
				$sql->bindValue(":Apt_Num",$apt);
				$sql->bindValue(":Year",$Year);
				$sql->execute();
				$row_count= $sql->rowCount();
				while ($row = $sql->fetch(PDO::FETCH_OBJ))
				{
				$result[]=(array)$row;
				}
				$a= $sql->rowCount();
				return $result;
	}
	
function db_query_Monthly($apt,$table,$Year,$Month)
	{
			    $result =array();
			    $table .= '_Monthly';
		        $sql=$GLOBALS['conn']->prepare("select * from ".$table." where Apt= :Apt_Num AND Year= :Year AND Month= :Month ") ;
				$sql->bindValue(":Apt_Num",$apt);
				$sql->bindValue(":Year",$Year);
				$sql->bindValue(":Month",$Month);
				$sql->execute();
				$row_count= $sql->rowCount();
				while ($row = $sql->fetch(PDO::FETCH_OBJ))
				{
				$result[]=(array)$row;
				}
				$a= $sql->rowCount();
				return $result;
	}
	function db_query_Weekly($apt,$table,$Year,$Week)
	{
			    $result =array();
			    $table .= '_Weekly';
		        $sql=$GLOBALS['conn']->prepare("select * from ".$table." where Apt= :Apt_Num AND Year= :Year AND Week= :Week") ;
				$sql->bindValue(":Apt_Num",$apt);
				$sql->bindValue(":Year",$Year);
				$sql->bindValue(":Week",$Week);
				$sql->execute();
				$row_count= $sql->rowCount();
				while ($row = $sql->fetch(PDO::FETCH_OBJ))
				{
				$result[]=(array)$row;
				}
				$a= $sql->rowCount();
				return $result;
	}
	function db_query_Daily($apt,$table,$date)
	{
	           
			   
			    $result =array();
			    $table .= '_Daily';
		        $sql=$GLOBALS['conn']->prepare("select * from ".$table." where Apt= :Apt_Num AND Date= :Date ") ;
				$sql->bindValue(":Apt_Num",$apt);
				$sql->bindValue(":Date",$date);
				$sql->execute();
				$row_count= $sql->rowCount();
				while ($row = $sql->fetch(PDO::FETCH_OBJ))
				{
				$result[]=(array)$row;
				}
				$a= $sql->rowCount();
				return $result;
	}
	function db_query_Hourly($apt,$table,$date,$Hour)
	{
	           
			   
			    $result =array();
			    $table .= '_Hourly';
		        $sql=$GLOBALS['conn']->prepare("select * from ".$table." where Apt= :Apt_Num AND Date= :Date AND Hour= :Hour ") ;
				$sql->bindValue(":Apt_Num",$apt);
				$sql->bindValue(":Date",$date);
				$sql->bindValue(":Hour",$Hour);
				$sql->execute();
				$row_count= $sql->rowCount();
				while ($row = $sql->fetch(PDO::FETCH_OBJ))
				{
				$result[]=(array)$row;
				}
				$a= $sql->rowCount();
				return $result;
	}
	
	
}

// Example code
// must code
$testdb=new Engineer ();

//Test it Yearly
echo "Test it Yearly : By Passing the Apt# , table name and Year ::";
echo "<br>";
$r0=$testdb->db_query_Yearly(1,'Air','2012');
print_r($r0);
echo "<br>";
echo "===========================";
echo "<br>";
//Test it Monthly
echo "Test it Monthly : By Passing the Apt# , table name , Year and Month ::";
echo "<br>";
$r1=$testdb->db_query_Monthly(1,'Air','2012',3);
print_r($r1);
echo "<br>";
echo "===========================";
echo "<br>";
//Test it Weekly
echo "Test it Weekly : By Passing the Apt# , table name , Year and [Week of the Year]IMP ::";
echo "<br>";
$r2=$testdb->db_query_Weekly (1,'Air','2012',9);
print_r($r2);
echo "<br>";
echo "===========================";
echo "<br>";
//Test it Daily
echo "Test it Daily : By Passing the Apt# , table name and Date '2012-02-29' ::";
echo "<br>";
$r3=$testdb->db_query_Daily(1,'Air','2012-02-29');
print_r($r3);
echo "<br>";
echo "===========================";
echo "<br>";
//Test It Hourly
echo "Test it Hourly : By Passing the Apt# , table name ,Date '2012-02-29' and Hour [24 Hour fromat] ::";
echo "<br>";
$r4=$testdb->db_query_Hourly(1,'Air','2012-02-29',22);
print_r($r4);
echo "<br>";
echo "===========================";
echo "<br>";



?>