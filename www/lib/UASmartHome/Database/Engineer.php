<?php

include 'config.php';
//require_once __DIR__ . '/../vendor/autoload.php';




//$tables = array("Relative Humidity"=>"Air", "Temperature" => "Air", "CO2"=>"Air");



class Engineer {

//Engineer

//$tables = array("Relative Humidity"=>"Air", "Temperature" => "Air", "CO2"=>"Air"); 




	function db_pull_query($apt, $column, $startdate, $enddate, $period) {
		

		$tables = array("RelHum"=>"Air", "Temperature" => "Air", "CO2"=>"Air", "HotWater"=>"Water", "TotalWater"=>"Water", "Insulation"=>"Heat", "Stud"=>"Heat", "CurrentFlow"=>"Heat", "CurrentTemp"=>"Heat", "TotalMass"=>"Heat", "TotalEnergy"=>"Heat", "TotalVol"=>"Heat");

		$dateparts = explode("-", $startdate);
		$year = $dateparts[0];
		$month = $dateparts[1];
		$day = $dateparts[2];

		$table = $tables[$column];

		$results = array();
		//echo $startdate."\n".$enddate;
		$startdate = date_create_from_Format('Y-m-d', $startdate);
		$enddate = date_create_from_Format('Y-m-d', $enddate);
		//$loopkill = false;
	//	echo $startdate."\n".$enddate;
		//while (!$loopkill) { 
		//:	echo "5";
			//echo var_dump($startdate);
			//echo var_dump($enddate);
			if ($period == "Hourly") {
				while ($startdate <= $enddate) {
					$temp = Engineer::db_query_Hourly($apt,$table,$startdate->format('Y-m-d'), $column);
					$results[$startdate->format('Y-m-d')] = $temp[0];
					$startdate->add(date_interval_create_from_date_string('1 hour'));
				}	
			//return db_query_Hourly(
			//Needs the "get every hour" function
			} else if ($period == "Daily") {
				//$results[$startdate] = Engineer::db_query_Daily($apt, $table, $startdate->format('Y-m-d'), $column)[0][$column];
				while ($startdate <= $enddate) {
					$temp = Engineer::db_query_Daily($apt, $table, $startdate->format('Y-m-d'), $column); 
					$results[$startdate->format('Y-m-d')] = $temp[0]; 
					$startdate->add(date_interval_create_from_date_string('1 day'));
			}
				//echo var_dump($startdate);
				//$startdate
			} else if ($period == "Weekly") {
				$week = date_create_from_format("d-M-Y", $date);
				//return db_query_Weekly($apt, $table, $
				//CANT GET WEEK NUMBER RELIABLY
			} else if ($period == "Monthly") {
				$endyear = $enddate->format("Y");
				$endmonth = $enddate->format("n");
				$year = $startdate->format("Y");
				$month = $startdate->format("n");
				while ($month <= $endmonth && $year <= $endyear) {
					$temp = Engineer::db_query_Monthly($apt, $table, $year, $month, $column);
					$results[$startdate->format('Y-m-d')] =  $temp[0];
					$startdate->add(date_interval_create_from_date_string('1 month')); //TODO: This requires a new method that adds days based on the month because this PHP just adds 30 day

			        	$year = $startdate->format("Y");
	                                $month = $startdate->format("n");

				}
			} else if ($period == "Yearly") {
				$endyear = $enddate->format("Y");
				$year = $startdate->format("Y");
				while ($year <= $endyear) {

					$temp = Engineer::db_query_Yearly($apt, $table, $year, $column);
					$results[$startdate->format('Y-m-d')] = $temp[0];
					$startdate->add(date_interval_create_from_date_string('1 year'));
					$year = $startdate->format("Y");

					//return db_query_Yearly($apt, $table, $year, $column)[0][$column];
				}
			}
		//}
	


		return $results;
	
	}



