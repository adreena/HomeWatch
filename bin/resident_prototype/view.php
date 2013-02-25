<?php
    class Model {
    }

    class Controller {
    }

    class Achievement {

        public $description;
        public $achieved;
        public $received_date;

        public function __construct($description, $achieved, $received_date) {
            $this->description = $description;
            $this->achieved = $achieved;
            $this->received_date = $received_date;
        }

    }

    class View {
        private $model;
        private $controller;

        private $achieved_icon = "http://findicons.com/files/icons/1197/agua/128/home_badge.png";
        private $not_achieved_icon = "http://findicons.com/files/icons/1580/devine_icons_part_2/128/home.png";

        public function __construct(Controller $controller, Model $model) {
            $this->controller = $controller;
            $this->model = $model;
        }

        public function output() {
            $number_of_achievements = 15;

            $achievements = array (
                new Achievement("Maintain water usage under <x> for a month!", True, "08/10/2013"),
                new Achievement("Maintain water usage under <x+5> for a month!" , True, "08/10/2013"),
                new Achievement("Maintain water usage under <x+10> for a month!", False, "08/10/2013"),
                new Achievement("Maintain electricity usage under <x> for a month!", True, "08/10/2013"),
                new Achievement("Maintain electricity usage under <x+5> for a month!", True, "08/10/2013"),
                new Achievement("Maintain electricity usage under <x+10> for a month!", True, "08/10/2013"),
                new Achievement("Maintain healthy levels of humidity for a month!", True, "08/10/2013"),
                new Achievement("Maintain healthy levels of CO&#178 for a month!", True, "08/10/2013"),
                new Achievement("Maintain indoor temperatures under 20&#176C for a week!", True, "08/10/2013"),
                new Achievement("Decrease water usage by 10% in one month!", True, "08/10/2013"),
                new Achievement("Decrease water usage by 150% in one month!", True, "08/10/2013"),
                new Achievement("Decrease water usage by 20% in one month!", True, "08/10/2013"),
                new Achievement("Decrease electricity usage by 10% in one month!", True, "08/10/2013"),
                new Achievement("Decrease electricity usage by 15% in one month!", True, "08/10/2013"),
                new Achievement("Decrease electricity usage by 20% in one month!", True, "08/10/2013")
            );

            $html = '<table cellpadding="10px">';
            for ($i=0; $i<$number_of_achievements; $i++) {
                if ($i % 5 == 0) {
                    $html .= '</tr>';
                }
                if ($achievements[$i]->achieved) {
                    $html .= '<td><img src="' . $this->achieved_icon . '" alt="' . $achievements[$i]->description . '" title="' . $achievements[$i]->description . "\nAchieved on " . $achievements[$i]->received_date . '."></td>';
                }
                else {
                    $html .= '<td><img src="' . $this->not_achieved_icon . '" alt="' . $achievements[$i]->description . '" title="' . $achievements[$i]->description . '."></td>';
                }
            }

            $html .= '</tr></table>';
            return $html;
        }

    }

    $model = new Model();
    $controller = new Controller();
    $view = new View($controller, $model);
    echo $view->output();
?>
