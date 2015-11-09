<?php
include '../../connection.php';
session_start();
$employee_ID = mysqli_real_escape_string($link, $_POST['employee_ID']);
$order_ID = $_SESSION['order_ID'];
$message = "";

// Find the ID of the employee who this order is for
$orderForWhoSql = "SELECT order_for_who, order_name
                   FROM purchase_order
                   WHERE order_ID = '$order_ID';";
$orderForWhoResult = mysqli_query($link, $orderForWhoSql);
$orderForWhoRow = mysqli_fetch_array($orderForWhoResult);

// Find order items that are linked to this PO
$orderItemSql = "SELECT quantity, part_number, description, unit_price
                 FROM order_item
                 WHERE order_ID = '$order_ID';";
$orderItemResult = mysqli_query($link, $orderItemSql);

$totalPrice = 0;
// Construct the email message
while($orderItemRow = mysqli_fetch_array($orderItemResult)){
  $totalPrice += $orderItemRow[0] * $orderItemRow[3];
  $message .= $orderItemRow[0]." ".$orderItemRow[1]." - $".$orderItemRow[3]." each\n".$orderItemRow[2]."\n\n";
}
$message .= "Total price: $".$totalPrice."\n\n http://35.9.146.244:8888/Purchasing/Views/pendingApprovals.php";

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

mail($approvalEmail[0], "Order ".$orderForWhoRow[1]." needs your approval", $message, $headers);

?>
