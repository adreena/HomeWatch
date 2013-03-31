<?php
include ('Connection.php');

class WeatherGetData 
{
	function getGetData()
	{
  $RSS_Link = simplexml_load_file("http://www.weatheroffice.gc.ca/rss/city/ab-20_e.xml");
  $getLink= $RSS_Link->channel ->item[1]->description;
  $GetData = explode(" ", $getLink);
  $Temp= explode ("&",$GetData[15]);
  $nmonth = date('m',strtotime($GetData[10]));
  $Month =explode("0", $nmonth);
  $Time= DATE("H:i", STRTOTIME($GetData[5].$GetData[6]));
  $conn=new Connection ();
	 $WindDir;
	 if ($GetData[30]==0) 
	 {
	 $WindDir=" ";
	 }else {$WindDir=$GetData[29];}
	 
      $Query=$conn->connect()->prepare("INSERT INTO `Weather_Forecast` (`Year`,`Month`,`Day`,`Hour`,`External_Temperature`
	  ,`External_Relative_Humidity`,`Wind_Speed`,`Wind_Direction`) VALUES (:YR,:MT,:DY,:HR,:TP,:RV,:WS,:WD)");
			$Query->bindValue(":YR",$GetData[11]);
			$Query->bindValue(":MT",$Month[1]);
			$Query->bindValue(":DY",$GetData[9]);
			$Query->bindValue(":HR",$Time);
			$Query->bindValue(":TP",$Temp[0]);
			$Query->bindValue(":RV",$GetData[22]);
			$Query->bindValue(":WS",$GetData[30]);
			$Query->bindValue(":WD",$WindDir);
			$Query->execute();
  }
  }
  $testdGetGetData= new WeatherGetData();
  $testdGetGetData->getGetData ();
?>
