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

}

