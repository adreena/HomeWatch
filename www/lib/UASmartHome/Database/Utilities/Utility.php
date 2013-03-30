<?php namespace UASmartHome\Database\Utilities;

require_once __DIR__ . '/../../../../vendor/autoload.php';

class Utility {

    const INVALID_ID = -1;

    public $id;
    public $type;
    public $price;
    public $startdate;
    public $enddate;

    public function hasID()
    {
        return $this->id != Utility::INVALID_ID;
    }

    public function isValid()
    {
        if (empty($this->name))
            return false;

        if (!isset($this->value))
            return false;

        return true;
    }
}
