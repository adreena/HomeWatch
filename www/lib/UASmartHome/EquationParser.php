<?php namespace UASmartHome;

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__. '/Database/Engineer.php';

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

        $pieces = explode("$", $function);

        if(count($pieces) === 0) {
            echo "there are no database variables in the equation\n";
            return null;
        }

        for($i=1; $i<count($pieces); $i+=2) {

            switch($pieces[$i]) {
                case "air_temperature":
                    $data["air_temperature"] = \Engineer::db_pull_query(
                               $input["apartment"], "Temperature",
                               $input["startdate"], $input["enddate"],
                               $input["granularity"]);
                    break;

                case "air_humidity":
                    $data["air_humidity"] = \Engineer::db_pull_query(
                               $input["apartment"], "Relative_Humidity",
                               $input["startdate"], $input["enddate"],
                               $input["granularity"]);
                    break;

                case "air_co2":
                    $data["air_co2"] = \Engineer::db_pull_query(
                               $input["apartment"], "CO2",
                               $input["startdate"], $input["enddate"],
                               $input["granularity"]);
                    break;

                default:
                    echo "invalid variable " . $pieces[$i] . " in equation\n";
                    return null;

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

            reset($data);
            while ($cur_data = current($data)) {
                $date = array_keys($cur_data)[$i];
                if(count($cur_data) != $num_points) {
                    echo "not all data have the same number of points\n";
                    return null;
                }
                $evaluator->evaluate(key($data) . " = " . end(array_values($cur_data)[$i]));

                next($data);
            }

            $finalGraphData[$date] = $evaluator->evaluate(str_replace("$", "", $function)) . "\n";
        }

        return $finalGraphData;

    }

}

