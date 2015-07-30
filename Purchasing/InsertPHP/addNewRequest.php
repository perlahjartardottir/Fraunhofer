<?php
include '../../connection.php';

$request_supplier     = mysqli_real_escape_string($link, $_POST['request_supplier']);
$request_quantity     = mysqli_real_escape_string($link, $_POST['request_quantity']);
$approved_by_employee = mysqli_real_escape_string($link, $_POST['approved_by_employee']);
$request_description  = mysqli_real_escape_string($link, $_POST['request_description']);
$employee_ID          = mysqli_real_escape_string($link, $_POST['employee_ID']);

$sql = "INSERT INTO order_request (employee_ID, approved_by_employee, request_description, request_date, active, request_supplier, request_quantity)
        VALUES ('$employee_ID', '$approved_by_employee', '$request_description', CURDATE(), 1, '$request_supplier', '$request_quantity');";
$result = mysqli_query($link, $sql)
?>
