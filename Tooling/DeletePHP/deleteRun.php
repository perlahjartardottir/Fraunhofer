<?php
/*
	This file deletes a run on a POS track sheet. I.e. removes the run from the pos_run table
	If this is the last PO that has this run on its track sheet
	the run is also deleted from the run table
	we do this by calling a procedure in MySQL called check_delete_run found in procedures.sql
*/
include '../connection.php';
session_start();
$po_ID = $_SESSION["po_ID"];
$run_number	= mysqli_real_escape_string($link, $_POST['line']);

$run_IDsql = "SELECT run_ID
			  			FROM run
			  			WHERE run_number = '$run_number';";
$run_IDresult = mysqli_query($link, $run_IDsql);
while($row = mysqli_fetch_array($run_IDresult)){
	$run_ID = $row[0];
}

$set_run_ID = "SET @run_ID = '$run_ID';";
$set_run_ID_result = mysqli_query($link, $set_run_ID);

$set_po_ID = "SET @po_ID = '$po_ID';";
$set_po_ID_result = mysqli_query($link, $set_po_ID);

$sql = "CALL check_delete_run(@po_ID, @run_ID);";
$result = mysqli_query($link, $sql);

if(!$result){
	echo ("There was an error deleting the run" . mysql_error($link));
}
mysqli_close($link);
?>
