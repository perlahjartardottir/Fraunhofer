<?php
include '../connection.php';
session_start();
$po_ID = $_SESSION["po_ID"];
$line_item = mysqli_real_escape_string($link, $_POST['line']);

// Find the right lineitem_ID
$lineitemSql = "SELECT lineitem_ID
								FROM lineitem
								WHERE po_ID = '$po_ID'
								AND line_on_po = '$line_item'";
$lineitemResult = mysqli_query($link, $lineitemSql);
while($row = mysqli_fetch_array($lineitemResult)){
	$lineitem_ID = $row[0];
}

// Query to find the run that line item belongs to
// And gives error message when you try to delete
// Line item that belongs to any runs
$runSql = "SELECT run_ID
					 FROM lineitem_run
					 WHERE lineitem_ID = '$lineitem_ID';";
$runResult = mysqli_query($link, $runSql);
if (mysqli_num_rows($runResult) != 0){
	$run_ID = mysqli_fetch_array($runResult);
	$runNumberSql = "SELECT run_number
									 FROM run
									 WHERE run_ID = '$run_ID[0]';";
	$runNumberResult = mysqli_query($link, $runNumberSql);
	$runNumber = mysqli_fetch_array($runNumberResult);

	die('Error! This line item belongs to run: <a href="../views/generateTrackSheet.php">'.$runNumber[0].'</a>');
}

// first we delete the tool from the regulartool and the odd tool table
$toolSql = "DELETE FROM regulartool
						WHERE lineitem_ID = '$lineitem_ID'";
$toolResult = mysqli_query($link, $toolSql);

$toolSql = "DELETE FROM oddshapedtool
						WHERE lineitem_ID = '$lineitem_ID'";
$toolResult = mysqli_query($link, $toolSql);

// then we delete the item that has the right line number on the right PO
$sql = "DELETE FROM lineitem
				WHERE po_ID = '$po_ID'
				AND line_on_po = '$line_item'";
$result = mysqli_query($link, $sql);


//if the query goes wrong
if(!$result){
  die('Error! This line item belongs to a run');
}
mysqli_close($link);
?>
