<?php namespace UASmartHome\Database;

require_once __DIR__ . '/../../../vendor/autoload.php';

class Equation {

    const INVALID_ID = -1;
    
    public $id;
    public $name;
    public $body;
    public $description;
    
    public function exists() {
        return $this->id != Equation::INVALID_ID;
    }
    
}
