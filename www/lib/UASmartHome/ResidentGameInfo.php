<?php namespace UASmartHome;

class ResidentGameInfo
{
    public $rank;
    public $name;
    public $score;
    public $achievements;

    public function __construct($rank, $name, $score, $achievements)
    {
        $this->rank = $rank;
        $this->name = $name;
        $this->score = $score;
        $this->achievements = $achievements;
    }
}
