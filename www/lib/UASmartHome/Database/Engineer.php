<?php namespace UASmartHome\Database; 

class Engineer {
    //This hnadles the query of the database 
    function db_pull_query($apt=null, $column, $startdate, $enddate, $period, $Phase=null) {

        $tables = array("Relative_Humidity"=>"Air","Temp"=>"OutsideTemp", "Temperature" => "Air", "CO2"=>"Air", "Hot_Water"=>"Water", "Total_Water"=>"Water", "Insulation"=>"Heat_Flux", "Stud"=>"Heat_Flux", "Current_Flow"=>"Heating_Water", "Current_Temperature_1"=>"Heating_Water", "Current_Temperature_2"=>"Heating_Water",  "Total_Mass"=>"Heating", "Total_Energy"=>"Heating", "Total_Volume"=>"Heating", "Phase"=>"El_Energy", "Ch1"=>"El_Energy", "Ch2"=>"El_Energy", "AUX1"=>"El_Energy", "AUX2"=>"El_Energy", "AUX3"=>"El_Energy", "AUX3"=>"El_Energy", "AUX4"=>"El_Energy", "AUX5"=>"El_Energy", "Wind_Speed"=>"Weather_Forecast", "Wind_Direction" => "Weather_Forecast");

        $table = $tables[$column];

        /* Assume that all dates are in a "canonical" format -- that is, 
         * prevent the server's default timezone from affecting the date. */
        date_default_timezone_set('UTC');

        if ($period === "Hourly") {
            $startdate = date_create_from_Format('Y-m-d G', $startdate);
            $enddate = date_create_from_Format('Y-m-d G', $enddate);
        }
        else {
            $startdate = date_create_from_Format('Y-m-d G', $startdate);
            $enddate = date_create_from_Format('Y-m-d G', $enddate);
        }
        $results = array();

        if ($period == "Hourly") {
            $temp = Engineer::db_query_Hourly($apt, $table, $startdate->format('Y-m-d:H'), $enddate->format('Y-m-d:H'), $column, $Phase);
            foreach($temp as $row) {
                $returnDate = date_create_from_Format('Y-m-d:G', $row["TS"]);
                $results[$returnDate->format('Y-m-d:G')][$column] = $row[$column];
            }

        } else if ($period === "Daily") {
            while ($startdate <= $enddate) {
                $temp = Engineer::db_query_Daily($apt, $table, $startdate->format('Y-m-d'), $column, $Phase);
                if (ISSET($temp[0])) {
                    $results[$startdate->format('Y-m-d:G')] = $temp[0];
                } else {
                    $results[$startdate->format('Y-m-d:G')] = 0;
                }
                $startdate->add(date_interval_create_from_date_string('1 day'));
            }
        } else if ($period == "Weekly") {
            while ($startdate <= $enddate) {
                $year = $startdate->format("Y");    
                $week = $startdate->format("W");
                $temp = Engineer::db_query_Weekly($apt, $table, $year, $week, $column, $Phase);
                if (ISSET($temp[0])) {
                    $results[$startdate->format('Y-m-d:G')] = $temp[0];
                } else {
                    $results[$startdate->format('Y-m-d:G')] = 0;
                }
                $startdate->add(date_interval_create_from_date_string('1 week'));
            }
        } else if ($period == "Monthly") {
            while ($startdate <= $enddate) {
                $year = $startdate->format("Y");
                $month = $startdate->format("n");
                $temp = Engineer::db_query_Monthly($apt, $table, $year, $month, $column, $Phase);
                if (ISSET($temp[0])) {
                    $results[$startdate->format('Y-m-d:G')] = $temp[0];
                } else {
                    $results[$startdate->format('Y-m-d:G')] = 0;
                }
                $startdate->add(date_interval_create_from_date_string('1 month')); 
            }
        } else if ($period == "Yearly") {
            $endyear = $enddate->format("Y");
            $year = $startdate->format("Y");
            while ($startdate <= $enddate) {

                $temp = Engineer::db_query_Yearly($apt, $table, $year, $column, $Phase);
                if (ISSET($temp[0])) {
                    $results[$startdate->format('Y-m-d:G')] = $temp[0];
                } else {
                    $results[$startdate->format('Y-m-d:G')] = 0;
                }
                $startdate->add(date_interval_create_from_date_string('1 year'));
                $year = $startdate->format("Y");
            }
        }
        return $results;
    }


