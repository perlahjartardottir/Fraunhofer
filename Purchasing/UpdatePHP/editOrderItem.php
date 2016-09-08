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
                  WHERE department_name LIKE '$department';";
$departmentResult = mysqli_query($link, $departmentSql);
$departmentID = mysqli_fetch_row($departmentResult)[0];

// Find cost code ID
$cost_codeSql = "SELECT cost_code_ID
                  FROM cost_code
                  WHERE cost_code_name LIKE '$cost_code';";
$costCodeID = mysqli_fetch_row(mysqli_query($link, $cost_codeSql))[0];

 $sql = "UPDATE order_item
        SET quantity = '$quantity', part_number = '$part_number', unit_price = '$unit_price', description = '$description', department_ID = '$departmentID', cost_code_ID = '$costCodeID'
        WHERE order_item_ID = '$order_item_ID';";       

$result = mysqli_query($link, $sql);
if (!$result) {
    $message  = 'Invalid result query: ' . mysqli_error($link);
    die($message);
}
?>
