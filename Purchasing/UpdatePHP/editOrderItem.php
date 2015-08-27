<?php
session_start();
include '../../connection.php';

$quantity = mysqli_real_escape_string($link, $_POST['quantity']);
$order_item_ID = mysqli_real_escape_string($link, $_POST['order_item_ID']);
$part_number = mysqli_real_escape_string($link, $_POST['part_number']);
$department = mysqli_real_escape_string($link, $_POST['department']);
$unit_price = mysqli_real_escape_string($link, $_POST['unit_price']);
$description = mysqli_real_escape_string($link, $_POST['description']);
$order_ID = $_SESSION['order_ID'];

//Find department ID
$departmentSql = "SELECT department_ID
                  FROM department
                  WHERE department_name = '$department';";
$departmentResult = mysqli_query($link, $departmentSql);
$departmentRow = mysqli_fetch_array($departmentResult);

$sql = "UPDATE order_item
        SET quantity = '$quantity', part_number = '$part_number', department_ID = '$departmentRow[0]', unit_price = '$unit_price', description = '$description'
        WHERE order_item_ID = '$order_item_ID';";
$result = mysqli_query($link, $sql);
?>
