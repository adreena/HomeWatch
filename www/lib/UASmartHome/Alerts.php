<?php namespace UASmartHome;

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__. '/Database/Engineer.php';

class Alerts
{

    public function __construct() {

    }

    private static function compare($left, $right, $operation) {
        switch($operation) {
            case ">":
                if($left > $right) {
                    return true;
                }
                break;
            case "<":
                if($left < $right) {
                    return true;
                }
                break;
            case "==":
                if($left == $right) {
                    return true;
                }
                break;
            case "!=":
                if($left != $right) {
                    return true;
                }
                break;
            default:
                echo "comparison operator not found\n";
                return false;
        }

    }

    /* given startdate, enddate, apartment, and alerttype, get any values
     * which fit the alerttype and return them as an array of key=>value,
     * where key is date and value is the alerttype value which has
     * exceeded some threshold
     *
     * alerttypes so far: Temperature, CO2, Relative_Humidity
     */
    public static function getDefaultAlerts($input) {

        $input = json_decode($input, true);
        $finalAlerts = array();

        $data = Engineer::db_query_Alert(
                   $input["apartment"], $input["alerttype"],
                   $input["startdate"], $input["enddate"]);

        return $data;
        var_dump($data);

    }

    /* given the comparison string, split it into left and right and
     * call parser on each piece, then do the comparisons on the values
     * for left and right.
     *
     * returns an array of key=>value pairs which matched the comparison,
     * where key is a timestamp and value is a number (usually floating point)
     */
    public static function getAlerts($input) {

        $input = json_decode($input, true);
        $evaluator = new EvalMath();
        $comparison = $input["alert"];
        $compare_types = array("<", ">", "!=", "==");
        $finalAlerts = array();

        /* the regular expression match will put the following stuff
         * in pieces:
         * 0 => $comparison
         * 1 => stuff before the operator
         * 2 => operator
         * 3 => stuff after the operator
         */
        if(!preg_match("/(.*)(<|>|!=|==)(.*)/", $comparison, $pieces)) {
            echo "no comparison operator found\n";
            return null;
        }

        $input["granularity"] = "Hourly";
        $input["function"] = $pieces[1];
        $input = json_encode($input);
        $left = EquationParser::getData($input);
        $input = json_decode($input, true);
        $input["function"] = $pieces[3];
        $input = json_encode($input);
        $right = EquationParser::getData($input);

        if(!is_array($left) && !is_array($right)) { //neither has a variable
            echo "there are no database variables in the alert\n";
            return null;
        }

        if(is_array($left)) {
            if(is_array($right)) {
                if(count($left) != count($right)) {
                    echo "different amount of data points for the left and right parts of the comparison\n";
                    return null;
                }

                for($i=0; $i<count($left); $i++) {
                    if(array_keys($left)[$i] != array_keys($right)[$i]) {
                        echo "date mismatch from database\n";
                        return null;
                    }

                    if(Alerts::compare(array_values($left)[$i],
                                       array_values($right)[$i],
                                       $pieces[2])) {
                        //TODO: what to return for multiple variables on both sides?
                        $finalAlerts[array_keys($left)[$i]] = array_values($right)[$i];
                    }
                }
            }
            else { //$right is a constant
                foreach($left as $date=>$value) {
                    if(Alerts::compare($value, $right, $pieces[2])) {
                        $finalAlerts[$date] = $value;
                    }
                }
            }
        }
        else { //$left is a constant
            if(is_array($right)) {
                foreach($right as $date=>$value) {
                    if(Alerts::compare($left, $value, $pieces[2])) {
                        $finalAlerts[$date] = $value;
                    }
                }
            }
            else { //both left and right are constants
                echo "there are no database variables in the alert\n";
                return null;
            }

        }


        return $finalAlerts;

    }

}

/* test data for getDefaultAlerts
$functionArray = array();
$functionArray["startdate"] = "2012-02-29:0";
$functionArray["enddate"] = "2012-03-02:0";
$functionArray["apartment"] = 1;
//$functionArray["alert"] = "\$air_co2$ > 1160";
$functionArray["alerttype"] = "Temperature";

$input = json_encode($functionArray);
var_dump(Alerts::getDefaultAlerts($input));
*/

/* test data for getAlerts
$functionArray = array();
$functionArray["startdate"] = "2012-02-29:0";
$functionArray["enddate"] = "2012-03-01:0";
$functionArray["apartment"] = 1;
//$functionArray["alert"] = "\$air_co2$ > 1160";
$functionArray["alert"] = "\$air_co2$ > \$air_temperature$*1000";
$functionArray["alerttype"] = "CO2";

$input = json_encode($functionArray);
var_dump(Alerts::getAlerts($input));
*/
