<?php
include ('Connection.php');

class WeatherGetData 
{
	function getGetData()
	{
  $RSS_Link = simplexml_load_file("http://www.weatheroffice.gc.ca/rss/city/ab-20_e.xml");
  $getLink= $RSS_Link->channel ->item[1]->description;
  $GetData = explode(" ", $getLink);
  $Temp=" ";
  $RH=" ";
  $WD=" ";
  $nmonth = date('m',strtotime($GetData[10]));
  $Month =explode("0", $nmonth);
  $Time= DATE("H:i", STRTOTIME($GetData[5].$GetData[6]));
  preg_match('#Temperature:(?:</b>)?\s+([-+]?\d+\.\d+)#', $getLink, $Temp);
  preg_match('#Humidity:(?:\<\/b\>)?\s+([-+]?\d+\.*\d+)#', $getLink, $RH);
  preg_match('#Wind:(?:\<\/b\>)?\s+(\w{1,2})?\s+(\d+)#', $getLink, $WD);
  $conn=new Connection ();
	 $WindDir;
	 if ($WD[2]==0) 
	 {
	 $WindDir=" ";
	 }else {$WindDir=$WD[1];}
	 
      $Query=$conn->connect()->prepare("INSERT INTO `Weather_Forecast` (`Year`,`Month`,`Day`,`Hour`,`External_Temperature`
	  ,`External_Relative_Humidity`,`Wind_Speed`,`Wind_Direction`) VALUES (:YR,:MT,:DY,:HR,:TP,:RV,:WS,:WD)");
			$Query->bindValue(":YR",$GetData[11]);
			$Query->bindValue(":MT",$Month[1]);
			$Query->bindValue(":DY",$GetData[9]);
			$Query->bindValue(":HR",$Time);
			$Query->bindValue(":TP",$Temp[1]);
			$Query->bindValue(":RV",$RH[1]);
			$Query->bindValue(":WS",$WD[2]);
			$Query->bindValue(":WD",$WindDir);
			$Query->execute();
  }
  }
  $testdGetGetData= new WeatherGetData();
  $testdGetGetData->getGetData ();
?>
