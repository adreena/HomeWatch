<?php namespace UASmartHome;

class Score
{
    public $rank;
    public $score;

    public function __construct($rank, $score)
    {
        $this->rank = $rank;
        $this->score = $score;
    }
}
