<?php
include '../../connection.php';
session_start();
$order_ID    = $_SESSION['order_ID'];
$quantity    = mysqli_real_escape_string($link, $_POST['quantity']);
$part_number = mysqli_real_escape_string($link, $_POST['part_number']);
$unit_price  = mysqli_real_escape_string($link, $_POST['unit_price']);
$description = mysqli_real_escape_string($link, $_POST['description']);

$sql = "INSERT INTO order_item (order_ID, quantity, part_number, unit_price, description)
        VALUES ('$order_ID', '$quantity', '$part_number', '$unit_price', '$description');";
$result = mysqli_query($link, $sql);
 ?>
