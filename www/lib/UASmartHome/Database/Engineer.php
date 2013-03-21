<?php

include 'config.php';
class Engineer {

	function db_pull_query($apt, $column, $startdate, $enddate, $period, $phase=null) {
	
		$tables = array("Relative_Humidity"=>"Air", "Temperature" => "Air", "CO2"=>"Air", "Hot_Water"=>"Water", "Total_Water"=>"Water", "Insulation"=>"Heat_Flux", "Stud"=>"Heat_Flux", "Current_Flow"=>"Heating", "Current_Temperature_1"=>"Heating", "Current_Temperature_2"=>"Heating",  "Total_Mass"=>"Heating", "Total_Energy"=>"Heating", "Total_Volume"=>"Heating", "Phase"=>"El_Energy", "Ch1"=>"El_Energy", "Ch2"=>"El_Energy", "AUX1"=>"El_Energy", "AUX2"=>"El_Energy", "AUX3"=>"El_Energy", "AUX3"=>"El_Energy", "AUX4"=>"El_Energy", "AUX5"=>"El_Energy");

		$dateparts = explode("-", $startdate);
		$year = $dateparts[0];
		$month = $dateparts[1];
		$day = $dateparts[2];

		$table = $tables[$column];

		$results = array();
		$startdate = date_create_from_Format('Y-m-d:G', $startdate);
		$enddate = date_create_from_Format('Y-m-d:G', $enddate);
	
			if ($period == "Hourly") {
				while ($startdate <= $enddate) {
					$temp = Engineer::db_query_Hourly($apt,$table,$startdate->format('Y-m-d'), $startdate->format('G'), $column, $phase);
					
					if (ISSET($temp[0])) {
						$results[$startdate->format('Y-m-d:G')] = $temp[0];
					} else {
						$results[$startdate->format('Y-m-d:G')] = 0;
					}
					$startdate->add(date_interval_create_from_date_string('1 hour'));
				}	
			} else if ($period == "Daily") {
				while ($startdate <= $enddate) {
					$temp = Engineer::db_query_Daily($apt, $table, $startdate->format('Y-m-d'), $column, $phase); 
					if (ISSET($temp[0])) {
						$results[$startdate->format('Y-m-d:G')] = $temp[0];
					} else {
						$results[$startdate->format('Y-m-d:G')] = 0;
					}
					$startdate->add(date_interval_create_from_date_string('1 day'));
				}
			} else if ($period == "Weekly") {
				//$week = date_create_from_format("d-M-Y", $date);
				//return db_query_Weekly($apt, $table, $
				//CANT GET WEEK NUMBER RELIABLY
			} else if ($period == "Monthly") {
				$endyear = $enddate->format("Y");
				$endmonth = $enddate->format("n");
				$year = $startdate->format("Y");
				$month = $startdate->format("n");
				while ($month <= $endmonth && $year <= $endyear) {
					$temp = Engineer::db_query_Monthly($apt, $table, $year, $month, $column, $phase);
					if (ISSET($temp[0])) {
						$results[$startdate->format('Y-m-d:G')] = $temp[0];
					} else {
						$results[$startdate->format('Y-m-d:G')] = 0;
					}
					$startdate->add(date_interval_create_from_date_string('1 month')); //TODO: This requires a new method that adds days based on the month because this PHP just adds 30 day

			        	$year = $startdate->format("Y");
	                                $month = $startdate->format("n");

				}
			} else if ($period == "Yearly") {
				$endyear = $enddate->format("Y");
				$year = $startdate->format("Y");
				while ($year <= $endyear) {

					$temp = Engineer::db_query_Yearly($apt, $table, $year, $column, $phase);
					if (ISSET($temp[0])) {
						$results[$startdate->format('Y-m-d:G')] = $temp[0];
					} else {
						$results[$startdate->format('Y-m-d:G')] = 0;
					}
					$startdate->add(date_interval_create_from_date_string('1 year'));
					$year = $startdate->format("Y");
				}
			}
		return $results;
	}



	function db_query_Yearly($apt,$table,$Year,$column, $phase=null)
	{
	           
			   
			    $result =array();
			    $table .= '_Yearly';
		        //$sql=$GLOBALS['conn']->prepare("select ".$column." from ".$table." where Apt= :Apt_Num AND Year= :Year ") ;
			if ($phase == null) {
		        	$sql=$GLOBALS['conn']->prepare("select ".$column." from ".$table." where Apt= :Apt_Num AND Year= :Year") ;
			} else {
				$sql=$GLOBALS['conn']->prepare("select ".$column." from ".$table." where Apt= :Apt_Num AND Year= :Year AND Phase = :Phase") ;
				$sql->bindValue(":Phase", $phase);
			}
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
	
function db_query_Monthly($apt,$table,$Year,$Month,$column, $phase=null)
	{
			    $result =array();
			    $table .= '_Monthly';
		        //$sql=$GLOBALS['conn']->prepare("select ".$column." from ".$table." where Apt= :Apt_Num AND Year= :Year AND Month= :Month ") ;
			if ($phase == null) {
		        	$sql=$GLOBALS['conn']->prepare("select ".$column." from ".$table." where Apt= :Apt_Num AND Year= :Year AND Month= :Month") ;
			} else {
				$sql=$GLOBALS['conn']->prepare("select ".$column." from ".$table." where Apt= :Apt_Num AND Year= :Year AND Month= :Month AND Phase = :Phase") ;
				$sql->bindValue(":Phase", $phase);
			}
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
	function db_query_Weekly($apt,$table,$Year,$Week,$column,$phase=null)
	{
			    $result =array();
			    $table .= '_Weekly';
		        //$sql=$GLOBALS['conn']->prepare("select ".$column." from ".$table." where Apt= :Apt_Num AND Year= :Year AND Week= :Week") ;
			if ($phase == null) {
		        	$sql=$GLOBALS['conn']->prepare("select ".$column." from ".$table." where Apt= :Apt_Num AND Year= :Year AND Week= :Week") ;
			} else {
				$sql=$GLOBALS['conn']->prepare("select ".$column." from ".$table." where Apt= :Apt_Num AND Year= :Year AND Week= :Week AND Phase = :Phase") ;
				$sql->bindValue(":Phase", $phase);
			}
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
	function db_query_Daily($apt,$table,$date,$column,$phase=null)
	{
	           
			   
				$result =array();
				$table .= '_Daily';
			       
			if ($phase == null) {
		        	$sql=$GLOBALS['conn']->prepare("select ".$column." from ".$table." where Apt= :Apt_Num AND Date= :Date") ;
			} else {
				$sql=$GLOBALS['conn']->prepare("select ".$column." from ".$table." where Apt= :Apt_Num AND Date= :Date AND Phase = :Phase") ;
				$sql->bindValue(":Phase", $phase);
			}
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
	function db_query_Hourly($apt,$table,$date,$Hour,$column,$phase=null)
	{
	           

			    $result =array();
			    $table .= '_Hourly';
			if ($phase == null) {
		        	$sql=$GLOBALS['conn']->prepare("select ".$column." from ".$table." where Apt= :Apt_Num AND Date= :Date AND Hour= :Hour ") ;
			} else {
				$sql=$GLOBALS['conn']->prepare("select ".$column." from ".$table." where Apt= :Apt_Num AND Date= :Date AND Hour= :Hour AND Phase = :Phase") ;
				$sql->bindValue(":Phase", $phase);
			}
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
		
	


	public 	function Utilities_Delete ($Month,$Year)
	{
			$conn=new Connection ();
			$Query=$conn->connect()->prepare("delete from Utilities_Prices where Month= :MT AND Year= :YR" ) ;
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


?>
