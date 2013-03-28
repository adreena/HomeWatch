<?php namespace UASmartHome\Database\Configuration;

require_once __DIR__ . '/../../../../vendor/autoload.php';

class Equation {

    const INVALID_ID = -1;
    
    public $id;
    public $name;
    public $body;
    public $description;
    
    public function hasID()
    {
        return $this->id != Equation::INVALID_ID;
    }
    
    public function isValid()
    {
        if (empty($this->name))
            return false;
        
        if (!isset($this->body))
            return false;
        
        return true;
    }
    
}
