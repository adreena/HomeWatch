<?php namespace UASmartHome\Database; 

class Engineer2 {


public function EQ($Datefrom,$Dateto,$EQ,$column=null)
	{
		//Energy1 =Solar Day  ,Energ2=(DWHR) ,Energy3=Geo field + DWHR   ,Energy4=Solar + DWHR + Geo field + Heat pumps  ,Energy5=Boiler1,Energy 6=Boiler2 ,Energy 7=Heating energy consumption

	 $conn=new Connection2();
	if ($EQ==1)
	{
	$dbh=$conn->connect()->prepare("select SUM(".$column.") from Energy_Minute where Date between :SD and :ED") ;
	$dbh->bindValue(":SD",$Datefrom);
	$dbh->bindValue(":ED",$Dateto);
	$dbh->execute();
	while ($row = $dbh->fetch(\PDO::FETCH_ASSOC))
		     {
				$result[]=(array)$row;
		     }
				
	return $result;
	}
	if ($EQ==2){//EQ2_part1
	 $dbh=$conn->connect()->prepare("select EQ2_Part1 (:SD, :ED)") ;
	$dbh->bindParam(":SD",$Datefrom);
	$dbh->bindParam(":ED",$Dateto);
	$dbh->execute();
	$row = $dbh->fetch(\PDO::FETCH_ASSOC);
	return $row;
	}
	if ($EQ==3)
	{ //EQ2_Part 2
	 $dbh=$conn->connect()->prepare("select EQ2_part2( :SD, :ED)") ;
	$dbh->bindParam(":SD",$Datefrom);
	$dbh->bindParam(":ED",$Dateto);
	$dbh->execute();
	$row = $dbh->fetch(\PDO::FETCH_ASSOC);
	return $row;
	}
	if ($EQ==4)
	{//Gets the Cop1 & Cop2
	 $dbh=$conn->connect()->prepare("select Calc_Cop( :SD,:ED)") ;
	 $dbh->bindParam(":SD",$Datefrom);
	$dbh->bindParam(":ED",$Dateto);
	$dbh->execute();
	$dbh=$conn->connect()->prepare("select * from COPCal") ;
	$dbh->execute();
	$row = $dbh->fetch(\PDO::FETCH_ASSOC);
	$trun=$conn->connect()->prepare ("Truncate COPCal");
	$trun->execute();
	return $row;
	}
	if ($EQ==5)
	{// UseAnaylze the Cop1 & Cop2
	 $dbh=$conn->connect()->prepare("select calc_cop_test( :SD,:ED)") ;
	 $dbh->bindParam(":SD",$Datefrom);
	$dbh->bindParam(":ED",$Dateto);
	$dbh->execute();
	$dbh=$conn->connect()->prepare("select * from anaylze") ;
	$dbh->execute();
	$row = $dbh->fetch(\PDO::FETCH_ASSOC);
	$trun=$conn->connect()->prepare ("Truncate anaylze");
	$trun->execute();
	return $row;
	}	      
	}



}

$testDB = new Engineer2();
print_r($testDB->EQ('2013-03-15 00:00','2013-03-21 23:59',5));
echo "<br>";
echo "===========================";
echo "<br>";
 print_r($testDB->EQ('2013-03-15 00:00','2013-03-21 23:59',4));
echo "<br>";
echo "===========================";
echo "<br>";
  print_r($testDB->EQ('2013-03-15 00:00','2013-03-21 23:59',3));
  echo "<br>";
echo "===========================";
echo "<br>";
 print_r($testDB->EQ('2013-03-15 00:00','2013-03-21 23:59',2));
  echo "<br>";
echo "===========================";
echo "<br>";
echo "<br>";
 print_r($testDB->EQ('2013-03-15 ','2013-03-21 23:59',1,"Energy1"));
  echo "<br>";
echo "===========================";
echo "<br>";

?>
