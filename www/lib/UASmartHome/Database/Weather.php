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
  $conn=new Connection ();
    $MonthList = array("January"=>"1", "February" => "2", "March"=>"3", 
	"April"=>"4", "May"=>"5", "June"=>"6", 
	"July"=>"7", "August"=>"8", "September"=>"9", 
	"October"=>"10",  "November"=>"11", "December"=>"12");
	$Times = array("01:00"=>"13:00", "02:00" => "14:00", "03:00"=>"15:00", 
	"04:00"=>"16:00", "05:00"=>"17:00", "06:00"=>"18:00", 
	"07:00"=>"19:00", "08:00"=>"20:00", "09:00"=>"21:00", 
	"10:00"=>"22:00",  "11:00"=>"23:00");
	$Time="00:00";
	if ($GetData[6]=="PM")
	{
	$Time=$Times[$GetData[5]];
	}else {$Time=$GetData[5];}
	
	 $Month = $MonthList[$GetData[10]];
	 $WindDir;
	 if ($GetData[30]==0) 
	 {
	 $WindDir=" ";
	 }else {$WindDir=$GetData[29];}
	 
      $Query=$conn->connect()->prepare("INSERT INTO `Weather_Forecast` (`Year`,`Month`,`Day`,`Hour`,`External_Temperature`
	  ,`External_Relative_Humidity`,`Wind_Speed`,`Wind_Direction`) VALUES (:YR,:MT,:DY,:HR,:TP,:RV,:WS,:WD)");
			$Query->bindValue(":YR",$GetData[11]);
			$Query->bindValue(":MT",$Month);
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