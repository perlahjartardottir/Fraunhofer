<?php
include '../../connection.php';
session_start();

$employee_name = mysqli_real_escape_string($link, $_POST['employee_name']);
$employee_ID   = mysqli_real_escape_string($link, $_POST['employee_ID']);
$supplier_name = mysqli_real_escape_string($link, $_POST['supplier_name']);
$request_ID    = mysqli_real_escape_string($link, $_POST['request_ID']);

// Query to get the employee ID of the employee who requested the purchase
$employeeSql = "SELECT employee_ID
                FROM employee
                WHERE employee_name = '$employee_name';";
$employeeResult = mysqli_query($link, $employeeSql);
$row = mysqli_fetch_array($employeeResult);
$order_for_who = $row[0];

// Get the ID and the net terms from the supplier name
$supplierSql = "SELECT supplier_ID, net_terms
                FROM supplier
                WHERE supplier_name = '$supplier_name';";
$supplierResult = mysqli_query($link, $supplierSql);

$row = mysqli_fetch_array($supplierResult);
$supplier_ID = $row[0];
$supplier_net_terms = $row[1];

// if there is no request linked to this po then we don't add a request, we would have to add NULL
// but since we would add it as a variable then it would become 'NULL' (the string) and that is not what we want
if($request_ID == ""){
  $sql = "INSERT INTO purchase_order (supplier_ID, employee_ID, order_for_who, order_date, net_terms)
          VALUES ('$supplier_ID', '$employee_ID', '$order_for_who', CURDATE(), '$supplier_net_terms');";
}else{
  $sql = "INSERT INTO purchase_order (supplier_ID, employee_ID, order_for_who, order_date, request_ID, net_terms)
          VALUES ('$supplier_ID', '$employee_ID', '$order_for_who', CURDATE(), '$request_ID', '$supplier_net_terms');";
}
$result = mysqli_query($link, $sql);

if(!$result){
	echo("Something went wrong : ".mysqli_error($link));
}

// mysqli_insert_id fetches the last inserted row
$_SESSION["order_ID"] = mysqli_insert_id($link);
$order_ID = $_SESSION["order_ID"];

// Add CCD- as a prefix for the purchase order name
$orderNameSql = "UPDATE purchase_order
                 SET order_name = 'CCD-".$order_ID."'
                 WHERE order_ID = '$order_ID';";
$orderNameResult = mysqli_query($link, $orderNameSql);

if(!$orderNameResult){
	echo("Something went wrong : ".mysqli_error($link));
}
?>
