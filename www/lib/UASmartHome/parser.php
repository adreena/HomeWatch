<?php namespace UASmartHome;

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__. '/Database/Engineer.php';

$debug = False;

class parser
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
        $cleaned_funcs = array();
        $data = array();
        $finalGraphData = array();

        $pieces = explode("$", $function);

        for($i=1; $i<count($pieces); $i+=2) {

            switch($pieces[$i]) {
                case "air_temperature":
                    $data["air_temperature"] = \Engineer::db_pull_query(
                               $input["apartment"], "Temperature",
                               $input["startdate"], $input["enddate"],
                               $input["granularity"]);
                    //var_dump($data);
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

            }

        }

        $rand_data = current($data);
        $num_points = count($rand_data);

        // fill the $cleaned_funcs array with as many copies of the function
        // as needed
        while ($point = current($rand_data)) {
            $cleaned_funcs[key($rand_data)] = $function;
            next($rand_data);
        }

        // replace all the variables in the array of functions
        while ($cur_data = current($data)) {
            if(count($cur_data) != $num_points) {
                echo "not all data have the same number of points\n";
            }

            while($value = current($cur_data)) {
                switch(key($data)) {
                    case "air_temperature":
                        $cleaned_funcs[key($cur_data)] = str_replace("\$air_temperature$", $value["Temperature"], $cleaned_funcs[key($cur_data)]);
                        break;
                    case "air_humidity":
                        $cleaned_funcs[key($cur_data)] = str_replace("\$air_humidity$", $value["Relative_Humidity"], $cleaned_funcs[key($cur_data)]);
                        break;
                    case "air_co2":
                        $cleaned_funcs[key($cur_data)] = str_replace("\$air_co2$", $value["CO2"], $cleaned_funcs[key($cur_data)]);
                        break;
                    default:
                        echo "invalid variable " . key($data) . " in equation\n";
                }

                next($cur_data);
            }

            next($data);
        }

        // evaluate the functions and store them in $finalGraphData
        while ($func = current($cleaned_funcs)) {
            $finalGraphData[key($cleaned_funcs)] = parser::evalEquation($func);
            next($cleaned_funcs);
        }


        //var_dump($finalGraphData);
        return $finalGraphData;

    }

    private static function evalEquation($equation) {

        $evaluator = new EvalMath();
        return $evaluator->evaluate($equation);


    }



}
