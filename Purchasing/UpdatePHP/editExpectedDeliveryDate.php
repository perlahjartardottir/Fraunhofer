<?php
session_start();
include '../../connection.php';

$expected_delivery_date = mysqli_real_escape_string($link, $_POST['expected_delivery_date']);
$order_ID = $_SESSION['order_ID'];

if($expected_delivery_date == ""){
  $sql = "UPDATE purchase_order
          SET expected_delivery_date = NULL
          WHERE order_ID = '$order_ID'";
}else{
  $sql = "UPDATE purchase_order
          SET expected_delivery_date = '$expected_delivery_date'
          WHERE order_ID = '$order_ID';";
}

$result = mysqli_query($link, $sql);
?>
