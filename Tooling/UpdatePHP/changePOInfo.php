<?php

include '../connection.php';

$po_ID 				   = mysqli_real_escape_string($link, $_POST['po_ID']);
$input_date 		   = mysqli_real_escape_string($link, $_POST['input_date']);
$input_initial_inspect = mysqli_real_escape_string($link, $_POST['input_initial_inspect']);
$input_number_of_lines = mysqli_real_escape_string($link, $_POST['input_number_of_lines']);
$shipping_info 		   = mysqli_real_escape_string($link, $_POST['shipping_info']);
$input_po_number 	   = mysqli_real_escape_string($link, $_POST['input_po_number']);
$customer_ID 	   = mysqli_real_escape_string($link, $_POST['customer_ID']);

// we do this so all the following update additions
// can start with ',' instead of 'SET'
$sql = "UPDATE pos SET nr_of_lines = nr_of_lines ";


if(!empty($input_date)){
	$sql .= ", receiving_date = '$input_date' ";
}
if(!empty($input_initial_inspect)){
	$sql .= ", initial_inspection = '$input_initial_inspect' ";
}
if(!empty($input_number_of_lines)){
	$sql .= ", nr_of_lines = '$input_number_of_lines' ";
}
if(!empty($shipping_info)){
	$sql .= ", shipping_info = '$shipping_info' ";
}
if(!empty($input_po_number)){
	$sql .= ", po_number = '$input_po_number' ";
}
if(!empty($customer_ID)){
	$sql .= ", customer_ID = '$customer_ID' ";
}

$sql .= "WHERE po_ID = '$po_ID';";


$sqlResult = mysqli_query($link, $sql);

mysqli_close($link);
?>
