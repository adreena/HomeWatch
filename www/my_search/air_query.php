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
                                $sensor=$_GET['id'][1];//"Relative_Humidity";
                                $startdate="2012-3-1 4";
                                $enddate="2012-3-1 4";
                                //Relative humidity
                                $date=array();
                                $date=make_date_axis($startdate,$enddate);
                                $relative_humidity=array();
                                $apartments=array("1","2","3","4","5","6","7","8","9","10","11","12");
                                foreach($apartments as $apartment){
                                $data= Engineer::db_pull_query($apartment, $sensor, $startdate, $enddate, $period, $phase);
                                $data=clean_query_result($data,$sensor);
                                $new_entry=array();
                                $new_entry["name"]="Apt-".$apartment;
                                $new_entry["data"]=$data;
                                array_push($relative_humidity, $new_entry);
                                }
                                clean_sum($sums);
                               // Total_HP($relative_humidity);
             }
             
            //var_dump($relative_humidity);

            

    function Total_HP($result){
        echo json_encode($result);
    }
    function clean_sum($sums){
        $result=array()
        print_r($sums);
        for($sums as $key => $values){
            $result[$key]=array();
            for($values as $item){
                echo $item;
            }
        }
        return $result;
    }
    function clean_query_result($data,$sensor){
        $result=array();
        foreach ($data as $key => $value) {
                    //echo "Data #{$counter}: ";
                   // echo "Apartment: {$key} , Sensor: {$sensor}, Value: {$data[$key][$sensor]} \n";
                    array_push($result,(float)($data[$key][$sensor]));
                   // $result[$key]= $data[$key][$sensor];
                    //echo"<br />"; 
                    //$counter+=1;
            }
            return $result;
    }
    function make_date_axis($start,$end){
        $index=abs(strtotime($start) - strtotime($end))/ (60 * 60 * 24);
        $date=$start;
        $result=array();
        array_push($result, $start);
        for($i=1;$i<=$index;$i++){
           list($y,$m,$d)=explode('-',$date);
           $date = Date("Y-m-d", mktime(0,0,0,$m,$d+1,$y));
           
           array_push($result, $date);
            }
        return $result;
    }
?>


