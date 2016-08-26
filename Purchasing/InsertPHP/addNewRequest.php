<?php
include '../../connection.php';

$request_supplier     = mysqli_real_escape_string($link, $_POST['request_supplier']);
$timeframe            = mysqli_real_escape_string($link, $_POST['orderTimeframe']);
$timeframeDate        = mysqli_real_escape_string($link, $_POST['orderTimeframeDate']);
$department           = mysqli_real_escape_string($link, $_POST['department']);
$cost_code            = mysqli_real_escape_string($link, $_POST['cost_code']);
$request_description  = mysqli_real_escape_string($link, $_POST['request_description']);
$employee_ID          = mysqli_real_escape_string($link, $_POST['employee_ID']);
$part_number          = mysqli_real_escape_string($link, $_POST['part_number']);
$quantity             = mysqli_real_escape_string($link, $_POST['quantity']);
$request_price        = mysqli_real_escape_string($link, $_POST['request_price']);
$unit_price        = mysqli_real_escape_string($link, $_POST['unit_price']);

if($timeframe === "Specific date"){
	$timeframe = $timeframeDate;
}
// Insert all the fields to the request, no matter if they are empty or not
$sql = "INSERT INTO order_request (employee_ID, timeframe, department, cost_code, request_description, request_date, active, request_supplier, part_number, quantity, request_price, unit_price)
        VALUES ('$employee_ID', '$timeframe', '$department', '$cost_code', '$request_description', CURDATE(), 1, '$request_supplier', '$part_number', '$quantity', '$request_price','$unit_price');";
$result = mysqli_query($link, $sql);

// mysqli_insert_id fetches the last inserted row
$request_ID = mysqli_insert_id($link);

// create_request = 0 means that the quote is no longer active
// this query deactivates all active quotes and links them to the request
$quoteSql = "UPDATE quote
             SET create_request = 0, request_ID = '$request_ID'
             WHERE create_request = 1;";
$quoteResult = mysqli_query($link, $quoteSql);

if(!$result){
	echo("Something went wrong : ".mysqli_error($link));
}
?>
