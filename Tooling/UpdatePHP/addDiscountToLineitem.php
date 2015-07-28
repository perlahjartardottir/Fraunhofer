<?php
include '../connection.php';

session_start();
$lineitem_ID 		   = $_SESSION["lineitem_ID"];
$discount_quantity 	   = mysqli_real_escape_string($link, $_POST['quantity']);
$discount 	   		   = mysqli_real_escape_string($link, $_POST['discount']);
$discount_reason 	   = mysqli_real_escape_string($link, $_POST['reason']);

$sql = "INSERT INTO discount(lineitem_ID, number_of_tools, discount, discount_reason) 
	    VALUES('$lineitem_ID', '$discount_quantity', '$discount', '$discount_reason');";
$sqlResult = mysqli_query($link, $sql);

if(!$sqlResult){
	$message  = 'Invalid query: ' . mysqli_error($link);
    die($message);
}
mysqli_close($link);

?>