<?php namespace UASmartHome;

require_once __DIR__ . '/../../vendor/autoload.php';


use \UASmartHome\Database\Engineer;
use \UASmartHome\Database\Configuration\ConfigurationDB;
use \UASmartHome\Database\Utilities\UtilitiesDB;


class EquationParser
{

    /** Mapping of stuff to things. */
    public static $DBVARS = array(
        "air_co2" => "CO2",
        "air_humidity" => "Relative_Humidity",
        "Inside_temperature" => "Temperature",
         "out_side_Temprature"=>"Outside_Temperature",
        "elec_total" => "Total_Electricity",
        "elec_ch1_phasea" => "Ch1",
        "elec_ch2_phasea" => "Ch2",
        "elec_aux1_phasea" => "AUX1",
        "elec_aux2_phasea" => "AUX2",
        "elec_aux3_phasea" => "AUX3",
        "elec_aux4_phasea" => "AUX4",
        "elec_aux5_phasea" => "AUX5",
        "elec_ch1_phaseb" => "Ch1",
        "elec_ch2_phaseb" => "Ch2",
        "elec_aux1_phaseb" => "AUX1",
        "elec_aux2_phaseb" => "AUX2",
        "elec_aux3_phaseb" => "AUX3",
        "elec_aux4_phaseb" => "AUX4",
        "elec_aux5_phaseb" => "AUX5",
        "heat_energy" => "Total_Energy",
        "heat_volume" => "Total_Volume",
        "heat_mass" => "Total_Mass",
        "heat_flow" => "Current_Flow",
        "heat_temp1" => "Current_Temperature_1",
        "heat_temp2" => "Current_Temperature_2",
        "heatflux_stud" => "Stud",
        "heatflux_insul" => "Insulation",
        "water_hot" => "Hot_Water",
        "water_total" => "Total_Water"
       
    );

    public function __construct() {

    }

    /*
     * input: startdate, enddate, apartment, granularity, type ("electricity" or "water)
     * output: array of key=>value, where key is a timestamp and value is cost
     */
    public static function getUtilityCosts($input) {

        /* test data
        $functionArray = array();
        $functionArray["startdate"] = "2012-02-29 00:00";
        $functionArray["enddate"] = "2012-03-02 00:00";
        $functionArray["apartment"] = 1;
        $functionArray["granularity"] = "Daily";
        $functionArray["type"] = "electricity";

        $input = json_encode($functionArray);
        */

        /* wtffffffffffffffffffff. */
        $input = json_decode($input, true);
        $finalCosts = array();

        $costData = UtilitiesDB::Utilities_getPrice($input["type"], $input["startdate"], $input["enddate"]);

        if($input["type"] === "Electricity") {
            $utilityUse = EquationParser::getTotalElec($input["apartment"], $input["startdate"], $input["enddate"], $input["granularity"]);
            $utilityUse = EquationParser::convertToKWH($utilityUse, $input["granularity"]);
        }
        else if($input["type"] === "Water") {
            $utilityUse = EquationParser::getTotalWater($input["apartment"], $input["startdate"], $input["enddate"], $input["granularity"]);
        }
        else {
            $type = $input['type'];
            throw new \DomainException("Utility type '$type' not found.");
        }

        foreach($utilityUse as $date=>$use) {
            foreach($costData as $contract) {
                $start = strtotime($contract["Start_Date"]);
                $end = strtotime($contract["End_Date"]);

                // convert date from yyyy-mm-dd:h to php date format
                $phpdate = str_replace(":", " ", $date) . ":00";
                if (strtotime($phpdate) >= $start && strtotime($phpdate) <= $end) {
                    $finalCosts[$date] = $use * $contract["Price"];
                }

            }
        }

        return $finalCosts;

    }

    public static function convertToKWH($use, $granularity) {
        $numHours = 1;
        switch($granularity) {
            case "Daily":
                $numHours = 24;
                break;
            case "Weekly":
                $numHours = 24*7;
                break;
            case "Monthly":
                foreach($use as $date=>$value) {
                    //get the actual number of days for each month
                    $month = date("n", strtotime(str_replace(":", " ", $date) . ":00"));
                    $year = date("y", strtotime(str_replace(":", " ", $date) . ":00"));
                    $numDays = cal_days_in_month(CAL_GREGORIAN, $month, $year);
                    $use[$date] = $value/3600/1000 * $numDays * 24;
                }
                break;
            case "Yearly":
                foreach($use as $date=>$value) {
                    //accomodate leap years
                    $year = date("y", strtotime(str_replace(":", " ", $date) . ":00"));
                    $numDays = date("z", mktime(0,0,0,12,31,$year)) + 1;
                    $use[$date] = $value/3600/1000 * $numDays * 24;
                }
                break;
        }

        if ($granularity !== "Monthly" && $granularity !== "Yearly") {
            foreach($use as $date=>$value) {
                $use[$date] = $value/3600/1000 * $numHours;
            }
        }

        return $use;
    }

