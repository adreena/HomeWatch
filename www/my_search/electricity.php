<?php
/* 
----Query Descriptions:
** 1. id=='apt-dashboard' : yearly data for each apartment shown on dashboard
** 2. id=='pie-calendar': monthly data for all aparatements in pie-calendar
** 3. id=='stack-bars'  : daily data for each apartment in stacked bars
** 3. id=='hourly': is the hourly data for electricity consumption -> daily.html
** 4. id=='apartment': HighStock individual paratment screens 

----Heleper clean_Functions 

This set of functions helps processing results as the proper format for HighChart charts
** 1. clean_electricity_result: cleans dashboard electricity data
** 2. clean_energy_result: cleans dashboard energy data
** 3. clean_apartment_result: helper function for apartment screening
** 4. clean_hourly_result: helper function for hourly charts Electricity consumptions
** 5. clean_pie_result: cleans data for each pie of calendar
** 6. clean_pie_result: cleans data to display on pie-calendar
** 7. make_time_axis : prepared data and time for pie-calendar and stack-bars
** 8. process_sensors: merges some of the channels for stackbars
** 9. apartment_result: processes data for highstocks in apartment screen
*/
            namespace UASmartHome;

            date_default_timezone_set("America/Edmonton");

            require_once __DIR__ . '/../vendor/autoload.php';
            require_once __DIR__. '/../lib/UASmartHome/EquationParser.php';
            require_once __DIR__. '/../lib/UASmartHome/Alerts.php';
            require_once __DIR__ . '/../lib/UASmartHome/Auth/Firewall.php';

            use \UASmartHome\Auth\Firewall;
            Firewall::instance()->restrictAccess(Firewall::ROLE_ENGINEER, Firewall::ROLE_MANAGER);

            use \UASmartHome\Database\Engineer;
            use \UASmartHome\Database\Engineer2;
            use \UASmartHome\Database\Configuration\ConfigurationDB;
            use \UASmartHome\EquationParser;
            header('Content-Type: application/json; charset=utf-8');
 

            $phaseMapping = array("Mains (Phase A)" => "A", "Bedroom and hot water tank (Phase A)" => "A", "Oven (Phase A) and range hood" => "A", "Microwave" => "A", "Electrical duct heating" => "A", "Kitchen plugs (Phase A) and bathroom lighting" => "A", "Energy recovery ventilation" => "A", "Mains (Phase B)" => "B", "Kitchen plugs (Phase B) and kitchen counter" => "B", "Oven (Phase B)" => "B", "Bathroom" => "B", "Living room and balcony" => "B",  "Hot water tank (Phase B)" => "B", "Refrigerator" => "B");

            $channelMapping = array("Mains (Phase A)" => "Ch1", "Bedroom and hot water tank (Phase A)" => "Ch2", "Oven (Phase A) and range hood" => "AUX1", "Microwave" => "AUX2", "Electrical duct heating" => "AUX3", "Kitchen plugs (Phase A) and bathroom lighting" => "AUX4", "Energy recovery ventilation" => "AUX5", "Mains (Phase B)" => "Ch1", "Kitchen plugs (Phase B) and kitchen counter" => "Ch1", "Oven (Phase B)" => "AUX1", "Bathroom" => "AUX2", "Living room and balcony" => "AUX3",  "Hot water tank (Phase B)" => "AUX4", "Refrigerator" => "AUX5");
            $listElectricity=array("Bedroom and hot water tank (Phase A)", "Oven (Phase A) and range hood", "Microwave", "Electrical duct heating", "Kitchen plugs (Phase A) and bathroom lighting", "Energy recovery ventilation", "Kitchen plugs (Phase B) and kitchen counter", "Oven (Phase B)", "Bathroom" , "Living room and balcony",  "Hot water tank (Phase B)", "Refrigerator");
            $listPhases=array("Mains (Phase A)","Mains (Phase B)");


   
        if($_GET['id'][0]==="apt-dashboard") {
           
                $period="Monthly";
                //time is manually fixed for year 2013
                $startdate="2013-01-01 2";
                $enddate="2013-12-30 2";

                $monthMapping=array("01"=>"January","02"=>"February","03"=>"March","04"=>"April","05"=>"May","06"=>"June","07"=>"July","08"=>"August","09"=>"September","10"=>"October","11"=>"November","12"=>"December");
                $cats=array("Energy (kJ)","Electricity (kWh)");
                $apts=array("Apt.1","Apt.2","Apt.3","Apt.4","Apt.5","Apt.6","Apt.7","Apt.8","Apt.9","Apt.10","Apt.11","Apt.12");
                $months=array("January","February","March","April","May","June","July","August","September","October","November","December");
                $date=array();
                $energy=array();
                $elec=array();
                
                $apartments=array("1","2","3","4","5","6","7","8","9","10","11","12");

                foreach ($months as $month) {
                    array_push($energy,0);
                    array_push($elec,0);
                }

                // 1- Energy Consumption
                $sensor="Total_Energy"; 
                $apartment=explode(".",$_GET['id'][1]);
                $apartment=$apartment[1];
                $data= Engineer::db_pull_query($apartment, $sensor, $startdate, $enddate, $period, $phase);
                $energy=clean_energy_result($data,$sensor,$apartment,$energy,$monthMapping);

                // 2-Electricity Consumption
                for($i=0;$i<count($listElectricity);$i++) {

                    $sensor=$channelMapping[$listElectricity[$i]];
                    $phase=$phaseMapping[$listElectricity[$i]];
                    $data= Engineer::db_pull_query($apartment, $sensor, $startdate, $enddate, $period, $phase);
                    $elec=clean_electricity_result($data,$sensor,$apartment,$elec,$monthMapping); 
                }

                $result=array();
                $result['data']=array();

                $result['data']['elec']=$elec;
                $result['data']['energy']=$energy;
                $result['categories']=$months;
                $result['x']=$apartment;

                echo json_encode($result);
        }
 
        else if($_GET['id'][0]==="pie-calendar") {
                                
                
                $period="Daily";
                $startdate=(string)$_GET[id][1];
                $enddate=(string)$_GET[id][2];
                $startdate=$startdate." 4";
                $enddate=$enddate." 4";
               
                $apts=array("Apt.1","Apt.2","Apt.3","Apt.4","Apt.5","Apt.6","Apt.7","Apt.8","Apt.9","Apt.10","Apt.11","Apt.12");
                
                $date=array();
                $sums=array();
                
                $apartments=array("1","2","3","4","5","6","7","8","9","10","11","12");
                list($dates,$sums)=make_time_axis($startdate,$enddate,$apartments,$sums,$listPhases);
                
                
                //calculating the sum of phase A and phase B
                for($i=0;$i<count($listPhases);$i++) {
                    $sensor=$channelMapping[$listPhases[$i]];
                     $phase=$phaseMapping[$listPhases[$i]];
                     $elecSource=$listPhases[$i];

                    foreach($apartments as $apartment){
                        $data= Engineer::db_pull_query($apartment, $sensor, $startdate, $enddate, $period, $phase);
                        $sums=clean_pie_result($data,$sensor,$apartment,$elecSource,$sums);

                    }
                }
                $sums=clean_calendar_result($sums);

                $result=array();
                $result['sum']=$sums;
                $result['categories']=$apts;
                $result['yTitle']="Electrical Usage";
                $result['measure']='(kWh)';
                echo json_encode($result);
             }

        else if($_GET[id][0]==="stack-bars"){
                    $period="Daily";
                    $processQuery=array();
                    $startdate=(string)$_GET[id][1];
                    $enddate=(string)$_GET[id][2];
                    $startdate=$startdate." 4";
                    $enddate=$enddate." 4";
                    
                    $apts=array("Apt.1","Apt.2","Apt.3","Apt.4","Apt.5","Apt.6","Apt.7","Apt.8","Apt.9","Apt.10","Apt.11","Apt.12");
                    

                    $apartments=array(1,2,3,4,5,6,7,8,9,10,11,12);
                    list($dates,$sums)=make_time_axis($startdate,$enddate,$apartments,$sums,$listElectricity);
                    

                    for($i=0;$i<count($listElectricity);$i++) {
                         $sensor=$channelMapping[$listElectricity[$i]];
                         $phase=$phaseMapping[$listElectricity[$i]];
                         $elecSource=$listElectricity[$i];
                        foreach($apartments as $apartment){
                            $data= Engineer::db_pull_query($apartment, $sensor, $startdate, $enddate, $period, $phase);
                            $processQuery=process_sensors($data,$sensor,$apartment,$elecSource,$processQuery);
                            
                        }
                    }
                   
                    $result=array();
                    $result['query']=$processQuery;
                    $result['categories']=$apts;
                    $result['yAxis']="Electrical Usage (kWh)";
                    echo json_encode($result);

        }
             
        else if($_GET['id'][0]==="hourly"){
                
                $startdate=$_GET['id'][1];
                $enddate=$_GET['id'][2];
                $source=$_GET['id'][3];
                $phase=$phaseMapping[$source];
                $sensor=$channelMapping[$source];
                $period="Hourly";
                
                $result=[];

                $apartment_numbers=array("1","2","3","4","5","6","7","8","9","10","11","12");
                $aptartments=array("Apt.1","Apt.2","Apt.3","Apt.4","Apt.5","Apt.6","Apt.7","Apt.8","Apt.9","Apt.10","Apt.11","Apt.12");
                $categories=[0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23]; //24hour

                foreach($aptartments as $apartment) {
                    $result[$apartment]=[];
                }

                foreach ($apartment_numbers as $apartment) {
                   
                    $tempApt="Apt.{$apartment}";
                    $data=Engineer::db_pull_query($apartment, $sensor, $startdate, $enddate, $period, $phase);
                    $result= clean_hourly_result($data,$sensor,$apartment,$result);
                }
                $finalResult=[];
                $finalResult['query']=$result;
                $finalResult['categories']=$categories;
                $finalResult['source']=$source;
                $finalResult['yTitle']="Electrical Usage (kWh)";
                echo json_encode($finalResult);

        }

        else if($_GET['id'][0]==="apartment"){
                //time is manually set to year 2013
                $startdate="2013-01-01 0";
                $enddate="2013-12-30 23";
                $source=$_GET['id'][1];
                $tempApt=$_GET['id'][2];
                $result=[];
                $sources=[];
                $apartment_numbers=array("1","2","3","4","5","6","7","8","9","10","11","12");
                $apartments=array("Apt.1","Apt.2","Apt.3","Apt.4","Apt.5","Apt.6","Apt.7","Apt.8","Apt.9","Apt.10","Apt.11","Apt.12");
                $categories=[0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23];
                foreach($apartments as $apartment) {
                    $result[$apartment]=[];
                }
                $double=false;
                $apartment=explode(".",$tempApt)[1];
                
                //there are a couple of sources to sum up the values
                if($source ==="Hot water tank"){
                    array_push($sources, "Bedroom and hot water tank (Phase A)");
                    array_push($sources, "Hot water tank (Phase B)");    
                }else if($source ==="Kitchen"){
                    array_push($sources, "Kitchen plugs (Phase A) and bathroom lighting");
                    array_push($sources, "Kitchen plugs (Phase B) and kitchen counter");    
                }else if($source ==="Oven"){
                    array_push($sources, "Oven (Phase A) and range hood");
                    array_push($sources, "Oven (Phase B)");    
                }else{
                   array_push($sources,$source);  
                }


                
                foreach($sources as $source){              
                        $phase=$phaseMapping[$source];
                        $sensor=$channelMapping[$source];
                        $startdate="2013-01-01 0";
                        $enddate="2013-12-30 23";
                        $period="Hourly";
                        $data=Engineer::db_pull_query($apartment, $sensor, $startdate, $enddate, $period, $phase);
                        $result=apartment_result($data,$sensor,$tempApt,$result,$double);
                        $double=true;
                }

                $result[$tempApt]=clean_apartment_result($result[$tempApt]);

                //data needs to be sorted because highcharts is complaining about error#15
                usort($result[$tempApt], function($a, $b) {
                    return $a[0] - $b[0];
                });
                
                echo json_encode($result[$tempApt]);
        }


