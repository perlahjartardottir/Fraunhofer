<?php

include '../connection.php';

$po_ID = mysqli_real_escape_string($link, $_POST['po_ID']);
$line = mysqli_real_escape_string($link, $_POST['line']);
$input_quantity = mysqli_real_escape_string($link, $_POST['input_quantity']);
$input_price = mysqli_real_escape_string($link, $_POST['input_price']);
$input_tool = mysqli_real_escape_string($link, $_POST['input_tool']);
$input_diameter = mysqli_real_escape_string($link, $_POST['input_diameter']);
$input_length = mysqli_real_escape_string($link, $_POST['input_length']);
$end = mysqli_real_escape_string($link, $_POST['input_end']);


// we do this so all the following update additions
// can start with ',' instead of 'SET'
$sql = "UPDATE lineitem SET line_on_po = line_on_po ";

if(!empty($input_quantity)){
	$sql .= ", quantity = '$input_quantity' ";
}
if(!empty($input_price)){
	$sql .= ", price = '$input_price' ";
}
if(!empty($input_tool)){
	$sql .= ", tool_ID = '$input_tool' ";
}
if(!empty($input_diameter)){
	$sql .= ", diameter = '$input_diameter' ";
}
if(!empty($input_length)){
	$sql .= ", length = '$input_length' ";
}
if($end == '0' || $end == '1'){
	$sql .= ", double_end = '$end' ";
}



$sql .= "WHERE po_ID = '$po_ID'
		 AND line_on_po = '$line';";

var_dump($sql);
$sqlResult = mysqli_query($link, $sql);

mysqli_close($link);
?>