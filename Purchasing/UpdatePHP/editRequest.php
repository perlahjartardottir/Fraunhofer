<?php
include '../../connection.php';

$request_supplier     = mysqli_real_escape_string($link, $_POST['request_supplier']);
$department           = mysqli_real_escape_string($link, $_POST['department']);
$cost_code            = mysqli_real_escape_string($link, $_POST['cost_code']);
$request_description  = mysqli_real_escape_string($link, $_POST['description']);
$part_number          = mysqli_real_escape_string($link, $_POST['part_number']);
$quantity             = mysqli_real_escape_string($link, $_POST['quantity']);
$unit_price        	= mysqli_real_escape_string($link, $_POST['unit_price']);
$request_price        = mysqli_real_escape_string($link, $_POST['request_price']);

$sql = "UPDATE order_request SET request_supplier = '$request_supplier', department = '$department', cost_code = '$cost_code', request_description = '$request_description', part_number = '$part_number', quantity = '$quantity', unit_price = '$unit_price',
		request_price = '$request_price';";
$result = mysqli_query($link, $sql);
if(!$result){
	echo("Could not update request: ".mysqli_error($link));
}

?>
