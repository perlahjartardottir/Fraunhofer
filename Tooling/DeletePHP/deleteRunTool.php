<?php
/*
	This file deletes a lineitem from the linked run.
	I.e deletes a line in the lineitem_run table.
	We need to use the run_ID here so we dont delete more than one
	line, since a tool might be coated more than once in some cases.
	It also updates quantity_on_packinglist.
	If there is an error we let the user know with a mysqli_error message
	If there were no errors our jquery refreshes the table so the user sees the change
*/
include '../connection.php';

session_start();

$po_ID = $_SESSION["po_ID"];
$line_on_po = mysqli_real_escape_string($link, $_POST['lineitem']);
$run_ID = mysqli_real_escape_string($link, $_POST['run_ID']);

// getting the right po_ID
$po_IDSql = "SELECT p.po_ID
             FROM   pos p
             WHERE p.po_number = '$po_ID';";
$po_IDResult = mysqli_query($link, $po_IDSql);

while($row = mysqli_fetch_array($po_IDResult)){
    $po_ID = $row[0];
}

// Getting the right lineitem ID
$lineitemSql = "SELECT lineitem_ID
                FROM lineitem
                WHERE po_ID = '$po_ID'
                AND line_on_po = '$line_on_po'";
$lineitemResult = mysqli_query($link, $lineitemSql);

while($row = mysqli_fetch_array($lineitemResult)){
    $lineitem_ID = $row[0];
}

$runQuantitySql = "SELECT number_of_items_in_run
                   FROM lineitem_run
                   WHERE lineitem_ID = '$lineitem_ID'
                   AND run_ID = '$run_ID'";
$runQuantityResult = mysqli_query($link, $runQuantitySql);
if(!$runQuantityResult){
	 echo ("Error runQuant" . mysqli_error($link));
}
while($row = mysqli_fetch_array($runQuantityResult)){
	$run_quantity = $row[0];
}

// before we delete we subtract the amount we are deleting from the quantity_on_packinglist in the lineitem table
$subtractSql = "UPDATE lineitem
                SET quantity_on_packinglist = quantity_on_packinglist - $run_quantity
                WHERE lineitem_ID = '$lineitem_ID'";

$subtractResult = mysqli_query($link, $subtractSql);
if(!$subtractResult){
	 echo ("Error subtract" . mysqli_error($link));
}

// we will use the run_ID and lineitem_ID to delete from the lineitem_run table
$deleteSql = "DELETE FROM lineitem_run
              WHERE run_ID = '$run_ID'
              AND lineitem_ID = '$lineitem_ID';";
$deleteResult = mysqli_query($link, $deleteSql);

if(!$deleteResult){
	 echo ("Error deleting" . mysqli_error($link));
}
mysqli_close($link);
?>
