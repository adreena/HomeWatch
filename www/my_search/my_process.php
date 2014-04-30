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

            
            /*          "Total_HP"=>"TotalElec",
                        "Total_P1"=>"TotalElec",
                        "P11"=>"BasEnergy",
                        "P12"=>"BasEnergy",
                        "HP1"=>"BasEnergy",
                        "HP2"=>"BasEnergy",
                        "HP3"=>"BasEnergy",
                        "HP4"=>"BasEnergy",
                        "Relative_Humidity"=>"Air",
                        "Outside_Temperature"=>"OutsideTemp",
                        "Temperature" => "Air",
                        "CO2"=>"Air", 
                        "Hot_Water"=>"Water", 
                        "Total_Water"=>"Water",
                        "HeatFlux_Insulation"=>"Heat_Flux", 
                        "HeatFlux_Stud"=>"Heat_Flux", 
                        "Current_Flow"=>"Heating_Water",
                        "Current_Temperature_1"=>"Heating_Water",
                        "Current_Temperature_2"=>"Heating_Water",
                        "Total_Mass"=>"Heating", 
                        "Total_Energy"=>"Heating", 
                        "Total_Volume"=>"Heating",
                        "Phase"=>"El_Energy", 
                        "Ch1"=>"El_Energy", 
                        "Ch2"=>"El_Energy", 
                        "AUX1"=>"El_Energy",
                        "AUX2"=>"El_Energy", 
                        "AUX3"=>"El_Energy", 
                        "AUX3"=>"El_Energy",
                        "AUX4"=>"El_Energy",
                        "AUX5"=>"El_Energy", 
                        "Wind_Speed"=>"Weather_Forecast", 
                        "Wind_Direction" => "Weather_Forecast"
            */

            /*  Total HP for 1 Apartment           
            $period ="Hourly";
            $apartment="1";
            $sensor="Total_HP";
            $startdate="2013-3-20 0";
            $enddate="2013-3-20 10";*/

           


            

            //data for aprtment 5
           // $apartment="5";
            //$data[$apartment] = Engineer::db_pull_query($apartment, $sensor, $startdate, $enddate, $period, $phase);
            //var_dump($data);
            //echo "\n";

           // var_dump($data);
            //echo "* \n";
            /*foreach ($data as $key => $value) {
                    //echo "Data #{$counter}: ";
                    $temp=array($key,$data[$key]);
                   // echo "Date: {$temp[0]} , Value: {$temp[1]} \n";
                   $result[$key]= $data[$key][$sensor];
                    //echo"<br />"; 
                    //$counter+=1;
            }*/

                        //$pick="rel";
                       // $pick="hpumps";
                       // $pick="energy";
            //echo $_GET['query'];
                        if($_GET['query']==''){
                                $period ="Daily";
                                $sensor="Relative_Humidity";
                                $startdate="2012-3-1 4";
                                $enddate="2012-3-3 4";
                                //Relative humidity

                                $relative_humidity=array();

                                $apartment="1";
                                $data= Engineer::db_pull_query($apartment, $sensor, $startdate, $enddate, $period, $phase);
                                $data=clean_query_result($data,$sensor);
                                $relative_humidity[$apartment]=$data;
                                
                                $apartment="2";
                                $data= Engineer::db_pull_query($apartment, $sensor, $startdate, $enddate, $period, $phase);
                                $data=clean_query_result($data,$sensor);
                                $relative_humidity[$apartment]=$data;

                                $apartment="3";
                                $data= Engineer::db_pull_query($apartment, $sensor, $startdate, $enddate, $period, $phase);
                                $data=clean_query_result($data,$sensor);
                                $relative_humidity[$apartment]=$data;


                                $apartment="4";
                                $data= Engineer::db_pull_query($apartment, $sensor, $startdate, $enddate, $period, $phase);
                                $data=clean_query_result($data,$sensor);
                                $relative_humidity[$apartment]=$data;
                                Total_HP($relative_humidity);

                            }
                        elseif($_GET['query']=="#electricity-summary"){
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
                        elseif($_GET['query']=='#energy-summary'){
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
        
            //var_dump($relative_humidity);

            

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


