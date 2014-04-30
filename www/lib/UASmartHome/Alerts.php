<?php namespace UASmartHome;

require_once __DIR__ . '/../../vendor/autoload.php';

use \UASmartHome\Database\Engineer;

class Alerts
{

    public static $ALERTVARS = array(
        "air_co2" => "co2",
        "air_humidity" => "rh",
        "air_temperature" => "temperature",
        "elec_total" => "Total_Electricity",
        "elec_ch1" => "ch1",
        "elec_ch2" => "ch2",
        "elec_aux1" => "aux1",
        "elec_aux2" => "aux2",
        "elec_aux3" => "aux3",
        "elec_aux4" => "aux4",
        "elec_aux5" => "aux5",
        "heat_energy" => "total_energy",
        "heat_volume" => "total_vol",
        "heat_mass" => "total_mass",
        "heat_flow" => "cur_flow",
        "heat_temp1" => "cur_t1",
        "heat_temp2" => "cur_t2",
        "heatflux_stud" => "stud",
        "heatflux_insul" => "insulation",
        "water_hot" => "hot",
        "water_total" => "total"
    );

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
                throw new \InvalidArgumentException("Comparison operator not found");
        }

        /* The above may have matched, but the comparison failed. */
        return false;

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

        $data = Engineer::db_query_default_Alert(
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
        $db_vars = self::$ALERTVARS;
        $db_compare_vals = array();

        if($numleftpieces === 1 && $numrightpieces === 1) {
            throw new \DomainException("no database variables in the alert");
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
            if(trim($leftpieces[0]) == "" && trim($leftpieces[2]) == "")
                $db_compare_vals["left"] = $db_vars[$leftpieces[1]];
            else
                return false;
        }
        else {
            if(!is_numeric($left)) return false;
        }

        if ($numrightpieces === 3) {
            // there is one variable on the right side
            if(trim($rightpieces[0]) == "" && trim($rightpieces[2]) == "")
                $db_compare_vals["right"] = $db_vars[$rightpieces[1]];
            else
                return false;
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

    private static function rearrangeComparison($useDBCache, $pieces) {

        $comparison = array();

        if(array_key_exists("left", $useDBCache)) {
            $comparison["left"] = $useDBCache["left"];
            $comparison["right"] = $pieces[3];
            $comparison["operator"] = $pieces[2];
        }
        else {
            $comparison["left"] = $useDBCache["right"];
            $comparison["right"] = $pieces[1];

            //reverse comparison operator because variable is on the right side
            if ($pieces[2] == ">")
                $comparison["operator"] = "<";
            else if ($pieces[2] == "<")
                $comparison["operator"] = ">";
        }

        return $comparison;

    }

    private static function getPieces($formula) {

        /* the regular expression match will put the following stuff
         * in pieces:
         * 0 => $comparison
         * 1 => stuff before the operator
         * 2 => operator
         * 3 => stuff after the operator
         */
        if (!preg_match("/(.*)(<|>|!=|==)(.*)/", $formula, $pieces)) {
            throw new \DomainException("no comparison operator found in $formula");
        }

        $pieces[1] = trim($pieces[1]);
        $pieces[3] = trim($pieces[3]);

        return $pieces;

    }

    public static function getAlerts($input) {

        $input = json_decode($input, true);
        /* HACK! Why does this need to take in JSON??!??!??! */
        $comparison = $input["function"];
        $comparison_crc32 = crc32($comparison);
        $finalAlerts = array();

        if(preg_match("/(.*)(AND|OR)(.*)/", $comparison, $bool_pieces)) {
            $leftbool = trim($bool_pieces[1]);
            $rightbool = trim($bool_pieces[3]);

            $leftpieces = Alerts::getPieces($leftbool);
            $rightpieces = Alerts::getPieces($rightbool);

            if(!$leftpieces || !$rightpieces)
                return null;

            $useDBCacheLeft = Alerts::checkUseDBCache($leftpieces[1], $leftpieces[3]);
            $useDBCacheRight = Alerts::checkUseDBCache($rightpieces[1], $rightpieces[3]);
            if(is_null($useDBCacheLeft) || is_null($useDBCacheRight))
                return null;

            if($useDBCacheLeft && $useDBCacheRight) {

                $leftcompare = Alerts::rearrangeComparison($useDBCacheLeft, $leftpieces);
                $rightcompare = Alerts::rearrangeComparison($useDBCacheRight, $rightpieces);


                if($leftcompare["left"] != $rightcompare["left"]) {
                    throw new \DomainException("Different variables are not supported right now");
                    return null;
                }

                $column = $leftcompare["left"];
                $value1 = $leftcompare["right"];
                $sign1 = $leftcompare["operator"];
                $value2 = $rightcompare["right"];
                $sign2 = $rightcompare["operator"];

                // check if the view already exists under the name username_variable
                if (!Engineer::db_check_Alert($comparison_crc32)) {
                    if (!Engineer::db_create_Alert($column, $value1, $sign1, 2, $comparison_crc32, $value2, $sign2, $bool_pieces[2])) {
                        /* THERE SHOULD NOT BE AN ECHO HERE! */
                        echo "could not create alert\n";
                        return null;
                    }
                }

                $data = Engineer::db_query_Alert(
                           $input["apartment"], $comparison_crc32 . "_Alert",
                           $input["startdate"], $input["enddate"]);

                foreach($data as $values) {
                    $finalAlerts[str_replace(" ", ":", $values["TS"])] = $values[$column];
                }

                return $finalAlerts;

            } else {
                throw new \DomainException("Syntax error in the alert");
                return null;
            }

        } else {

            $pieces = Alerts::getPieces($comparison);

            $useDBCache = Alerts::checkUseDBCache($pieces[1], $pieces[3]);

            if(is_null($useDBCache))
                return null;

            if($useDBCache) {
                $compare = alerts::rearrangeComparison($useDBCache, $pieces);

                $column = $compare["left"];
                $value1 = $compare["right"];
                $sign1 = $compare["operator"];

                // check if the view already exists under the name username_variable
                if(!Engineer::db_check_Alert($comparison_crc32)) {
                    if(!Engineer::db_create_Alert($column, $value1, $sign1, 1, $comparison_crc32)) {
                        echo "could not create alert\n";
                        return null;
                    }
                }

                $data = Engineer::db_query_Alert(
                           $input["apartment"], $comparison_crc32 . "_Alert",
                           $input["startdate"], $input["enddate"]);

                foreach($data as $values) {
                    $finalAlerts[str_replace(" ", ":", $values["TS"])] = $values[$column];
                }

                return $finalAlerts;

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
                throw new \DomainException("there are no database variables in the alert");
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

}

/* test data for getDefaultAlerts
$functionArray = array();
$functionArray["startdate"] = "2012-02-29:0";
$functionArray["enddate"] = "2012-03-02:23";
$functionArray["apartment"] = 1;
//$functionArray["alert"] = "\$air_co2$ > 1160";
$functionArray["alerttype"] = "Temperature";

$input = json_encode($functionArray);
var_dump(Alerts::getDefaultAlerts($input));
*/

/* test data for getAlerts
$functionArray = array();
$functionArray["startdate"] = "2012-3-1 0";
$functionArray["enddate"] = "2012-3-2 0";
$functionArray["apartment"] = 1;
$functionArray["function"] = "\$air_co2$ > 1000 AND \$air_co2$ < 1200";
//$functionArray["alert"] = "10 < \$heatflux_insul$ OR \$heatflux_insul$ < 7";
//$functionArray["alert"] = "\$air_co2$ > \$air_temperature$*1000";
//$functionArray["alerttype"] = "CO2";

$input = json_encode($functionArray);
var_dump(Alerts::getAlerts($input));
*/
