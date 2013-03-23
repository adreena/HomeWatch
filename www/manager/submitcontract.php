<?php namespace UASmartHome;

require_once __DIR__ . '/../vendor/autoload.php';

$view = new \UASmartHome\ManagerView();

if($_POST['formSubmit'] == "Submit New" || $_POST['formSubmit'] == "Submit Edit")
{
  $errorMessage = "";
 
  if(empty($_POST['type']))
  {
    $errorMessage .= "<li>You forgot to enter a type!</li>";
  }
  if(empty($_POST['price']))
  {
    $errorMessage .= "<li>You forgot to enter a price!</li>";
  }
  if(empty($_POST['startdate']))
  {
    $errorMessage .= "<li>You forgot to enter a starting date!</li>";
  }
  if(empty($_POST['enddate']))
  {
    $errorMessage .= "<li>You forgot to enter an ending date!</li>";
  }
 
  $Type = $_POST['type'];
  $Price = $_POST['price'];
  $Start_Date = $_POST['startdate'];
  $End_Date = $_POST['enddate'];
 
  if(!empty($errorMessage))
  {
    echo("<p>There was an error with your form:</p>\n");
    echo("<ul>" . $errorMessage . "</ul>\n");
	if($_POST['formSubmit'] == "Submit New")
	{
		echo("<FORM action='addcontracts.php' method='post'><P><INPUT type='submit' name='Retry' value='Retry'></P></FORM>");
	}
	else if($_POST['formSubmit'] == "Submit Edit")
	{
		echo("<FORM action='editcontracts.php' method='post'><P><INPUT type='submit' name='Retry' value='Retry'></P></FORM>");
	}
	echo("<FORM action='utilitycontracts.php' method='post'><P><INPUT type='submit' name='Cancel' value='Cancel'></P></FORM>");
  }
  else
  {
	if($_POST['formSubmit'] == "Submit New")
	{
		$view->submitContract($Type, $Price, $Start_Date, $End_Date);
		echo("<p>Contract has been submitted.</p>\n");
	}
	else if($_POST['formSubmit'] == "Submit Edit")
	{
		$view->editContract($Type, $Price, $Start_Date, $End_Date);
		echo("<p>Contract change has been submitted.</p>\n");
	}
	echo("<FORM action='utilitycontracts.php' method='post'><P><INPUT type='submit' name='Continue' value='Continue'></P></FORM>");
  }
 
}

