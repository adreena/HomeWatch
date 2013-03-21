<?php namespace UASmartHome;

class CurrentInfo
{
    public $info;
    public $value;

    public function __construct($info, $value)
    {
        $this->info = $info;
        $this->value = $value;
    }
}
