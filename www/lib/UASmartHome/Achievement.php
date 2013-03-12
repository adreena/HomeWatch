<?php namespace UASmartHome;

class Achievement
{
    public $description;
    public $achieved;
    public $icon;
    public $receivedDate;

    public function __construct($description, $achieved, $icon, $receivedDate)
    {
        $this->description = $description;
        $this->achieved = $achieved;
        $this->icon = $icon;
        $this->receivedDate = $receivedDate;
    }

}

