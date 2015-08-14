<?php

include '../../connection.php';
session_start();
$order_ID = mysqli_real_escape_string($link, $_POST['order_ID']);

$_SESSION["order_ID"] = $order_ID;

// Get all information for the PO
$sql = "SELECT order_ID, employee_ID, order_for_who, supplier_ID, approved_by, order_date, department_ID
		FROM purchase_order
		WHERE order_ID = '$order_ID';";
$result = mysqli_query($link, $sql);
$row = mysqli_fetch_array($result);

// Query to find the employee who created the purchase order
$employeeSql = "SELECT employee_name
                FROM employee
                WHERE employee_ID = '$row[1]';";
$employeeResult = mysqli_query($link, $employeeSql);
$employee = mysqli_fetch_array($employeeResult);

//Query to find the name of the employee who this order is for
$orderForWhoSql = "SELECT employee_name
                   FROM employee
                   WHERE employee_ID = '$row[2]';";
$orderForWhoResult = mysqli_query($link, $orderForWhoSql);
$orderForWho = mysqli_fetch_array($orderForWhoResult);

//Query to find the supplier name
$supplierSql ="SELECT supplier_name
               FROM supplier
               WHERE supplier_ID = '$row[3]';";
$supplierResult = mysqli_query($link, $supplierSql);
$supplier = mysqli_fetch_array($supplierResult);

// Find the department name
$departmentSql = "SELECT department_name
									FROM department
									WHERE department_ID = '$row[6]';";
$departmentResult = mysqli_query($link, $departmentSql);
$department = mysqli_fetch_array($departmentResult);

echo "<p>".'Purchase order: '.$row[0]."</p>";
echo "<p>".'Order created by: '.$employee[0]."</p>";
echo "<p>".'For employee: '.$orderForWho[0]."</p>";
echo "<p>".'Supplier: '.$supplier[0]."</p>";
if($department[0] != ""){
	echo "<p>".'Department: '.$department[0]."</p>";
}
echo "<p>".'Approved by: '.$row[4]."</p>";
echo "<p>".'Order date: '.$row[5]."</p>";

mysqli_close($link);
?>
