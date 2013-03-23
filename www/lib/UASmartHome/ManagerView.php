<?php namespace UASmartHome;

class ManagerView
{

    private $controller;

    public function __construct()
    {
        $this->controller = new ManagerControl();
    }

    public function getResidentInfo()
    {
        # TODO: when the user controls are in place, replace this 1 by the actual user id
        $resinfo = $this->controller->getResidentInfo(1);
        return $resinfo;
    }
	
	public function getUtilityContracts()
	{
		$contracts = $this->controller->getUtilityContracts();
	}
	
	public function submitContract($Type, $Price, $Start_Date, $End_Date)
	{
		$this->controller->submitContract($Type, $Price, $Start_Date, $End_Date);
	}
	
	public function editContract($Type, $Price, $Start_Date, $End_Date)
	{
		$this->controller->editContract($Type, $Price, $Start_Date, $End_Date);
	}

}

