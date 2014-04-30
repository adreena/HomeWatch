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

    $sensor = array();
   //parsing out the components of the graph

        

        //echo json_encode($_GET["id"]);
        //$startdate = $query['startdate']; //Startdate input: yyyy-mm-dd (for non-hourly) or yyyy-mm-dd:h (for hourly)
        //$enddate = $query['enddate']; //Enddate input (same format as startdate)            //var_dump($relative_humidity);
        if($_GET['id']=="electricity-summary"){
                                $period ="Daily";
                                $startdate="2013-4-1 0";
                                $enddate="2013-4-1 0";

                                $energy=array();

                                $sensor="HP1";
                                $data= Engineer::db_pull_query($apartment, $sensor, $startdate, $enddate, $period, $phase);
                                $data=clean_query_result($data,$sensor);
                                $energy[$sensor]=$data;

                                $sensor="HP2";
                                $data= Engineer::db_pull_query($apartment, $sensor, $startdate, $enddate, $period, $phase);
                                $data=clean_query_result($data,$sensor);
                                $energy[$sensor]=$data;

                                $sensor="HP3";
                                $data= Engineer::db_pull_query($apartment, $sensor, $startdate, $enddate, $period, $phase);
                                $data=clean_query_result($data,$sensor);
                                $energy[$sensor]=$data;

                                $sensor="HP4";
                                $data= Engineer::db_pull_query($apartment, $sensor, $startdate, $enddate, $period, $phase);
                                $data=clean_query_result($data,$sensor);
                                $energy[$sensor]=$data;
                                Total_HP($energy);
                                //var_dump($energy);

                        }
                        elseif($_GET['id']=='energy-summary'){
                                $sensor ="Total_Energy";
                                $startdate="2012-3-1 1";
                                $enddate="2012-3-31 10";
                                $period="Yearly";
                                $energy=array();


                                $apartment="1";
                                $data= Engineer::db_pull_query($apartment, $sensor, $startdate, $enddate, $period, $phase);
                                $data=clean_query_result($data,$sensor);
                                $energy[$apartment]=$data;

                                $apartment="2";              
                                $data= Engineer::db_pull_query($apartment, $sensor, $startdate, $enddate, $period, $phase);
                                $data=clean_query_result($data,$sensor);
                                $energy[$apartment]=$data;

                                $apartment="3";              
                                $data= Engineer::db_pull_query($apartment, $sensor, $startdate, $enddate, $period, $phase);
                                $data=clean_query_result($data,$sensor);
                                $energy[$apartment]=$data;

                                $apartment="4";              
                                $data= Engineer::db_pull_query($apartment, $sensor, $startdate, $enddate, $period, $phase);
                                $data=clean_query_result($data,$sensor);
                                $energy[$apartment]=$data;

                                $apartment="5";              
                                $data= Engineer::db_pull_query($apartment, $sensor, $startdate, $enddate, $period, $phase);
                                $data=clean_query_result($data,$sensor);
                                $energy[$apartment]=$data;

                                $apartment="6";              
                                $data= Engineer::db_pull_query($apartment, $sensor, $startdate, $enddate, $period, $phase);
                                $data=clean_query_result($data,$sensor);
                                $energy[$apartment]=$data;

                                $apartment="7";              
                                $data= Engineer::db_pull_query($apartment, $sensor, $startdate, $enddate, $period, $phase);
                                $data=clean_query_result($data,$sensor);
                                $energy[$apartment]=$data;

                                $apartment="8";              
                                $data= Engineer::db_pull_query($apartment, $sensor, $startdate, $enddate, $period, $phase);
                                $data=clean_query_result($data,$sensor);
                                $energy[$apartment]=$data;

                                $apartment="9";              
                                $data= Engineer::db_pull_query($apartment, $sensor, $startdate, $enddate, $period, $phase);
                                $data=clean_query_result($data,$sensor);
                                $energy[$apartment]=$data;

                                $apartment="10";              
                                $data= Engineer::db_pull_query($apartment, $sensor, $startdate, $enddate, $period, $phase);
                                $data=clean_query_result($data,$sensor);
                                $energy[$apartment]=$data;


                                $apartment="11";              
                                $data= Engineer::db_pull_query($apartment, $sensor, $startdate, $enddate, $period, $phase);
                                $data=clean_query_result($data,$sensor);
                                $energy[$apartment]=$data;


                                $apartment="12";              
                                $data= Engineer::db_pull_query($apartment, $sensor, $startdate, $enddate, $period, $phase);
                                $data=clean_query_result($data,$sensor);
                                $energy[$apartment]=$data;
                                //var_dump($energy);
                                Total_HP($energy);

                        }

    function Total_HP($result){
        echo json_encode($result);
    }
    
    function clean_query_result($data,$sensor){
        $result=array();
        foreach ($data as $key => $value) {
                    //echo "Data #{$counter}: ";
                   // echo "Apartment: {$key} , Sensor: {$sensor}, Value: {$data[$key][$sensor]} \n";
                    $result[$key]=$data[$key][$sensor];
                   // $result[$key]= $data[$key][$sensor];
                    //echo"<br />"; 
                    //$counter+=1;
            }
            return $result;
    }
?>


