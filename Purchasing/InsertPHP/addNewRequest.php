<?php
include '../../connection.php';

$request_supplier     = mysqli_real_escape_string($link, $_POST['request_supplier']);
$approved_by_employee = mysqli_real_escape_string($link, $_POST['approved_by_employee']);
$request_description  = mysqli_real_escape_string($link, $_POST['request_description']);
$employee_ID          = mysqli_real_escape_string($link, $_POST['employee_ID']);

$sql = "INSERT INTO order_request (employee_ID, approved_by_employee, request_description, request_date, active, request_supplier)
        VALUES ('$employee_ID', '$approved_by_employee', '$request_description', CURDATE(), 1, '$request_supplier');";
$result = mysqli_query($link, $sql)
?>
