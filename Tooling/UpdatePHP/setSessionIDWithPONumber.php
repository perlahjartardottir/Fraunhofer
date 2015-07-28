<?php
/*
	This file sets the session po_ID to the user input
*/
session_start();
include '../connection.php';

$po_ID = mysqli_real_escape_string($link, $_GET['po_ID']);

//	Find the right po_id from the po_number from the user
$po_IDsql = "SELECT p.po_ID
             FROM   pos p
             WHERE p.po_number = '$po_ID';";
$po_IDresult = mysqli_query($link, $po_IDsql);

while($row = mysqli_fetch_array($po_IDresult)){
    $POID = $row[0];
}

$_SESSION["po_ID"] = $POID;


mysqli_close($link);
?>
