<?php
session_start();
include '../../connection.php';

$quantity = mysqli_real_escape_string($link, $_POST['quantity']);
$order_item_ID = mysqli_real_escape_string($link, $_POST['order_item_ID']);
$part_number = mysqli_real_escape_string($link, $_POST['part_number']);
$department = mysqli_real_escape_string($link, $_POST['department']);
$unit_price = mysqli_real_escape_string($link, $_POST['unit_price']);
$description = mysqli_real_escape_string($link, $_POST['description']);
$cost_code = mysqli_real_escape_string($link, $_POST['cost_code']);
$order_ID = $_SESSION['order_ID'];

//Find department ID
$departmentSql = "SELECT department_ID
                  FROM department
                  WHERE department_name = '$department';";
$departmentResult = mysqli_query($link, $departmentSql);
if(!$departmentResult){
  die("department not found. " . mysqli_error($link));
}
$departmentRow = mysqli_fetch_array($departmentResult);

// Find cost code ID
$cost_codeSql = "SELECT cost_code_ID
                  FROM cost_code
                  WHERE cost_code_name = '$cost_code';";
$cost_codeResult = mysqli_query($link, $cost_codeSql);
if(!$cost_codeResult){
  die("cost_code not found. " . mysqli_error($link));
}
$cost_codeRow = mysqli_fetch_array($cost_codeResult);

$sql = "UPDATE order_item
        SET quantity = '$quantity', part_number = '$part_number', unit_price = '$unit_price', description = '$description', department_ID = '$departmentRow[0]', cost_code_ID = '$cost_codeRow[0]'
        WHERE order_item_ID = '$order_item_ID';";
$result = mysqli_query($link, $sql);
if (!$result) {
    $message  = 'Invalid result query: ' . mysqli_error($link);
    die($message);
}
?>
