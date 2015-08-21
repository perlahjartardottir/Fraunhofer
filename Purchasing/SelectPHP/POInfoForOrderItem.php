<?php

include '../../connection.php';
session_start();
$order_ID = mysqli_real_escape_string($link, $_POST['order_ID']);

$_SESSION["order_ID"] = $order_ID;

// Get all information for the PO
$sql = "SELECT order_ID, employee_ID, order_for_who, supplier_ID, approved_by, order_date, order_name
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

echo "<p>".'Purchase order: '.$row[0]."</p>";
echo "<p>".'Order created by: '.$employee[0]."</p>";
echo "<p>".'For employee: '.$orderForWho[0]."</p>";
echo "<p>".'Supplier: '.$supplier[0]."</p>";
echo "<p>".'Approved by: '.$row[4]."</p>";
echo "<p>".'Order date: '.$row[5]."</p>";
echo "<p>Order Number: <input type='text' id='order_name' value='".$row[6]."'><button class='btn btn-primary' style='margin-left: 10px;' onclick='editOrderNumber()'>Edit Order Number</button></p>";
mysqli_close($link);
?>