	function db_query_Yearly($apt,$table,$Year,$column)
	{
	           
			   
			    $result =array();
			    $table .= '_Yearly';
		        $sql=$GLOBALS['conn']->prepare("select ".$column." from ".$table." where Apt= :Apt_Num AND Year= :Year ") ;
				$sql->bindValue(":Apt_Num",$apt);
				$sql->bindValue(":Year",$Year);
				$sql->execute();
				$row_count= $sql->rowCount();
				while ($row = $sql->fetch(PDO::FETCH_ASSOC))
				{
				$result[]=(array)$row;
				}
				$a= $sql->rowCount();
				return $result;
	}
	
function db_query_Monthly($apt,$table,$Year,$Month,$column)
	{
			    $result =array();
			    $table .= '_Monthly';
		        $sql=$GLOBALS['conn']->prepare("select ".$column." from ".$table." where Apt= :Apt_Num AND Year= :Year AND Month= :Month ") ;
				$sql->bindValue(":Apt_Num",$apt);
				$sql->bindValue(":Year",$Year);
				$sql->bindValue(":Month",$Month);
				$sql->execute();
				$row_count= $sql->rowCount();
				while ($row = $sql->fetch(PDO::FETCH_ASSOC))
				{
				$result[]=(array)$row;
				}
				$a= $sql->rowCount();
				return $result;
	}
	function db_query_Weekly($apt,$table,$Year,$Week,$column)
	{
			    $result =array();
			    $table .= '_Weekly';
		        $sql=$GLOBALS['conn']->prepare("select ".$column." from ".$table." where Apt= :Apt_Num AND Year= :Year AND Week= :Week") ;
				$sql->bindValue(":Apt_Num",$apt);
				$sql->bindValue(":Year",$Year);
				$sql->bindValue(":Week",$Week);
				$sql->execute();
				$row_count= $sql->rowCount();
				while ($row = $sql->fetch(PDO::FETCH_ASSOC))
				{
				$result[]=(array)$row;
				}
				$a= $sql->rowCount();
				return $result;
	}
	function db_query_Daily($apt,$table,$date,$column)
	{
	           
			   
				$result =array();
				$table .= '_Daily';
			        $sql=$GLOBALS['conn']->prepare("select ".$column.", Date from ".$table." where Apt= :Apt_Num AND Date= :Date ") ;
				$sql->bindValue(":Apt_Num",$apt);
				$sql->bindValue(":Date",$date);
				$sql->execute();
				$row_count= $sql->rowCount();
				while ($row = $sql->fetch(PDO::FETCH_ASSOC))
				{
				$result[]=(array)$row;
				}
				$a= $sql->rowCount();
				return $result;
	}
	function db_query_Hourly($apt,$table,$date,$Hour,$column)
	{
	           
			   
			    $result =array();
			    $table .= '_Hourly';
		        $sql=$GLOBALS['conn']->prepare("select ".$column." from ".$table." where Apt= :Apt_Num AND Date= :Date AND Hour= :Hour ") ;
				$sql->bindValue(":Apt_Num",$apt);
				$sql->bindValue(":Date",$date);
				$sql->bindValue(":Hour",$Hour);
				$sql->execute();
				$row_count= $sql->rowCount();
				while ($row = $sql->fetch(PDO::FETCH_ASSOC))
				{
				$result[]=(array)$row;
				}
				$a= $sql->rowCount();
				return $result;
	}
         	public function db_air_Period($apt,$datefrom,$dateto,$hourfrom,$hourto,$type)
	{
			    $result =array();
				$errinf =array();
				$subsql ="test";
				print_r($hourfrom);
				if ($hourfrom==Null) $hourfrom=0;
				if ($hourto==Null)   $hourto=23;
				if ($type <> 5)
				   {
				     $hourfrom=0;
					 $hourto=23;
				   }
				switch ($type)
				{
					case 1:	
						$subsql = "v0_air.Year";
						break;
					case 2: 
						$subsql = "v0_air.Year,v0_air.Month";
						break;
					case 3: 
						$subsql = "v0_air.Year,v0_air.Week";
						break;
					case 4: 
						$subsql = "v0_air.Date";
						break;
					case 5: 
						$subsql = "v0_air.Date,v0_air.Hour";
						break;
				}
			//	print_r($type);
				$sqlstatment="select `v0_air`.`Apt` AS `Apt`,".$subsql.",avg(`v0_air`.`Temperature`) AS `Temperature`,
							avg(`v0_air`.`Relative Humidity`) AS `Relative Humidity`,
							avg(`v0_air`.`CO2`) AS `CO2`
							from  v0_air
							where Apt= :Apt_Num AND (Date between  :datefrom and :dateto)
							AND (Hour between  :hourfrom and :hourto)
  							Group by v0_air.`Apt`,".$subsql;
				//print_r($sqlstatment);
							
				$sql=$GLOBALS['conn']->prepare($sqlstatment);

				$sql->bindValue(":Apt_Num",$apt);
				$sql->bindValue(":datefrom",$datefrom);
				$sql->bindValue(":dateto",$dateto);
				$sql->bindValue(":hourfrom",$hourfrom);
				$sql->bindValue(":hourto",$hourto);
				$errinf = $sql->errorinfo();
				$sql->execute();
				$row_count= $sql->rowCount();
				array_push($result,$errinf[0]);
				array_push($result,$errinf[1]);
				array_push($result,$errinf[2]);
				while ($row = $sql->fetch(PDO::FETCH_ASSOC))
				{
					array_push($result,$row);
				}
				array_push($result,$sql->rowCount());
				return $result;
	}
		
	
}

// Example code
// must code
//This should all be  in a test method, because this file has tobe included elsewhere
/*
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

//Test It Periodically
echo "Test it Periodically : By Passing the Apt# Date from '2012-02-29' and '2013-03-31' ";
echo "<br>";
$r4=$testdb->db_air_Period(5,'2012-03-01','2012-03-01',0,5,5);
print_r($r4);
echo "<br>";
echo "===========================";
echo "<br>";
*/
?>
