<?php
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
 
            

            if($_GET['id'][0]=="hourly") {
    
                $period ="Hourly";
                 
                
                $startdate=$_GET['id'][1];
                $enddate=$_GET['id'][2];
                $sensor=$_GET['id'][3];
                
                $period="Hourly";
                
                $result=[];

                $apartments=array("1","2","3","4","5","6","7","8","9","10","11","12");
                $mod_apts=array("Apt.1","Apt.2","Apt.3","Apt.4","Apt.5","Apt.6","Apt.7","Apt.8","Apt.9","Apt.10","Apt.11","Apt.12");
                $categories=[0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23];
                foreach($mod_apts as $apartment) {
                    $result[$apartment]=[];
                }
                //print_r($result);
                foreach ($apartments as $apartment) {
                    //echo "Apt.{$apartment}\n";
                    $tempApt="Apt.{$apartment}";
                    $data=Engineer::db_pull_query($apartment, $sensor, $startdate, $enddate, $period, $phase);
                    $result= daily_clean_query_result($data,$sensor,$apartment,$result);
                    
                }
                if ($sensor=="Temperature")
                    $sensor='Indoor_Temperature';

               $finalResult=[];
                $finalResult['query']=$result;
                $finalResult['categories']=$categories;
                $finalResult['source']=$sensor;

                $finalResult['yTitle']='';
                echo json_encode($finalResult);

                                
             }
             
 
    function daily_clean_query_result($data,$sensor,$apartment,$result){
        foreach ($data as $key => $value) {
                    $time=explode(":", $key);
                    $time=$time[1];

                    $tempApt="Apt.{$apartment}";
                    $temp=round((float)($data[$key][$sensor]),2);
                    //echo "Apratment:{$apartment}, {$key}, {$time}, Value: {$temp} \n";
                    
                    array_push($result[$tempApt],$temp);
                    
                    
            }
           return $result;
    }
            

?>