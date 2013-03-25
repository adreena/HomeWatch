<?php namespace UASmartHome\Database;

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
