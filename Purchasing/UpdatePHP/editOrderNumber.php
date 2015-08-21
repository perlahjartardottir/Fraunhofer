<?php
session_start();
include '../../connection.php';

$order_name = mysqli_real_escape_string($link, $_POST['order_name']);
$order_ID = $_SESSION['order_ID'];

$sql = "UPDATE purchase_order
        SET order_name = '$order_name'
        WHERE order_ID = '$order_ID';";
$result = mysqli_query($link, $sql);
?>
