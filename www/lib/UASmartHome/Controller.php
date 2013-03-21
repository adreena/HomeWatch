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
        $residents = $this->model->Resident_DB_Get_All_Residents();
        $scores = array();
        $unsorted_scores = array();
	    $rank = 0;

        foreach ($residents as $resident) {
            $data = $this->model->Resident_DB_Score($resident);

            if ($resident == $resident_id)
                $unsorted_scores["r"] = $data["Score"];
            else
                array_push($unsorted_scores, $data["Score"]);

        }

        arsort($unsorted_scores);

        while ($score = current($unsorted_scores)) {
            $rank++;

            if (key($unsorted_scores) === "r") {
                array_push($scores, new Score($rank, $score, True));
            }
            else
                array_push($scores, new Score($rank, $score, False));

            next($unsorted_scores);
        }

        //var_dump(\UASmartHome\Auth\User::getSessionuser());

        return $scores;
    }

    public function getCurrentInfo($resident_id)
    {
        $currentInfo = array();
        array_push($currentInfo, new CurrentInfo("elec", "1"));
        array_push($currentInfo, new CurrentInfo("water", "2"));
        array_push($currentInfo, new CurrentInfo("humidity", "5"));
        array_push($currentInfo, new CurrentInfo("co2", "6"));
        array_push($currentInfo, new CurrentInfo("temp", "7"));

        return $currentInfo;


    }

    public function getRank($resident_id)
    {
        $scores = $this->getScores($resident_id);
        $rank = 0;

        foreach ($scores as $score) {
            if ($score->ismyscore) {
                $rank = $score->rank;
            }
        }

        return $rank;

    }


}
