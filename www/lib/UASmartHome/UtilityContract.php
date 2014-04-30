<?php namespace UASmartHome;

class UtilityContract
{
    public $Type;
    public $Price;
    public $Start_Date;
	public $End_Date;

    public function __construct($Type, $Price, $Start_Date, $End_Date)
    {
        $this->Type = $Type;
        $this->Price = $Price;
        $this->Start_Date = $Start_Date;
		$this->End_Date = $End_Date;
    }
}
