<?php
/*
	Get a quick view of the items on the PO
	Makes assigning tools to runs easier
	Uses the sessio po_ID
*/
include '../connection.php';
session_start();
$po_ID = $_SESSION["po_ID"];

$sql = "SELECT l.line_on_po, l.tool_ID, l.quantity
		FROM lineitem l
		WHERE po_ID = '$po_ID'
		ORDER BY line_on_po;";
$result = mysqli_query($link, $sql);

$colorBool = true;

// This loop prints out the line items in different colors for every line
while($row = mysqli_fetch_array($result)){
	if($colorBool){
		echo "<li class='list-group-item list-group-item-success'>Line# : <em>".$row[0]."</em> ToolID : <em>".$row[1]."</em> Quantity : <em>".$row[2]."</li>";
		$colorBool = !$colorBool;
	}else{
		echo "<li class='list-group-item list-group-item-info'>Line# : <em>".$row[0]."</em> ToolID : <em>".$row[1]."</em> Quantity : <em>".$row[2]."</li>";
		$colorBool = !$colorBool;
	}
}
mysqli_close($link);
?>



















