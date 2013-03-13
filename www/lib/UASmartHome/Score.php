<?php namespace UASmartHome;

class Score
{
    public $rank;
    public $score;
    public $ismyscore;

    public function __construct($rank, $score, $ismyscore)
    {
        $this->rank = $rank;
        $this->score = $score;
        $this->ismyscore = $ismyscore;
    }
}
