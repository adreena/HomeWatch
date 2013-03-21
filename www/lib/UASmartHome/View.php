<?php namespace UASmartHome;

class View
{

    private $controller;

    public function __construct()
    {
        $this->controller = new Controller();
    }

    public function getAchievements()
    {
        # TODO: when the user controls are in place, replace this 1 by the actual user id
        $achievements = $this->controller->getAchievements(1);
        return $achievements;
    }

    public function getScores()
    {
        $scores = $this->controller->getScores(1);
        return $scores;
    }

    public function getCurrentInfo()
    {
        $currentInfo = $this->controller->getCurrentInfo(1);
        return $currentInfo;
    }

    public function getRank()
    {
        $rank = $this->controller->getRank(1);
        return $rank;
    }
}

