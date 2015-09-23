<?php
/*
	This file inserts a connection between an already existing run
	and a PO.
	We need to query the database to find which run_number on the po it is.
*/

include '../connection.php';
session_start();
$po_ID = $_SESSION["po_ID"];
$run_ID	   = mysqli_real_escape_string($link, $_POST['old_run']);

// find the next run number on this po by selecting the highest one in the database
// and adding 1 to it
$runOnPoSql = "SELECT IF(MAX(run_number_on_po) IS NULL, 1, MAX(run_number_on_po) + 1)
			   			 FROM pos_run
			   	 		 WHERE po_ID = '$po_ID';";
$runOnPoResult = mysqli_query($link, $runOnPoSql);
while($row = mysqli_fetch_array($runOnPoResult)){
	$right_run_number = $row[0];
}

$insertSql = "INSERT INTO pos_run VALUES('$run_ID', '$po_ID', '$right_run_number')";

$insertResult = mysqli_query($link, $insertSql);
if(!$insertResult){
  echo ("There was an error deleting the run" . mysql_error($link));
}
mysqli_close($link);
?>
