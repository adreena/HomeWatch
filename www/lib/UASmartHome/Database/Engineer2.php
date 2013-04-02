<?php namespace UASmartHome\Database; 

class Engineer2 {

    const ENERGY_EQ = 6;
    
    public static $EnergyColumns = array(
        "Energy1" => "Solar Day",
        "Energy2" => "DWHR",
        "Energy3" => "Geothermal + DWHR",
        "Energy4" => "Solar + DWHR + Geothermal + Heat Pumps",
        "Energy5" => "Boiler 1",
        "Energy6" => "Boiler 2",
        "Energy7" => "Heating Energy Consumption"
    );
    
    public function EQ($Datefrom,$Dateto,$EQ,$column=null)
	{
	
	 $conn=new Connection2();
	if ($EQ==1)
	{
	$dbh=$conn->connect()->prepare("select SUM(".$column.") from Energy_Minute where ts between :SD and :ED") ;
	$dbh->bindValue(":SD",$Datefrom);
	$dbh->bindValue(":ED",$Dateto);
	$dbh->execute();
	$row = $dbh->fetch(\PDO::FETCH_ASSOC);
	return $row;
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
        if ($EQ==self::ENERGY_EQ)
	{
	$dbh=$conn->connect()->prepare("select (SUM(".$column.")*1000000) from Energy_Minute where ts between :SD and :ED") ;
	$dbh->bindValue(":SD",$Datefrom);
	$dbh->bindValue(":ED",$Dateto);
	$dbh->execute();
	$row = $dbh->fetch(\PDO::FETCH_ASSOC);
	return $row;
	}
}

    public static function getEnergyColumnData($Datefrom,$Dateto,$column) {
        return self::EQ($Datefrom, $Dateto, ENERGY_EQ, $column);
    }
    
    public static function getEnergyColumns() {
        return self::$EnergyColumns;
    }

}

/*
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
*/

?>
