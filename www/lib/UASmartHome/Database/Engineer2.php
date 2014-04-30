<?php namespace UASmartHome\Database; 

class Engineer2 {

    const ENERGY_EQ = 6;

    public static $EnergyColumns = array(
            "Energy1" => "Solar",
            "Energy2" => "DWHR",
            "Energy3" => "Geothermal + DWHR",
            "Energy4" => "Solar + DWHR + Geothermal + Heat Pumps",
            "Energy5" => "Boiler 1",
            "Energy6" => "Boiler 2",
            "Energy7" => "Heating Consumption"
            );

    public function EQ($Datefrom,$Dateto,$EQ,$column=null,$tablename=null, $incl=1)
	{
	
	 $conn=new Connection2();
	if ($EQ==1)	{
		$dbh=$conn->connect()->prepare("select SUM(Energy1) AS Energy1,SUM(Energy2) AS Energy2,SUM(Energy3) AS Energy3,SUM(Energy4) AS Energy4,SUM(Energy5+Energy6) AS Energy5_6,SUM(Energy7) AS Energy7 from Energy_Minute where ts between :SD and :ED") ;
		$dbh->bindValue(":SD",$Datefrom);
		$dbh->bindValue(":ED",$Dateto);
		$dbh->execute();
		$row = $dbh->fetch(\PDO::FETCH_ASSOC);
		return $row;
	}
	if ($EQ==2 || $EQ==3)	{
		$row = array();

		$dbh=$conn->connect()->prepare("SELECT * FROM stonymountain.bas_el_energy WHERE ts >= :SD ORDER BY ts LIMIT 0,1") ;
		$dbh->bindParam(":SD",$Datefrom);
		$dbh->execute();
		$start = $dbh->fetch(\PDO::FETCH_ASSOC);
		$dbh=$conn->connect()->prepare("SELECT * FROM stonymountain.bas_el_energy WHERE ts >= :ED ORDER BY ts LIMIT 0,1") ;
		$dbh->bindParam(":ED",$Dateto);
		$dbh->execute();
		$end = $dbh->fetch(\PDO::FETCH_ASSOC);
	
		foreach ($end as $pump => $energy)
			$row[$pump] = ($end[$pump] - $start[$pump])*2.778e-7;

		$dbh=$conn->connect()->prepare("SELECT * FROM `bas_el_energy_extra` WHERE ts >= :SD ORDER BY ts LIMIT 0,1") ;
		$dbh->bindParam(":SD",$Datefrom);
		$dbh->execute();
		$start = $dbh->fetch(\PDO::FETCH_ASSOC);
		$dbh=$conn->connect()->prepare("SELECT * FROM `bas_el_energy_extra` WHERE ts >= :ED ORDER BY ts LIMIT 0,1") ;
		$dbh->bindParam(":ED",$Dateto);
		$dbh->execute();
		$end = $dbh->fetch(\PDO::FETCH_ASSOC);
		foreach ($end as $pump => $energy)
			$row[$pump] = ($end[$pump] - $start[$pump]);
		
		unset($row['ts']);
		unset($row['seconds_counter']);

		if ($EQ==2)
			return $row;

		if ($EQ==3) {
			$cop = array();
			$dbh=$conn->connect()->prepare("SELECT SUM(Energy4) AS prod_total,SUM(Energy7) AS cons_total from EnergyH_Graph where ts between :SD and :ED");
			$dbh->bindValue(":SD",$Datefrom);
			$dbh->bindValue(":ED",$Dateto);
			$dbh->execute();
			$energy = $dbh->fetch(\PDO::FETCH_ASSOC);
			$cop1_el = $row['HP1']+$row['HP2']+$row['HP3']+$row['HP4'];
			$cop2_el = $cop1_el+$row['P-1-1']+$row['P-1-2']+$row['SHTS']+$row['P7_1']+$row['P8']+$row['P2_1']+$row['P2_2']+$row['P2_3']+$row['P2_4'];
			$cop3_el = $cop2_el+$row['P4_1']+$row['P4_2']+$row['BLR_1']+$row['BLR_2']+$row['P3_1']+$row['P3_2'];
			$cop['COP1'] = ($energy['prod_total']*2.778e-4)/($cop1_el);
			$cop['COP2'] = ($energy['prod_total']*2.778e-4)/($cop2_el);
			$cop['COP3'] = ($energy['cons_total']*2.778e-4)/($cop3_el);
			return $cop;
		}
	}
	
    if ($EQ==self::ENERGY_EQ)
	{//The Tablees Are EnergyH_Graph the Ts format ->2013-03-15:01 ,EnergyD_Graph is a Date only 2013-03-15
	    //print "$Datefrom $Dateto\n";
	    $comparison = '<';
	    if ($incl)
	    	$comparison = '<=';
		$dbh=$conn->connect()->prepare("select ".$column."  from ".$tablename." where ts >= :SD AND ts $comparison :ED") ;
	    $dbh->bindValue(":SD",$Datefrom);
	    $dbh->bindValue(":ED",$Dateto);
	    $dbh->execute();
	    $result = $dbh->fetch(\PDO::FETCH_ASSOC);
	    return $result[$column];
	}
}


    public static function getEnergyColumnData($datefrom, $dateto,$column,$granularity=null) {
        /* Assume that all dates are in a "canonical" format -- that is, 
         * prevent the server's default timezone from affecting the date. */
        date_default_timezone_set('UTC');
       // echo "***** {$datefrom} ****";
        
        $intervalString = null;
        switch ($granularity) {
            case "Hourly": $intervalString = '1 hour'; break;
            case "Daily": $intervalString = '1 day'; break;
            case "Weekly": $intervalString = '1 week'; break;
            case "Monthly":
            default: $intervalString = '1 month'; break;
        }
        
        $interval = \DateInterval::createFromDateString($intervalString);
        $period = new \DatePeriod($datefrom, $interval, $dateto);

        $data = array();

      /*  while ($startdate < $enddate) {

        if ($granularity == "Hourly") {
            $data=self::EQ($datefrom->format("Y-m-d:G"), $dateto->format("Y-m-d:G"), self::ENERGY_EQ, $column, "EnergyH_Graph");
        } else if ($granularity == "Daily"){
            $data=self::EQ($datefrom->format("Y-m-d"), $dateto->format("Y-m-d"), self::ENERGY_EQ, $column, "EnergyD_Graph");
        }

        }
*/      foreach ($period as $tick) {

            $datapoint = "Error";
            
            $strDisplayTick = $tick->format("Y-m-d:G");
            
            if ($granularity == "Hourly") {
                $strTick = $tick->format("Y-m-d:H");
                $strTickEnd = $tick->add($interval)->format("Y-m-d:H");
                $datapoint = self::EQ($strTick, $strTickEnd, self::ENERGY_EQ, $column, "EnergyH_Graph");
            } else if ($granularity == "Daily") {
                $strTick = $tick->format("Y-m-d");
                $strTickEnd = $tick->add($interval)->format("Y-m-d");
                $datapoint = self::EQ($strTick, $strTickEnd, self::ENERGY_EQ, $column, "EnergyD_Graph");
            }
            else if ($granularity == "Monthly" || $granularity == "Weekly") {
            	$strTick = $tick->format("Y-m-d");
            	$strTickEnd = $tick->add($interval)->format("Y-m-d");
            	$datapoint = self::EQ($strTick, $strTickEnd, self::ENERGY_EQ, "SUM($column)", "EnergyD_Graph", 0);
            }
               
            $data[$strDisplayTick] = $datapoint;
            
        }


        return $data;
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
