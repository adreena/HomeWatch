<?php namespace UASmartHome;

require_once __DIR__ . '/../../vendor/autoload.php';


use \UASmartHome\Database\Engineer;
use \UASmartHome\Database\Configuration\ConfigurationDB;


class EquationParser
{

    /** Mapping of stuff to things. */
    public static $DBVARS = array(
        "air_co2" => "CO2",
        "air_humidity" => "Relative_Humidity",
        "air_temperature" => "Temperature",
        "elec_total" => "Total_Electricity",
        "elec_ch1" => "Ch1",
        "elec_ch2" => "Ch2",
        "elec_aux1" => "AUX1",
        "elec_aux2" => "AUX2",
        "elec_aux3" => "AUX3",
        "elec_aux4" => "AUX4",
        "elec_aux5" => "AUX5",
        "heat_energy" => "Total_Energy",
        "heat_volume" => "Total_Volume",
        "heat_mass" => "Total_Mass",
        "heat_flow" => "Current_Flow",
        "heat_temp1" => "Current_Temperature_1",
        "heat_temp2" => "Current_Temperature_2",
        "heatflux_stud" => "Stud",
        "hotflex_stud" => "Stud",
        "heatflux_insul" => "Insulation",
        "water_hot" => "Hot_Water",
        "water_total" => "Total_Water",
        //TODO: add weather functions in database
        "weather_temp" => "External_Temperature",
        "weather_humidity" => "External_Relative_Humidity",
        "weather_windspeed" => "Wind_Speed",
        "weather_winddirection" => "Wind_Direction"
    );

    public function __construct() {

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
        $functionArray["startdate"] = "2012-02-29:0";
        $functionArray["enddate"] = "2012-03-02:0";
        $functionArray["apartment"] = 1;
        $functionArray["granularity"] = "Hourly";
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
                $data[$pieces[$i]] = Engineer::db_pull_query(
                           $input["apartment"], "Ch1",
                           $input["startdate"], $input["enddate"],
                           $input["granularity"], "A");
                $elecBdata = Engineer::db_pull_query(
                           $input["apartment"], "Ch1",
                           $input["startdate"], $input["enddate"],
                           $input["granularity"], "B");
                foreach($elecBdata as $date=>$value) {
                    if($data["elec_total"][$date] === 0 && $value === 0)
                        $data["elec_total"][$date] = 0;
                    else
                        $data["elec_total"][$date]["Ch1"] += $value["Ch1"];
                }
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
            echo "no database variable found in equation\n";
            return null;
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

