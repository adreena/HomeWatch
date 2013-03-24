<?php namespace UASmartHome;

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__. '/Database/Engineer.php';

define("DBVARS", serialize(array(
    "air_co2" => "CO2",
    "air_humidity" => "Relative_Humidity",
    "air_temperature" => "Temperature",
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
    "heatflux_insul" => "Insulation",
    "water_hot" => "Hot_Water",
    "water_total" => "Total_Water",
    //TODO: add weather functions in database
    "weather_temp" => "External_Temperature",
    "weather_humidity" => "External_Relative_Humidity",
    "weather_windspeed" => "Wind_Speed",
    "weather_winddirection" => "Wind_Direction"
)));


class EquationParser
{

    public function __construct() {

    }

    /* given the equation from the config file, replace all the values
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
        $functionArray["enddate"] = "2012-03-01:0";
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
        $db_vars = unserialize(DBVARS);

        $pieces = explode("$", $function);

        if(count($pieces) === 1) {
            return $evaluator->evaluate($function);
        }

        for($i=1; $i<count($pieces); $i+=2) {

            $data[$pieces[$i]] = Engineer::db_pull_query(
                       $input["apartment"], $db_vars[$pieces[$i]],
                       $input["startdate"], $input["enddate"],
                       $input["granularity"]);

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
            while ($cur_data = current($data)) {
                $date = array_keys($cur_data)[$i];
                if(count($cur_data) != $num_points) {
                    echo "not all data have the same number of points\n";
                    return null;
                }

                if(!array_values($cur_data)[$i]) { //no db value for this time
                    $emptydata = true;
                }
                else {
                    $evaluator->evaluate(key($data) . " = " . end(array_values($cur_data)[$i]));
                }

                next($data);
            }

            if (!$emptydata)
                $finalGraphData[$date] = $evaluator->evaluate(str_replace("$", "", $function));
            $emptydata = false;
        }

        return $finalGraphData;

    }

}

