<?php namespace UASmartHome;

class ResidentScores
{
    public $ID;
    public $co2;
    public $energy;
	public $temperature;
	public $heatloss;
	public $humidity;

    public function __construct($ID, $co2, $energy, $temperature, $heatloss, $humidity)
    {
        $this->ID = $ID;
        $this->co2 = $co2;
        $this->energy = $energy;
		$this->temperature = $temperature;
        $this->heatloss = $heatloss;
        $this->humidity = $humidity;
    }

}

