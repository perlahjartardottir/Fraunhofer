<?php

include '../../connection.php';
session_start();
$order_ID = mysqli_real_escape_string($link, $_POST['order_ID']);

$_SESSION["order_ID"] = $order_ID;

// Get all information for the PO
$sql = "SELECT order_ID, employee_ID, order_for_who, supplier_ID, approved_by, order_date, order_name, expected_delivery_date, net_terms
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
$supplierSql ="SELECT supplier_name, net_terms
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
echo "<p>Order number: <input type='text' id='order_name' value='".$row[6]."'><button class='btn btn-primary' style='margin-left: 10px;' onclick='editOrderNumber()'>Edit Order Number</button></p>";
echo "<p>Expected delivery date: <input type='date' id='expected_delivery_date' value='".$row[7]."'><button class='btn btn-primary' style='margin-left: 10px;' onclick='editExpectedDeliveryDate(); return false;'>Edit Date</button></p>";
echo "<p>Net terms (days): <input type='number' id='net_terms' value='".$row[8]."'><button class='btn btn-primary' style='margin-left: 10px;' onclick='editNetTerms(); return false;'>Edit Net Terms</button></p>";
// echo "<p><input type='checkbox' id='credit' onchange='payByCredit()'> Pay by credit card</p>";
echo "<script>
				$(document).ready(function() {
					$('input[type=date]').each(function() {
		        if  (this.type != 'date' ) $(this).datepicker({
		          dateFormat: 'yy-mm-dd'
		        });
		      });
				});
			</script>";
?>
