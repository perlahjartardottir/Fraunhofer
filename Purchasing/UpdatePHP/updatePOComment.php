<?php
session_start();
include '../../connection.php';

$order_final_inspection = mysqli_real_escape_string($link, $_POST['order_final_inspection']);
$order_ID = $_SESSION['order_ID'];

$sql = "UPDATE purchase_order
        SET order_final_inspection = '$order_final_inspection'
        WHERE order_ID = '$order_ID';";
$result = mysqli_query($link, $sql);
?>
