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


            $elecBAS=array("Total_P1"=>"Total Geo Circ. Pump","Total_HP"=>"Total Heat Pumps","P11"=>"Geo Circ. Pump 2","P12"=>"Geo Circ. Pump 1","HP1"=>"Heat Pump 1","HP2"=>"Heat Pump 2","HP3"=>"Heat Pump 3", "HP4"=>"Heat Pump 4");
         
/* Query Descriptions
   1. id=='total elect' : BAS Screen Total Electricity HighStock
   2. id=='pie-elect'   : BAS Screen Pie of cost/usage Total Electricity per source
   3. id=='total-energy': BAS Screen Total Energy HighStock
   4. id=='pie-energy'  : BAS Screen Pie of cost/usage Total Energy per source
   5. id=='bas-dash'    : Dashboard BAS charts

*/
          if($_GET['id'][0]=="total-elec") {
                                
                        $period="Hourly";
                       // time is now fixed and manually adjusted
                        $startdate="2013-01-01 0";
                        $enddate="2013-11-18 23";
                        $sensor=$_GET['id'][1];
                        $result=array();
                        $result[$sensor]=array();
                        $data= Engineer::db_pull_query('',$sensor, $startdate, $enddate, $period, $phase);
                        $result=BAS_Electricity($data,$sensor,$result);              
                        $finalResult=array();
                        $finalResult['result']=$result;

                        //sorting time , Highcharts error#15
                        usort($result[$sensor], function($a, $b) {
                            return $a[0] - $b[0];
                        });
                       echo json_encode($finalResult);
             }
             
            else if($_GET['id'][0]=="pie-elec") {
                                
                        //$period ="Daily";
                        $period="Hourly";
                        $startdate=$_GET['id'][1];//"2013-05-01 0";//
                        $enddate=$_GET['id'][2];//"2013-05-1 23";//
                        $sensors=array("HP1","HP2","HP3","HP4","P11","P12");//$_GET['id'][1];
                        $result=array();
                        $cost=array();
                        foreach($sensors as $sensor){
                            $result[$sensor]=0;
                            $cost[$sensor]=0;
                        }
                        foreach($sensors as $sensor){
                            $data= Engineer::db_pull_query('',$sensor, $startdate, $enddate, $period, $phase);
                            list($result,$cost)=bas_pie_elec($data,$sensor,$result,$cost); 
                        } 
                     //   print_r($result);
                        $finalResult=[];
                        $finalResult['elec']=array();
                        $finalResult['cost']=array();
                        $finalResult['totalCost']=array();
                        $finalResult['totalElec']=array();
                        $elecSum=0;
                        foreach($result as $s => $v){
                            $temp=[];
                            $temp[0]=$s;
                            $temp[1]=$v;
                            $elecSum+=$temp[1];
                            array_push($finalResult['elec'],$temp);
                        } 
                        $elecSum=round($elecSum,2);
                        array_push($finalResult['totalElec'],$elecSum);

                        $costSum=0;
                        foreach($cost as $s => $v){
                            $temp=[];
                            $temp[0]=$s;
                            $temp[1]=($v)*0.1;///(3600*1000);
                            $costSum+=$temp[1];
                            array_push($finalResult['cost'],$temp);
                        } 
                        $costSum=round($costSum,2);
                        array_push($finalResult['totalCost'],$costSum);
                     // print_r($finalResult);           
                     echo json_encode($finalResult);
             }


            else if ($_GET['id'][0] == "total-energy") {

                $dateFormat = 'Y-m-d G';
                $startdate="2013-01-01 0";//
                $enddate="2013-11-18 23";//

                $d1 = date_create_from_format($dateFormat, $startdate);
                $d2 = date_create_from_Format($dateFormat, $enddate);
                $period="Hourly";
                $sources=array("Energy1","Energy2","Energy3","Energy4","Energy5","Energy6","Energy7");
                $energyColumns = Engineer2::getEnergyColumns();
                $source=$_GET['id'][1];
                $result=array(); 
                $result[$energyColumns[$source]]=array();
            
                $data = Engineer2::getEnergyColumnData($d1, $d2,$source, $period);
                $result=BAS_Energy($data,$energyColumns[$source],$result); 
                
                // Sorting Highcharts error#15
                usort($result[$source], function($a, $b) {
                    //echo "here";
                    return $a[0] - $b[0];
                });
                $finalResult=array();
                $finalResult['result']=array();;
                $finalResult['result']= $result;
                echo json_encode($finalResult);
                
             }
             else if($_GET['id'][0]=="pie-energy") {
                        $dateFormat = 'Y-m-d G';
                        $period="Hourly";
                        $startdate=$_GET['id'][1];//
                        $enddate=$_GET['id'][2];//
                        $d1 = date_create_from_format($dateFormat, $startdate);
                        $d2 = date_create_from_Format($dateFormat, $enddate);
                        $sensors=array("Energy1","Energy2","Energy3","Energy5","Energy6");//$_GET['id'][1];
                        $result=array();
                        $cost=array();
                        $energyColumns = Engineer2::getEnergyColumns();
                        foreach($sensors as $sensor){
                            $result[$energyColumns[$sensor]]=0;
                            $cost[$energyColumns[$sensor]]=0;
                        }
                        foreach($sensors as $sensor){
                            $data = Engineer2::getEnergyColumnData($d1, $d2,$sensor, $period);
                           list($result,$cost)=bas_pie_energy($data,$energyColumns[$sensor],$result,$cost); 
                        } 
                

                        
                        $finalResult=[];
                        $finalResult['energy']=array();
                        $finalResult['cost']=array();
                        $finalResult['totalCost']=array();
                        $finalResult['totalEnergy']=array();
                        $elecSum=0;
                        foreach($result as $s => $v){
                            $temp=[];
                            $temp[0]=$s;
                            $temp[1]=$v;
                            $elecSum+=$temp[1];
                            array_push($finalResult['energy'],$temp);
                        } 
                        $elecSum=round($elecSum,2);
                        array_push($finalResult['totalEnergy'],$elecSum);

                        $costSum=0;
                        foreach($cost as $s => $v){
                            $temp=[];
                            $temp[0]=$s;
                            $temp[1]=($v)*0.1;///(3600*1000);
                            $costSum+=$temp[1];
                            array_push($finalResult['cost'],$temp);
                        } 
                        $costSum=round($costSum,2);
                        array_push($finalResult['totalCost'],$costSum);
                          
                     echo json_encode($finalResult);
             }
            else if($_GET['id'][0]==="bas-dash") {
                                
                        $period="Monthly";
                        $startdate="2013-01-01 2";
                        $enddate="2013-11-18 2";
                        $sensors=array("HP1","HP2","HP3","HP4","P11","P12");
                        $months=array("January","February","March","April","May","June","July","August","September","October","November","December");
                        $energy=array();
                        $elec=array();
                        foreach ($months as $month) {
                            array_push($energy,0);
                            array_push($elec,0);
                        }

                        // 1- Elec total Monthly
                        foreach($sensors as $sensor){
                            $result[$sensor]=array();
                            $data= Engineer::db_pull_query('',$sensor, $startdate, $enddate, $period, $phase);
                            $elec=dashboard_ElecEnergy($data,$sensor,$elec);
                       }

                        $dateFormat = 'Y-m-d G';
                        $d1 = date_create_from_format($dateFormat, $startdate);
                        $d2 = date_create_from_Format($dateFormat, $enddate);
                        $period="Monthly";
                        $sensors=array("Energy4","Energy5","Energy6","Energy7");
                        $energyColumns = Engineer2::getEnergyColumns();
                        foreach($sensors as $sensor){
                            $data = Engineer2::getEnergyColumnData($d1, $d2,$sensor, $period);
                            $energy=dashboard_ElecEnergy($data,$energyColumns[$sensor],$energy); 
                        }
                       $result=array();
                       $result['data']=array();
                       $result['data']['energy']=$energy;
                       $result['data']['elec']=$elec;
                       echo json_encode($result);
             }
        

    function dashboard_ElecEnergy($data,$sensor,$result){

        foreach ($data as $key => $value) {
            $date=substr($key,0,10);
            $sp=explode("-",$key);
            $sp=intval($sp[1])-1;
            $temp=round((float)($data[$key][$sensor]),2);
            $result[$sp]+=$temp;                       
        }
       
        return $result;
    }
    function bas_pie_energy($data,$sensor,$result,$cost){
       
        foreach ($data as $key => $value) {
            $date=substr($key,0,10);
            $temp=[];
            $result[$sensor]+=round((float)($data[$key]),2);
            $cost[$sensor]+=(float)($data[$key]);
        }
       
        return array($result,$cost);
    }
    function bas_pie_elec($data,$sensor,$result,$cost){

        foreach ($data as $key => $value) {
            $date=substr($key,0,10);
            $temp=[];
            $result[$sensor]+=round((float)($data[$key][$sensor]),2);
            $cost[$sensor]+=(float)($data[$key][$sensor]);
        }
        return array($result,$cost);
    }
    function BAS_Energy($data,$sensor,$result){

        foreach ($data as $key => $value) {
            $date=substr($key,0,10);
            $temp=[];
            $split=explode(":",$key);
            $split[1]=$split[1].":00:00"; //transforming time to proper format of 00:00:00
            $newDate=$split[0]." ".$split[1];
            $temp[0]=strtotime($newDate)*1000;
            $temp[1]=round((float)($data[$key]),2);
            array_push($result[$sensor],$temp);                      
        }
       
        return $result;
    }
    
    

    function BAS_Electricity($data,$sensor,$result){

        foreach ($data as $key => $value) {
            $date=substr($key,0,10);
            $temp=[];
            $split=explode(":",$key);
            $split[1]=$split[1].":00:00"; //transforming time to proper format of 00:00:00
            $newDate=$split[0]." ".$split[1];
            $temp[0]=strtotime($newDate)*1000;
            $temp[1]=round((float)($data[$key][$sensor]),2);
            array_push($result[$sensor],$temp);
        }
       
        return $result;
    }


?>


