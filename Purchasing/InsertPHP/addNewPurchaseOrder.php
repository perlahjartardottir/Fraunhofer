<?php
include '../../connection.php';
session_start();

$employee_name = mysqli_real_escape_string($link, $_POST['employee_name']);
$employee_ID   = mysqli_real_escape_string($link, $_POST['employee_ID']);
$supplier_name = mysqli_real_escape_string($link, $_POST['supplier_name']);
$approved_by   = mysqli_real_escape_string($link, $_POST['approved_by']);

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

$sql = "INSERT INTO purchase_order (supplier_ID, employee_ID, order_for_who, approved_by, order_date)
        VALUES ('$supplier_ID', '$employee_ID', '$order_for_who', '$approved_by', CURDATE());";
$result = mysqli_query($link, $sql);

if(!$result){
	echo("Something went wrong : ".mysqli_error($link));
}
// mysqli_insert_id fetches the last inserted row
$_SESSION["order_ID"] = mysqli_insert_id($link);
?>
