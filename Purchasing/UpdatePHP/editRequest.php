<?php
include '../../connection.php';

$request_ID     = mysqli_real_escape_string($link, $_POST['request_ID']);
$request_supplier     = mysqli_real_escape_string($link, $_POST['request_supplier']);
$department           = mysqli_real_escape_string($link, $_POST['department']);
$cost_code            = mysqli_real_escape_string($link, $_POST['cost_code']);
$request_description  = mysqli_real_escape_string($link, $_POST['description']);
$part_number          = mysqli_real_escape_string($link, $_POST['part_number']);
$quantity             = mysqli_real_escape_string($link, $_POST['quantity']);
$unit_price           = mysqli_real_escape_string($link, $_POST['unit_price']);
$request_price        = mysqli_real_escape_string($link, $_POST['request_price']);
$part_description     = mysqli_real_escape_string($link, $_POST['part_description']);
$timeframe            = mysqli_real_escape_string($link, $_POST['orderTimeframe']);
$timeframeDate        = mysqli_real_escape_string($link, $_POST['orderTimeframeDate']);

// If the user has chosen a specific date, add that but not the text "specific date".
if($timeframe === "Specific date"){
	$timeframe = $timeframeDate;
}

$sql = "UPDATE order_request SET request_supplier = '$request_supplier', department = '$department', cost_code = '$cost_code', request_description = '$request_description', part_number = '$part_number', quantity = '$quantity', unit_price = '$unit_price', request_price = '$request_price', unit_description = '$part_description', timeframe = '$timeframe'
		WHERE request_ID = '$request_ID';";
$result = mysqli_query($link, $sql);
if(!$result){
	echo("Could not update request: ".mysqli_error($link));
}

?>
