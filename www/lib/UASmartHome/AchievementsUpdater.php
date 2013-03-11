<?php namespace UASmartHome;

require_once __DIR__ . '/../../vendor/autoload.php';

$debug = False;

class AchievementsUpdater
{

    private $residentDB;
    private $engineerDB;

    public function __construct() {
        $this->residentDB = new Database\ResidentDB();
        $this->engineerDB = new Database\Engineer();

        $this->residentDB->Connect();
        $this->engineerDB->Connect();

    }

    /* Give $resident_id the CO2 achievement if his apartment has had a month
     * with an average of less than 1250ppm CO2
     */
    public function updateCO2Achievement($resident_id) {

        if ($GLOBALS['debug'])
            echo "testing if resident has achievement id 4\n";

        $earned = $this->residentDB->Resident_DB_Earned_Achievement($resident_id);
        $has_achieved = False;

        foreach ($earned as $earned_achiev) {
            if ($earned_achiev["Achievement_ID"] == 4) {
                $has_achieved = True;
            }
        }

        if (!$has_achieved) {
            $apt = $this->residentDB->Resident_DB_Read($resident_id)["Room_Number"]; 
            $month = 3;
            $year = 2012;
            /* TODO: scan month and year from today when using this script on all the data!
            $month = getdate()["mon"];
            $year = getdate()["year"];
            */
            do {
                if ($GLOBALS['debug'])
                    echo "year = " . $year . " month = " . $month . "\n";

                $data = $this->engineerDB->db_query_Monthly($apt, 'Air', $year, $month);

                if ($month == 1) {
                    $month = 12;
                    $year--;
                }
                else {
                    $month--;
                }

                if (count($data) != 0 && $data[0]["CO2"] < 1250) {
                    try {
                        $this->residentDB->Resident_DB_Earned_Update($resident_id, 4, "today");
                    } catch (Exception $e) {
                        echo "Warning: caught exception while adding achievement\n";
                    }
                }
            } while(count($data) != 0 && $year>1990);
        }
        else if ($GLOBALS['debug'])
            echo "resident already has achievement id 4\n";
                


    }

}

if(count(getopt("d")) == 1) {
    $debug = True;
    echo "Debug mode on\n";
}

$updater = new AchievementsUpdater();

$resDB = new Database\ResidentDB();
$resDB->Connect();
$residents = $resDB->Resident_DB_Get_All_Residents();

foreach ($residents as $resident) {
    if($debug)
        echo "testing resident id " . $resident . "\n";

    $updater->updateCO2Achievement($resident);
}
