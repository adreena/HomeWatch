<?php namespace UASmartHome;

class View {
  private $model;
  private $controller;

  public function __construct(Controller $controller, Model $model) {
      $this->controller = $controller;
      $this->model = $model;
  }

  public function getAchievements() {

      $achievements = array (
          new Achievement("Maintain water usage under <x> for a month!", True, "08/10/2013"),
          new Achievement("Maintain water usage under <x+5> for a month!" , True, "08/10/2013"),
          new Achievement("Maintain water usage under <x+10> for a month!", False, "08/10/2013"),
          new Achievement("Maintain electricity usage under <x> for a month!", True, "08/10/2013"),
          new Achievement("Maintain electricity usage under <x+5> for a month!", True, "08/10/2013"),
          new Achievement("Maintain electricity usage under <x+10> for a month!", True, "08/10/2013"),
          new Achievement("Maintain healthy levels of humidity for a month!", True, "08/10/2013"),
          new Achievement("Maintain healthy levels of CO₂ for a month!", True, "08/10/2013"),
          new Achievement("Maintain indoor temperatures under 20°C for a week!", True, "08/10/2013"),
          new Achievement("Decrease water usage by 10% in one month!", True, "08/10/2013"),
          new Achievement("Decrease water usage by 150% in one month!", True, "08/10/2013"),
          new Achievement("Decrease water usage by 20% in one month!", True, "08/10/2013"),
          new Achievement("Decrease electricity usage by 10% in one month!", True, "08/10/2013"),
          new Achievement("Decrease electricity usage by 15% in one month!", True, "08/10/2013"),
          new Achievement("Decrease electricity usage by 20% in one month!", True, "08/10/2013")
      );

      return $achievements;
  }

}

