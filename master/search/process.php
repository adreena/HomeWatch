<?php
header('Content-type: text/html; charset=utf-8');

$sensors = array();
$apartments = array();
$carbon_dioxide = array();
//$date = $_GET("datepicker");
$date = "2012-03-01";

//if(empty($_GET)) {}
if($_GET['sensors']) {
  foreach($_GET['sensors'] as $value) {
    array_push($sensors, $value);
  }
}

if($_GET['apartments']) {
  foreach($_GET['apartments'] as $value) {
    array_push($apartments, $value);
  }
}

//the following code chunk needs to be replaced by result set
$data_file = file_get_contents("test.json");
$jsonIterator = new RecursiveIteratorIterator(
  new RecursiveArrayIterator(json_decode($data_file, TRUE)),
  RecursiveIteratorIterator::SELF_FIRST);

$index = 0;

foreach($jsonIterator as $key => $value) {
  if(is_array($value)) {
    // do nothing
  } else {
    if($key == "Date"){
      $temp_date = $value;
      continue;
    } else {
      if($temp_date == $date) {
        if($key == "Apt") {
          $temp_apart = $value;
	  continue;
        } else {          
	  if($temp_apart == "2") {
            if($key == "CO2") {
              $carbon_dioxide[$index] = $value;
              $index++;
            }
          }
        }        
      }
    }
  }
}
echo json_encode($carbon_dioxide);
?>