<?php namespace UASmartHome\Database\Utilities;

require_once __DIR__ . '/../../../../vendor/autoload.php';

class Utility {

    public $type;
    public $price;
    public $startdate;
    public $enddate;

    public function isValid()
    {
        if (empty($this->name))
            return false;

        if (!isset($this->value))
            return false;

        return true;
    }
}
