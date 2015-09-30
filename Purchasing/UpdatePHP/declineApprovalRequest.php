<?php
include '../../connection.php';
session_start();
$order_ID = mysqli_real_escape_string($link, $_POST['order_ID']);
$sql = "UPDATE purchase_order
        SET approval_status = 'declined'
        WHERE order_ID = '$order_ID';";
$result = mysqli_query($link, $sql);
?>
