<?php namespace UASmartHome;

class Achievement {

  public $description;
  public $achieved;
  public $receivedDate;

  public function __construct($description, $achieved, $receivedDate) {
      $this->description = $description;
      $this->achieved = $achieved;
      $this->receivedDate = $receivedDate;
  }

}


