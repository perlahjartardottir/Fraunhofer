<?php
/*
	This file inserts a line in the lineitem_run table, so we have a link between the runs
	and the line items. The only extra data we store in that table is
	a final comment for the line item and the number of tools in that run.
	The comment can be empty but the other fields are required. The jQuery
	function makes sure of this.
*/
include '../connection.php';
session_start();
$po_ID = $_SESSION['po_ID'];
$line_on_po   	  = mysqli_real_escape_string($link, $_POST['lineItem']);
$number_of_tools  = mysqli_real_escape_string($link, $_POST['number_of_tools']);
$run_number 	  = mysqli_real_escape_string($link, $_POST['runNumber']);
$lineitem_comment = mysqli_real_escape_string($link, $_POST['final_comment']);

$run_number_int = 0;

if($run_number == 'a' || $run_number == 'A'){ $run_number_int = 1;}

if($run_number == 'b' || $run_number == 'B'){ $run_number_int = 2;}

if($run_number == 'c' || $run_number == 'C'){ $run_number_int = 3;}

if($run_number == 'd' || $run_number == 'D'){ $run_number_int = 4;}

if($run_number == 'e' || $run_number == 'E'){ $run_number_int = 5;}

if($run_number == 'f' || $run_number == 'F'){ $run_number_int = 6;}

if($run_number == 'g' || $run_number == 'G'){ $run_number_int = 7;}

if($run_number_int == 0){
	die("Error! Run# on this PO must be a letter from a-g");
}
// if there is no PO selected
if(empty($po_ID)){
	die("Error! No PO selected.");
}

// Find the right lineitem by querying the database
// Find the lineitem that has the right po_ID and the right line_on_po
$lineitemSql = "SELECT lineitem_ID
								FROM lineitem
								WHERE po_ID = '$po_ID'
								AND line_on_po = '$line_on_po'";
$lineitemResult = mysqli_query($link, $lineitemSql);
while($row = mysqli_fetch_array($lineitemResult)){
	$lineitem_ID = $row[0];
}

if (mysqli_num_rows($lineitemResult) == 0) {
    die("Error! There is no Line item: ".$line_on_po);
}

// Find the right run
// Need to find the run that has the right po_ID and the right run_number_on_po in the pos_run table
$runIDsql = "SELECT run_ID
						 FROM pos_run posr
						 WHERE run_number_on_po = '$run_number_int'
						 AND po_ID = '$po_ID';";

$runIDresult = mysqli_query($link, $runIDsql);

if(mysqli_num_rows($runIDresult) == 0){
	die("Error! There is no run with run number ".$run_number." on this PO.");
}
while($row = mysqli_fetch_array($runIDresult)){
	$run_ID = $row[0];
}

$sql = "INSERT INTO lineitem_run VALUES('$lineitem_ID', '$run_ID','$number_of_tools', '$lineitem_comment')";

$resultSql = mysqli_query($link, $sql);

if (!$resultSql) {
	die("Error! This lineitem is in that run already.");
}

// if there are no errors update the quantity_on_packinglist in the lineitem table

$packinglistSql = "UPDATE lineitem
								   SET quantity_on_packinglist = quantity_on_packinglist + $number_of_tools
								   WHERE lineitem_ID = $lineitem_ID;";

$packinglistResult = mysqli_query($link, $packinglistSql);
// check if the number on packing list is higher than the quantity of tools on PO
// If there are more we let the user know
$quantitySql = "SELECT quantity, quantity_on_packinglist
								FROM lineItem
								WHERE lineitem_ID = $lineitem_ID;";

$quantityResult = mysqli_query($link, $quantitySql);

$row = mysqli_fetch_array($quantityResult);

if($row[1] > $row[0]){
	$extratools = $row[1] - $row[0];
	die("Warning! Line ".$line_on_po." has ".$extratools." extra tools assigned to runs. This might either be re-runs or an input error!");
}
// if fail let user know what went wrong

mysqli_close($link);
?>
