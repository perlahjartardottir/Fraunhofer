<?php
include '../../connection.php';
session_start();

$employee_name = mysqli_real_escape_string($link, $_POST['employee_name']);
$employee_ID   = mysqli_real_escape_string($link, $_POST['employee_ID']);
$supplier_name = mysqli_real_escape_string($link, $_POST['supplier_name']);
$approved_by   = mysqli_real_escape_string($link, $_POST['approved_by']);
$request_ID    = mysqli_real_escape_string($link, $_POST['request_ID']);

// Query to get the employee ID of the employee who requested the purchase
$employeeSql = "SELECT employee_ID
                FROM employee
                WHERE employee_name = '$employee_name';";
$employeeResult = mysqli_query($link, $employeeSql);
$row = mysqli_fetch_array($employeeResult);
$order_for_who = $row[0];

$supplierSql = "SELECT supplier_ID
                FROM supplier
                WHERE supplier_name = '$supplier_name';";
$supplierResult = mysqli_query($link, $supplierSql);
$row = mysqli_fetch_array($supplierResult);
$supplier_ID = $row[0];
var_dump($supplier_ID);

$sql = "INSERT INTO purchase_order (supplier_ID, employee_ID, order_for_who, approved_by, order_date, request_ID)
        VALUES ('$supplier_ID', '$employee_ID', '$order_for_who', '$approved_by', CURDATE(), '$request_ID');";
$result = mysqli_query($link, $sql);

if(!$result){
	echo("Something went wrong : ".mysqli_error($link));
}
// mysqli_insert_id fetches the last inserted row
$_SESSION["order_ID"] = mysqli_insert_id($link);
?>