    //Gets the Data by Yearly,this still needs to be implmented on the Front End
    public function db_query_Yearly($apt,$table,$Year,$column,$Phase=null)
    {  
        $result =array();
        $table .= '_Yearly';
        $conn=new Connection ();
        if ($Phase == null ){
            $Query=$conn->connect()->prepare("select ".$column." from ".$table." where Apt= :Apt_Num AND Year= :Year ") ;
        }else{
            if ($Phase == 'A' || 'B')
            {
                $Query=$conn->connect()->prepare("select ".$column." from ".$table." where Apt= :Apt_Num AND Year= :Year AND Phase= :PS") ;
                $Query->bindValue(":PS",$Phase);
            }}
        $Query->bindValue(":Apt_Num",$apt);
        $Query->bindValue(":Year",$Year);
        $Query->execute();
        $row_count= $Query->rowCount();
        while ($row = $Query->fetch(\PDO::FETCH_ASSOC))
        {
            $result[]=(array)$row;
        }
        $a= $Query->rowCount();
        return $result;
    }
	//Gets Data by Month
    public function db_query_Monthly($apt,$table,$Year,$Month,$column,$Phase=null)
    {
        $result =array();
        $table .= '_Monthly';
        $conn=new Connection ();
        if ($Phase ==null ){
            $Query=$conn->connect()->prepare("select ".$column." from ".$table." where Apt= :Apt_Num AND Year= :Year AND Month= :Month ") ;
        }else{
            if ($Phase == 'A' || 'B')
            {
                $Query=$conn->connect()->prepare("select ".$column." from ".$table." where Apt= :Apt_Num AND Year= :Year AND Month= :Month AND Phase= :PS ") ;
                $Query->bindValue(":PS",$Phase);
            }}
        $Query->bindValue(":Apt_Num",$apt);
        $Query->bindValue(":Year",$Year);
        $Query->bindValue(":Month",$Month);
        $Query->execute();
        $row_count= $Query->rowCount();
        while ($row = $Query->fetch(\PDO::FETCH_ASSOC))
        {
            $result[]=(array)$row;
        }
        $a= $Query->rowCount();
        return $result;
    }
	//Gets the Data by week 
    public function db_query_Weekly($apt,$table,$Year,$Week,$column,$Phase=null)
    {
        $result =array();
        $table .= '_Weekly';
        $conn=new Connection ();
        if ($Phase ==null ){
            $Query=$conn->connect()->prepare("select ".$column." from ".$table." where Apt= :Apt_Num AND Year= :Year AND Week= :Week") ;
        }else{
            if ($Phase == 'A' || 'B')
            {
                $Query=$conn->connect()->prepare("select  ".$column." from ".$table." where Apt= :Apt_Num AND Year= :Year AND Week= :Week AND Phase= :PS") ;
                $Query->bindValue(":PS",$Phase);
            }}
        $Query->bindValue(":Apt_Num",$apt);
        $Query->bindValue(":Year",$Year);
        $Query->bindValue(":Week",$Week);
        $Query->execute();
        $row_count= $Query->rowCount();
        while ($row = $Query->fetch(\PDO::FETCH_ASSOC))
        {
            $result[]=(array)$row;
        }
        $a= $Query->rowCount();
        return $result;
    }
	//Gets the data between two dates 
    public function db_query_Daily($apt,$table,$date,$column,$Phase=null)
    {

        $result =array();
        $table .= '_Daily_t';
        $conn=new Connection ();
        if ($Phase ==null ){
            $Query=$conn->connect()->prepare("select ".$column." from ".$table." where Apt= :Apt_Num AND Date= :Date ") ;
        }else{
            if ($Phase == 'A' || 'B')
            {
                $Query=$conn->connect()->prepare("select ".$column." from ".$table." where Apt= :Apt_Num AND Date= :Date AND Phase= :PS") ;
                $Query->bindValue(":PS",$Phase);
            }}
        $Query->bindValue(":Apt_Num",$apt);
        $Query->bindValue(":Date",$date);
        $Query->execute();
        $row_count= $Query->rowCount();
        while ($row = $Query->fetch(\PDO::FETCH_ASSOC))
        {
            $result[]=(array)$row;
        }
        $a= $Query->rowCount();
 
        return $result;
    }
    //Function to get the Max and Min for the graphs,Hourly
    public function db_query_Extrema($apt,$column,$table,$startdate,$enddate,$EX,$Phase=null)
    {
        $result =array();
        $table .= '_Hourly_t';
        $conn=new Connection ();
        if ($EX==1)
        { 
            if ($Phase==null){
                $Query=$conn->connect()->prepare("select MAX(".$column."),Hour from ".$table." where Apt= :Apt_Num AND Date between :SD AND :ED") ;
            }else{
                if ($Phase == 'A' || 'B')
                {
                    $Query=$conn->connect()->prepare("select MAX(".$column."),Hour from ".$table." where Apt= :Apt_Num AND Phase= :PS AND Date between :SD AND :ED") ;
                    $Query->bindValue(":PS",$Phase);

                }
            }}
        if ($EX==2)
        { 
            if ($Phase==null){
                $Query=$conn->connect()->prepare("select MIN(".$column."),Hour from ".$table." where Apt= :Apt_Num AND Date between :SD AND :ED") ;
            }else{
                if ($Phase == 'A' || 'B')
                {
                    $Query=$conn->connect()->prepare("select MIN(".$column."),Hour from ".$table." where Apt= :Apt_Num AND Phase= :PS AND Date between :SD AND :ED") ;
                    $Query->bindValue(":PS",$Phase);

                }
            }}
        $Query->bindValue(":Apt_Num",$apt);
        $Query->bindValue(":SD",$startdate);
        $Query->bindValue(":ED",$enddate);
        $Query->execute();
        $row_count= $Query->rowCount();
        while ($row = $Query->fetch(\PDO::FETCH_ASSOC))
        {
            $result[]=(array)$row;
        }

        return $result;
    }
	//Retrives the Data between two dates at a certain start hour and End hour
    public function db_query_Hourly($apt,$table,$Startdate,$EndDate,$column,$Phase=null)
    {

        $result =array();
        $table .= '_Hourly_t';
        $conn=new Connection ();
        if ($apt==null){
          
            {
                $Query=$conn->connect()->prepare("select $column, TS from ".$table." where  TS between :SD AND :ED ") ;
           	  $Query->bindValue(":SD",$Startdate);
     	          $Query->bindValue(":ED",$EndDate);
           }	
        }else {
        if ($Phase==null){
            $Query=$conn->connect()->prepare(" select $column, TS from ".$table." where Apt= :Apt_Num AND TS between :SD AND :ED ") ;
        } else {
            if ($Phase == 'A' || 'B')
            {
                $Query=$conn->connect()->prepare("select $column, TS from ".$table." where Apt= :Apt_Num AND TS between :SD AND :ED AND Phase= :PS") ;
                $Query->bindValue(":PS",$Phase);
            }
        }
        $Query->bindValue(":Apt_Num",$apt);
        $Query->bindValue(":SD",$Startdate);
        $Query->bindValue(":ED",$EndDate);
        }
        $Query->execute();
        $row_count= $Query->rowCount();
        while ($row = $Query->fetch(\PDO::FETCH_ASSOC))
        {
            $result[]=(array)$row;
        }
        $a= $Query->rowCount();
        return $result;


    }
     //This handels the default ones Co2,Relative Humidity 
    public function db_query_default_Alert ($apt,$column,$StartDate,$EndDate,$alertTable = null)
    {
        $tables = array("CO2"=>"Air_CO2_Alert", "Relative_Humidity" => "Air_Relative_Humidity_Alert", "Temperature"=>"Air_Temprature_Alert"); 

        $table = $tables[$column];

        $results = array();
        $StartDate = date_create_from_Format('Y-m-d:G', $StartDate);
        $EndDate = date_create_from_Format('Y-m-d:G', $EndDate);

        $conn=new Connection ();
        $Query=$conn->connect()->prepare("select ".$column.",Date,Hour from ".$table." where Apt= :Apt_Num AND Date Between :SD and :ED AND Hour Between :SH AND :EH ") ;
        $Query->bindValue(":Apt_Num",$apt);
        $Query->bindValue(":SD",$StartDate->format('Y-m-d'));
        $Query->bindValue(":ED",$EndDate->format('Y-m-d'));
        $Query->bindValue(":SH",$StartDate->format('G'));
        $Query->bindValue(":EH",$EndDate->format('G'));
        $Query->execute();

        while ($row = $Query->fetch(\PDO::FETCH_ASSOC))
        {
            $results[$row["Date"] . ":" . $row["Hour"]] = $row[$column];
        }

        return $results;
    }
	//This was another option for the Controller to retrieve results from the DB
    public function db_air_Period($apt,$datefrom,$dateto,$hourfrom,$hourto,$type)
    {
        $result =array();
        $errinf =array();
        $subsql ="test";
        print_r($hourfrom);
        if ($hourfrom==Null) $hourfrom=0;
        if ($hourto==Null)   $hourto=23;
        if ($type <> 5)
        {
            $hourfrom=0;
            $hourto=23;
        }
        switch ($type)
        {
            case 1:    
                $subsql = "v0_air.Year";
                break;
            case 2: 
                $subsql = "v0_air.Year,v0_air.Month";
                break;
            case 3: 
                $subsql = "v0_air.Year,v0_air.Week";
                break;
            case 4: 
                $subsql = "v0_air.Date";
                break;
            case 5: 
                $subsql = "v0_air.Date,v0_air.Hour";
                break;
        }
        //    print_r($type);
        $Querystatment="select `v0_air`.`Apt` AS `Apt`,".$subsql.",avg(`v0_air`.`Temperature`) AS `Temperature`,
            avg(`v0_air`.`Relative Humidity`) AS `Relative Humidity`,
            avg(`v0_air`.`CO2`) AS `CO2`
                from  v0_air
                where Apt= :Apt_Num AND (Date between  :datefrom and :dateto)
                AND (Hour between  :hourfrom and :hourto)
                Group by v0_air.`Apt`,".$subsql;
        //print_r($Querystatment);

        $Query=$conn->connect()->prepare($Querystatment);

        $Query->bindValue(":Apt_Num",$apt);
        $Query->bindValue(":datefrom",$datefrom);
        $Query->bindValue(":dateto",$dateto);
        $Query->bindValue(":hourfrom",$hourfrom);
        $Query->bindValue(":hourto",$hourto);
        $errinf = $Query->errorinfo();
        $Query->execute();
        $row_count= $Query->rowCount();
        array_push($result,$errinf[0]);
        array_push($result,$errinf[1]);
        array_push($result,$errinf[2]);
        while ($row = $Query->fetch(\PDO::FETCH_ASSOC))
        {
            array_push($result,$row);
        }
        array_push($result,$Query->rowCount());
        return $result;
    }


