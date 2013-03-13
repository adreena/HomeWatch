<?php namespace UASmartHome;

class Controller
{

    private $model;

    public function __construct() {
        $this->model = new Database\ResidentDB();
        $this->model->Connect();
    }

    public function getAchievements($resident_id) {

        $got_achiev = $this->model->Resident_DB_Earned_Achievement_2($resident_id);
        $all_achiev = $this->model->Resident_DB_Achievement();
        $achievements = array();

        foreach ($all_achiev as $achiev) {
            $has_achieved = False;
            $date_achieved = "";
            foreach ($got_achiev as $has) {
                if ($has["Name"] == $achiev["Name"]) {
                    $has_achieved = True;
                    $date_achieved = $has["Date_Earned"];
                    break;
                }
            }

            if ($has_achieved)
            {
                array_push($achievements,
                    new Achievement($achiev["Description"], True, "icons/" . $achiev["Enabled_Icon"], $date_achieved));
            }
            else
            {
                array_push($achievements,
                    new Achievement($achiev["Description"], False, "icons/" . $achiev["Disabled_Icon"], ""));

            }
        }

        return $achievements;

    }

    public function getScores($resident_id)
    {
        $scores = array();
        $scores[0] = new Score(0, 100);
        $scores[1] = new Score(1, 123);
        $scores[2] = new Score(2, 200);
        $scores[3] = new Score(3, 350);
        $scores[4] = new Score(4, 401);
        return $scores;
    }
}
