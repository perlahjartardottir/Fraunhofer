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

// Get the department ID from their name
$departmentSql = "SELECT department_ID
                  FROM department
                  WHERE department_name = '$department';";
$departmentResult = mysqli_query($link, $departmentSql);
$row = mysqli_fetch_array($departmentResult);
$department_ID = $row[0];

// Get the cost code ID from their name
$costCodeSql = "SELECT cost_code_ID
                  FROM cost_code
                  WHERE cost_code_name = '$cost_code';";
$costCodeResult = mysqli_query($link, $costCodeSql);
$costCodeRow = mysqli_fetch_array($costCodeResult);
$cost_code_ID = $costCodeRow[0];

// Insert department and cost code to the order item only if they are not empty
// Since we would have to add NULL in their but if we add a variable this way the outcome
// would be 'NULL' (the string) and that is not what we want
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
