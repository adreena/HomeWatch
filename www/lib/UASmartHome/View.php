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

}

