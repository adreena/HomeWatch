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
	 $Month = $MonthList[$GetData[10]];
      $Query=$conn->connect()->prepare("INSERT INTO `Weather_Forecast` (`Year`,`Month`,`Day`,`Hour`,`External_Temperature`
	  ,`External_Relative_Humidity`,`Wind_Speed`,`Wind_Direction`) VALUES (:YR,:MT,:DY,:HR,:TP,:RV,:WS,:WD)");
			$Query->bindValue(":YR",$GetData[11]);
			$Query->bindValue(":MT",$Month);
			$Query->bindValue(":DY",$GetData[9]);
			$Query->bindValue(":HR",$GetData[5]);
			$Query->bindValue(":TP",$Temp[0]);
			$Query->bindValue(":RV",$GetData[22]);
			$Query->bindValue(":WS",$GetData[30]);
			$Query->bindValue(":WD",$GetData[29]);
			$Query->execute();
  }
  }
  $testdGetGetData= new WeatherGetData();
  $testdGetGetData->getGetData ();
?>