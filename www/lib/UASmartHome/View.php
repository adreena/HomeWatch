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
        $achievements = $this->controller->getAchievements();
        return $achievements;
    }

    public function getScores()
    {
        $scores = $this->controller->getScores();
        return $scores;
    }

    public function getCurrentInfo()
    {
        $currentInfo = $this->controller->getCurrentInfo();
        return $currentInfo;
    }

    public function getRank()
    {
        $rank = $this->controller->getRank();
        return $rank;
    }
}

