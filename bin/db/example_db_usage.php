<?php

require 'Database.php';

// Example code
// must code
$testdb=new DB ();
$testdb->db_open_connection();
// example of query of view v_air for appartment 1 and return column CO2 for the specified date & hour
$column = "CO2";
$result = $testdb->db_query("v_air",1,$column,'2012-2-29',22);

// echo number of rows retrived

echo "Number of Rows : ".mysql_numrows($result);
echo "<br />";
echo "===========================";
echo "<br />";

while($row = mysql_fetch_array($result)) {
    echo "Apt : ".$row['Apt'];
    echo "<br />";
    echo $column.": ".$row[$column];
    echo "<br />";
    echo "---------------------";
    echo "<br />";
}

// example of insert new tempreature to the tempreature table
$testdb->db_insert_temp('2012-2-29',93);
// must close at the end of code
$testdb->db_close_connection();

