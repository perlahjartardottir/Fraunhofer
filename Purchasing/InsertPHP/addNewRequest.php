<?php
include '../../connection.php';

$request_supplier     = mysqli_real_escape_string($link, $_POST['request_supplier']);
$timeframe            = mysqli_real_escape_string($link, $_POST['orderTimeframe']);
$department           = mysqli_real_escape_string($link, $_POST['department']);
$cost_code            = mysqli_real_escape_string($link, $_POST['cost_code']);
$approved_by_employee = mysqli_real_escape_string($link, $_POST['approved_by_employee']);
$request_description  = mysqli_real_escape_string($link, $_POST['request_description']);
$employee_ID          = mysqli_real_escape_string($link, $_POST['employee_ID']);
$part_number          = mysqli_real_escape_string($link, $_POST['part_number']);
$quantity             = mysqli_real_escape_string($link, $_POST['quantity']);

$sql = "INSERT INTO order_request (employee_ID, timeframe, department, cost_code, approved_by_employee, request_description, request_date, active, request_supplier, part_number, quantity)
        VALUES ('$employee_ID', '$timeframe', '$department', '$cost_code', '$approved_by_employee', '$request_description', CURDATE(), 1, '$request_supplier', '$part_number', '$quantity');";
$result = mysqli_query($link, $sql);

// mysqli_insert_id fetches the last inserted row
$request_ID = mysqli_insert_id($link);
$quoteSql = "UPDATE quote
             SET create_request = 0, request_ID = '$request_ID'
             WHERE create_request = 1;";
$quoteResult = mysqli_query($link, $quoteSql);

if(!$result){
	echo("Something went wrong : ".mysqli_error($link));
}
?>