    //Gets weather data//Still front End needs to be implmented 
    public function Weather_DB_getData ($Year,$Month,$Day,$Hour)
    {
        $conn=new Connection ();
        $Query=$conn->connect()->prepare("select External_Temperature ,External_Relative_Humidity 
                ,Wind_Speed ,Wind_Direction from Weather_Forecast  
                where Year= :YR AND Month= :MT and Day= :DY And Hour= :HR") ;
        $Query->bindValue(":YR",$Year);
        $Query->bindValue(":MT",$Month);
        $Query->bindValue(":DY",$Day);
        $Query->bindValue(":HR",$Hour);
        $Query->execute();
        $row = $Query->fetch(\PDO::FETCH_ASSOC);
        $result=(array)$row;
        return $result;

    }
	//This Handles the Alert ,to be created based on certian criteria and makes it available to be viewed on the front End
    public function db_create_Alert ($column,$value1,$sign1,$between,$descr,$value2=null,$sign2=null,$condition=null)
    {
        $tables = array("rh"=>"air", "temperature" => "air", "co2"=>"air", 
                "hot"=>"water", "total"=>"water", "insulation"=>"heat_flux", 
                "stud"=>"heat_flux", "cur_flow"=>"heating", "cur_t2"=>"heating", 
                "cur_t1"=>"heating",  "total_mass"=>"heating", "total_energy"=>"heating", 
                "total_vol"=>"heating", "ch1"=>"el_energy", "ch2"=>"el_energy", "aux1"=>"el_energy", 
                "aux2"=>"el_energy", "aux3"=>"el_energy", "aux4"=>"el_energy", "aux5"=>"el_energy");
        $table = $tables[$column];
        $conn=new Connection (); 
        if ($between ==1){
            try

            { 

                $Query=$conn->connect()->prepare("CREATE OR REPLACE VIEW ".$descr."_Alert
                        AS select date_format(ts,'%Y-%m-%d %H') As TS,`apt` AS `Apt`,
                        avg(`".$column."`) AS `".$column."`
                        from `".$table."` group by `Apt`,date_format(ts,'%Y-%m-%d %H')    
                        having avg(`".$column."`) ".$sign1." ".$value1."");
                $Query->execute();
                return true;
            }
            catch ( \PDOException $e)
            {

                return false;
            }
        }
        if ($between ==2){
            try
            { 
                $Query=$conn->connect()->prepare("CREATE OR REPLACE VIEW ".$descr."_Alert
                        AS select date_format(ts,'%Y-%m-%d %H') As TS,`apt` AS `Apt`,
                        avg(`".$column."`) AS `".$column."`
                        from `".$table."` group by `Apt`,date_format(ts,'%Y-%m-%d %H')    
                        having avg(`".$column."`) ".$sign1." ".$value1." ".$condition." avg(`".$column."`) ".$sign2." ".$value2." ");
                $Query->execute();
                return true;
            }
            catch ( \PDOException $e)
            {
                return false;
            }    
        }

    }
	//Get the Alert
    public function db_query_Alert ($apt,$table,$StartDate,$EndDate)
    {
        $result =array();
        $conn=new Connection ();
        date_default_timezone_set('UTC');
        $StartDate = date_create_from_Format('Y-m-d H', $StartDate)->format('Y-m-d H');
        $EndDate = date_create_from_Format('Y-m-d H', $EndDate)->format('Y-m-d H');
        $Query=$conn->connect()->prepare("select * from ".$table." where Apt= :Apt_Num AND Ts between :SD and :ED ") ;
        $Query->bindValue(":Apt_Num",$apt);
        $Query->bindValue(":SD",$StartDate);
        $Query->bindValue(":ED",$EndDate);
        $Query->execute();
        while ($row = $Query->fetch(\PDO::FETCH_ASSOC))
        {
            $result[]=(array)$row;
        }

        return $result;

    }
	//Checks if the Alert is exists ,If true then we don't create View and if not then creates a view
    public function db_check_Alert($view)
    {

        $view .= '_Alert';
        $conn=new Connection ();
        try
        {    
            $Query=$conn->connect()->prepare("select * from ".$view."");
            $Query->execute();
            return true;
        }
        catch ( \PDOException $e)
        {
            return false;
        }

    }
    //Deletes Alert 
    public function db_Delete_Alert($viewname)
    {            
        $conn=new Connection ();  
        $Query=$conn->connect()->prepare("Drop view if exists ".$viewname." ");
        $Query->execute();
    }

   //Automatically gets the Apt list to view it to the Front End
    public static function db_apt_list ()
    {
        $conn = new Connection ();  
        $Query=$conn->connect()->prepare(" SELECT distinct(`apt`) FROM `apt_info` ");
        $Query->execute();
        return $Query->fetchAll(\PDO::FETCH_COLUMN, 0);
    }

}

