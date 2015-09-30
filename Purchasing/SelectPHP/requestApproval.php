<?php
include '../../connection.php';
session_start();
$employee_ID = mysqli_real_escape_string($link, $_POST['employee_ID']);
$order_ID = $_SESSION['order_ID'];

// Find the ID of the employee who this order is for
$orderForWhoSql = "SELECT order_for_who, order_name
                   FROM purchase_order
                   WHERE order_ID = '$order_ID';";
$orderForWhoResult = mysqli_query($link, $orderForWhoSql);
$orderForWhoRow = mysqli_fetch_array($orderForWhoResult);

// Find the email address of the employee who this order is for
$orderForWhoEmailSql = "SELECT employee_email
                        FROM employee
                        WHERE employee_ID = '$orderForWhoRow[0]';";
$orderForWhoEmailResult = mysqli_query($link, $orderForWhoEmailSql);
$orderForWhoEmail = mysqli_fetch_array($orderForWhoEmailResult);

//Find the email address of the person who needs to approve this PO
$approvalSql = "SELECT employee_email
                FROM employee
                WHERE employee_ID = '$employee_ID';";
$approvalResult = mysqli_query($link, $approvalSql);
$approvalEmail = mysqli_fetch_array($approvalResult);

$headers = "From: ffridfinnsson@fraunhofer.org" . "\r\n" . "CC: ".$orderForWhoEmail[0];

$sql = "UPDATE purchase_order
        SET approval_status = 'pending'
        WHERE order_ID = '$order_ID';";
$result = mysqli_query($link, $sql);
mail($approvalEmail[0], "Order ".$orderForWhoRow[1]." needs your approval", "http://35.9.146.244:8888/Purchasing/Views/pendingApprovals.php", $headers);

?>
