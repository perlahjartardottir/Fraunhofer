<?php
include '../../connection.php';
session_start();
$order_ID = mysqli_real_escape_string($link, $_POST['order_ID']);
$approval_response = mysqli_real_escape_string($link, $_POST['approval_response']);
$sql = "UPDATE purchase_order
        SET approval_status = 'declined', approval_response = '$approval_response'
        WHERE order_ID = '$order_ID';";
$result = mysqli_query($link, $sql);
?>
