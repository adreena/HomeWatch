<?php namespace UASmartHome;

include ('Engineer.php');

class ManagerControl
{

    private $model;
    private $connection;

    public function __construct() {
        $this->model = new Database\ResidentDB();
        $this->connection = new Database\Connection();
        $this->connection->Connect();
    }

    public function getResidentInfo() {

        $residents = $this->model->Resident_DB_Get_All_Residents();
        $info = array();
        $unsorted_scores = array();
	    $rank = 0;
        $name = "";

        foreach ($residents as $resident) {
            $name = $this->model->Resident_DB_Read($resident)["Name"];
            $data = $this->model->Resident_DB_Score($resident);

            $unsorted_scores[$name] = $data["Score"];

        }

        arsort($unsorted_scores);

        while ($score = current($unsorted_scores)) {
            $rank++;

                array_push($info, new ResidentGameInfo(
                                    $rank, key($unsorted_scores), $score, ""));

            next($unsorted_scores);
        }

        return $info;
    }

	public function getUtilityContracts	($Type)
	{
		return Engineer::Utilities_getAll()
	}
	
	public function submitContract($Type, $Price, $Start_Date, $End_Date)
	{
		Engineer::Utilities_Insert($Type, $Start_Date, $End_Date, $Price);
	}
	
	public function editContract($Type, $Price, $Start_Date, $End_Date)
	{
		Engineer::Utilities_Insert($Start_Date, $End_Date, $Price, $Type);
	}

}
