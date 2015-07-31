<?php
include '../../connection.php';
session_start();
$order_ID = mysqli_real_escape_string($link, $_POST['order_ID']);
$_SESSION['order_ID'] = $order_ID;
?>
