<?php
include '../../connection.php';
session_start();
$order_ID    = $_SESSION['order_ID'];
$quantity    = mysqli_real_escape_string($link, $_POST['quantity']);
$part_number = mysqli_real_escape_string($link, $_POST['part_number']);
$unit_price  = mysqli_real_escape_string($link, $_POST['unit_price']);
$description = mysqli_real_escape_string($link, $_POST['description']);
$department  = mysqli_real_escape_string($link, $_POST['department']);
$cost_code   = mysqli_real_escape_string($link, $_POST['cost_code']);

$departmentSql = "SELECT department_ID
                  FROM department
                  WHERE department_name = '$department';";
$departmentResult = mysqli_query($link, $departmentSql);
$row = mysqli_fetch_array($departmentResult);
$department_ID = $row[0];

$costCodeSql = "SELECT cost_code_ID
                  FROM cost_code
                  WHERE cost_code_name = '$cost_code';";
$costCodeResult = mysqli_query($link, $costCodeSql);
$costCodeRow = mysqli_fetch_array($costCodeResult);
$cost_code_ID = $costCodeRow[0];

if ($department_ID == "" && $cost_code == ""){
  $sql = "INSERT INTO order_item (order_ID, quantity, part_number, unit_price, description)
          VALUES ('$order_ID', '$quantity', '$part_number', '$unit_price', '$description');";
} else if ($cost_code == ""){
  $sql = "INSERT INTO order_item (order_ID, quantity, part_number, unit_price, description, department_ID)
          VALUES ('$order_ID', '$quantity', '$part_number', '$unit_price', '$description', '$department_ID');";
} else if ($department_ID == ""){
  $sql = "INSERT INTO order_item (order_ID, quantity, part_number, unit_price, description, cost_code_ID)
          VALUES ('$order_ID', '$quantity', '$part_number', '$unit_price', '$description', '$cost_code_ID');";
} else {
  $sql = "INSERT INTO order_item (order_ID, quantity, part_number, unit_price, description, department_ID, cost_code_ID)
          VALUES ('$order_ID', '$quantity', '$part_number', '$unit_price', '$description', '$department_ID', '$cost_code_ID');";
}

$result = mysqli_query($link, $sql);
if(!$result){
  $message = 'Invalid query: ' . mysqli_error($link);
  die($message);
}
 ?>