    /* Gets the total water for a given apartment, dates, and granularity.
     * Return value is array of key=>value, where key is date and value is
     * total water
    */
    public static function getTotalWater($apartment, $startdate, $enddate, $granularity) {
        $data = array();
        $waterData = Engineer::db_pull_query($apartment, "Total_Water",
                                             $startdate, $enddate, $granularity);

        foreach($waterData as $date=>$value) {
            if($waterData[$date] === 0 && $value === 0)
                $data[$date] = 0;
            else
                $data[$date] = $value["Total_Water"];
        }

        return $data;

    }

    /*
     * Gets the total electricity (Ch1 of phase A + Ch1 of phase B) between
     * startdate and enddate for a given apartment.  Return value is an array
     * of key=>value, where key is date and value is the total electricity used
     */
    public static function getTotalElec($apartment, $startdate, $enddate, $granularity) {

        $data = array();
        $elecAdata = Engineer::db_pull_query(
                   $apartment, "Ch1",
                   $startdate, $enddate,
                   $granularity, "A");
        $elecBdata = Engineer::db_pull_query(
                   $apartment, "Ch1",
                   $startdate, $enddate,
                   $granularity, "B");

        foreach($elecAdata as $date=>$value) {
            if($elecAdata[$date] === 0 && $value === 0)
                $data[$date] = 0;
            else
                $data[$date] = $value["Ch1"] + $elecBdata[$date]["Ch1"];

        }
        return $data;

    }

    /**
     * Given the equation from the config file, replace all the values
     * specified by $variables$ by the values in the database and calculate
     * the value of the function.
     *
     * returns an array of key=>value pairs, where key is a timestamp and
     * value is a number (usually floating point)
     */
    public static function getData($input) {

        /* test data
        $functionArray = array();
        $functionArray["startdate"] = "2012-03-01 00:00";
        $functionArray["enddate"] = "2012-03-02 00:00";
        $functionArray["apartment"] = 1;
        $functionArray["granularity"] = "Daily";
        $functionArray["function"] = "9 * (3+pi) * \$air_temperature$ + \$air_co2$ / 4";
        $functionArray["functionname"] = "functionname";

        $input = json_encode($functionArray);
        */

        $input = json_decode($input, true);
        $function = $input["function"];
        $data = array();
        $finalGraphData = array();
        $evaluator = new EvalMath();
        $db_vars = self::$DBVARS;

        // Manually replace constants outside of the evaluator
        // NOTE: This should be done before exploding the function string
        $function = EquationParser::replaceConstants($function);
        
        $pieces = explode("$", $function);

        if(count($pieces) === 1) {
            return $evaluator->evaluate($function);
        }

        for($i=1; $i<count($pieces); $i+=2) {

            if($pieces[$i] === "elec_total") {
                $totalElec = getTotalElec($input["apartment"],
                                $input["startdate"], $input["enddate"],
                                $input["granularity"]);

                foreach($totalElec as $date=>$value) {
                    $data["elec_total"][$date]["Ch1"] = $value;
                }
            }
            else if(strpos($pieces[$i], "elec_") === 0) {
                $data[$pieces[$i]] = Engineer::db_pull_query(
                           $input["apartment"], $db_vars[$pieces[$i]],
                           $input["startdate"], $input["enddate"],
                           $input["granularity"],
                           strtoupper($pieces[$i][strlen($pieces[$i])-1]));

            }

            else {
                $data[$pieces[$i]] = Engineer::db_pull_query(
                           $input["apartment"], $db_vars[$pieces[$i]],
                           $input["startdate"], $input["enddate"],
                           $input["granularity"]);
            }

        }

        $rand_data = current($data);

        if (!$rand_data) {
            throw new \Exception('No data found for the given range ' .
                   		 'No database variable found in equation');
        }

        $num_points = count($rand_data);

        // replace all the variables in the array of functions
        for ($i = 0; $i < $num_points; ++$i) {

            $emptydata = false;
            reset($data);
            $firstpass = true;
            while ($cur_data = current($data)) {

                // use the first variable's date
                if($firstpass)
                    $date = array_keys($cur_data)[$i];
                // date does not exist in the data or no db value exists
                if(!array_key_exists($date, $cur_data) || !array_values($cur_data)[$i]) {
                    $emptydata = true;
                //no db value for this time
                }
                else {
                    $evaluator->evaluate(key($data) . " = " . end(array_values($cur_data)[$i]));
                }

                $firstpass = false;
                next($data);
            }

            if (!$emptydata)
                $finalGraphData[$date] = $evaluator->evaluate(str_replace("$", "", $function));
            $emptydata = false;
        }

        return $finalGraphData;

    }

    public static function getVariables()
    {
        return self::$DBVARS;
    }

    private static function replaceConstants($string)
    {
        $constants = ConfigurationDB::fetchConstants(null); // TODO: Can these be cached?
        
        // Replace the name of each constant with its value
        // Note that constant names have presendence over variable names
        foreach ($constants as $constant) {
            // TODO: Can this regex be compiled?
            $string = preg_replace('/\$' . $constant['name'] . '\$/', $constant['value'], $string);
        }
        
        return $string;
    }

}

//var_dump(Engineer::db_pull_query(1, "CO2", "2012-03-07 00", "2012-03-20 00", "Hourly"));
