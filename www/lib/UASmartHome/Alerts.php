<?php namespace UASmartHome;

require_once __DIR__ . '/../../vendor/autoload.php';

use \UASmartHome\Database\Engineer;

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

    }

    /* Checks if the alert comparison should be handled by the DB creating
     * views or by the slow equation parser and individual DB reads
     */
    private static function checkUseDBCache($left, $right) {
        $leftpieces = explode("$", $left);
        $rightpieces = explode("$", $right);
        $numleftpieces = count($leftpieces);
        $numrightpieces = count($rightpieces);
        $db_vars = EquationParser::$DBVARS;
        $db_compare_vals = array();

        if($numleftpieces === 1 && $numrightpieces === 1) {
            echo "no database variables in the alert\n";
            return null;
        }
        else if ($numleftpieces > 3 || $numrightpieces > 3) {
            // more than 1 variable on one side on the comparison, db
            // cannot handle with views
            return false;
        }
        else if ($numleftpieces === 3 && $numrightpieces === 3) {
            // there is a variable on both sides of the comparison, db
            // cannot handle with views
            return false;
        }

        if ($numleftpieces === 3) {
            // there is one variable on the left side
            $db_compare_vals["left"] = $db_vars[$leftpieces[1]];
        }
        else {
            if(!is_numeric($left)) return false;
        }

        if ($numrightpieces === 3) {
            // there is one variable on the right side
            $db_compare_vals["right"] = $db_vars[$rightpieces[1]];
        }
        else {
            if(!is_numeric($right)) return false;
        }

        return $db_compare_vals;
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

        $pieces[1] = trim($pieces[1]);
        $pieces[3] = trim($pieces[3]);

        $useDBCache = Alerts::checkUseDBCache($pieces[1], $pieces[3]);
        if(is_null($useDBCache))
            return null;

        if($useDBCache) {
            if(array_key_exists("left", $useDBCache)) {
                $left = $useDBCache["left"];
                $right = $pieces[3];
            }
            else {
                $left = $useDBCache["right"];
                $right = $pieces[1];

                //reverse comparison operator because variable is on the right side
                if ($pieces[2] == ">")
                    $pieces[2] = "<";
                else if ($pieces[2] == "<")
                    $pieces[2] = ">";
            }

            $username = Auth\User::getSessionUser()->getUsername();

            // check if the view already exists under the name username_variable
            if(!Engineer::db_check_Alert($username . "_" . $left)) {
                Engineer::db_create_Alert($username,$left, $right, $pieces[2], 1);
            }
            $data = Engineer::db_query_Alert(
                       $input["apartment"], $left,
                       $input["startdate"], $input["enddate"], $username . "_" . $left . "_Alert");

            return $data;
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

/* test data for getData
$functionArray = array();
$functionArray["startdate"] = "2012-02-29:0";
$functionArray["enddate"] = "2012-03-01:0";
$functionArray["apartment"] = 1;
//$functionArray["alert"] = "\$heatflux_insul$ > 10";
$functionArray["alert"] = "10 < \$heatflux_insul$";
//$functionArray["alert"] = "\$air_co2$ > \$air_temperature$*1000";
$functionArray["alerttype"] = "CO2";

$input = json_encode($functionArray);
var_dump(Alerts::getAlerts($input));
*/