/* Heleper clean_Functions 
This set of functions helps processing results as the proper format for HighChart charts
** 1. clean_electricity_result:
** 2. clean_energy_result: cleans dashboard energy
** 3. clean_apartment_result: helper function for apartment screening
** 4. clean_hourly_result: helper function for hourly charts Electricity consumptions
** 5. clean_calendar_result:
** 6. clean_pie_result:
*/

    function clean_electricity_result($data,$sensor,$apartment,$result,$monthMapping){
        foreach ($data as $key => $value) {
                    $sp=explode("-",$key);
                    $sp=intval($sp[1])-1;
                    $apt="Apt.{$apartment}"; 
                    $temp=round((float)($data[$key][$sensor]),2);
                    $result[$sp]+=$temp;
            }
           return $result;
    }
    function clean_energy_result($data,$sensor,$apartment,$result,$monthMapping){
        foreach ($data as $key => $value) {
                    $sp=explode("-",$key);
                    $sp=intval($sp[1])-1;
                    $apt="Apt.{$apartment}";
                    $temp=round((float)($data[$key][$sensor]),2);
                    $result[$sp]=$temp;
            }
           return $result;
    }
    function clean_apartment_result($result){
        $finalResult=[];
        foreach($result as $time=> $value){
            $temp=[];
            $temp[0]=$time;
            $temp[1]=$value;
            array_push($finalResult, $temp);
        }
        return $finalResult;
    }
    
    function clean_hourly_result($data,$sensor,$apartment,$result){
        foreach ($data as $key => $value) {
                    $time=explode(":", $key);
                    $time=$time[1];
                    $tempApt="Apt.{$apartment}";
                    $temp=round((float)($data[$key][$sensor]),2);                    
                    array_push($result[$tempApt],$temp);
            }
           return $result;
    }

    function clean_calendar_result($sums){
        $result=array();

        foreach($sums as $key => $values){
            $result[$key]=array();
            foreach($values as $apt => $sum){
                $temp=[];
                $temp[0]=$apt;
                $temp[1]=$sum;
                array_push($result[$key], $temp);
            }
            
        }
        return $result;
    }

    function clean_pie_result($data,$sensor,$apartment,$elecSource,$sums){
        foreach ($data as $key => $value) {
                    $date=substr($key,0,10);
                    $apt="Apt.{$apartment}";
                    $temp=[];
                    $temp[0]=$elecSource;
                    $temp[1]=round((float)($data[$key][$sensor]),2);
                    
                    $x=$temp[1];
                    $sums[$date][$apt]+=$x;
                    array_push($result[$date][$apt],$temp);
            }
           return $sums;
    }
 

    function process_sensors($data,$sensor,$apartment,$elecSource,$processQuery){
        foreach ($data as $key => $value) {
                        $date=substr($key,0,10);
                        $apt="Apt.{$apartment}";
                        
                      
                        $v=(float)($data[$key][$sensor]);
                        $extra=0;

                        //merging some of the channels
                        if($elecSource==="Bedroom and hot water tank (Phase A)" || $elecSource==="Hot water tank (Phase B)"){
                            $elecSource="Bedroom and Hot water tank";
                            
                            $extra=$processQuery[$elecSource][$apartment-1];
                        }elseif($elecSource==="Oven (Phase A) and range hood" || $elecSource==="Oven (Phase B)"){
                            $elecSource="Oven and Range Hood";
                             $extra=$processQuery[$elecSource][$apartment-1];


                        }elseif($elecSource==="Kitchen plugs (Phase A) and bathroom lighting" || $elecSource==="Kitchen plugs (Phase B) and kitchen counter"){
                            $elecSource="Kitchen plugs, Bathroom Lighting and Kitchen Counter";
                            $extra=$processQuery[$elecSource][$apartment-1];

                        }
                        $v+=$extra;
                        $processQuery[$elecSource][$apartment-1]=round($v,3);
                        

                        
                }
        return $processQuery;

    }
    function apartment_result($data,$sensor,$apartment,$result,$double){


        foreach ($data as $key => $value) {
                    $date=substr($key,0,10);
                    $temp=[];
                    $split=explode(":",$key);
                    $split[1]=$split[1].":00:00"; //transforming time to proper format of 00:00:00
                    $newDate=$split[0]." ".$split[1];
                    
                    $time=strtotime($newDate)*1000;
                    $temp=round((float)($data[$key][$sensor]),2);
                    if($double===false){
                      $result[$apartment][$time]=$temp;
                   }else if ($double===true){
                        if($result[$apartment][$time]!=null){
                           
                            $result[$apartment][$time]+=$temp;
                        }
                        else{
                           
                            $result[$apartment][$time]=$temp;
                        }

                    }                    
            }
       
        return $result;
    }
    // Enumerating all dates
    function make_time_axis($start,$end,$apartments,$sums,$listElectricity){
        $end=substr($end,0,10);
        $start=substr($start,0,10);
        $diff=abs(strtotime($end) - strtotime($start));
        $years = floor($diff / (365*60*60*24));
        $months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));
        $days = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));
        //echo "days are: {$days} \n";
        $result=array();
        
        $result[$start]=array();
        
        $date=$start;
        for($i=0;$i<=$days;$i++){
           list($y,$m,$d)=explode('-',$date);
           $sums[$date]=array();
           $result[$date]=array();
             
           
           for($j=0;$j<count($apartments);$j++){
                $a=$apartments[$j];
                $Apt="Apt.{$a}";
                $sums[$date][$Apt]=0;
                $result[$date][$Apt]=array();
           }
           $date = Date("Y-m-d", mktime(0,0,0,$m,$d+1,$y));
        }

        //var_dump($result);
        return array($result,$sums);
    }

?>


