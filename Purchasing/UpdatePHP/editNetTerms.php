<?php
session_start();
include '../../connection.php';

$net_terms = mysqli_real_escape_string($link, $_POST['net_terms']);
$order_ID = $_SESSION['order_ID'];

$sql = "UPDATE purchase_order
        SET net_terms = '$net_terms'
        WHERE order_ID = '$order_ID';";

$result = mysqli_query($link, $sql);
?>
