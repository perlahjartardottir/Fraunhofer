<?php
include '../../connection.php';
session_start();
$user = $_SESSION["username"];
$order_ID = $_SESSION["order_ID"];
$request_ID = mysqli_real_escape_string($link, $_POST['request_ID']);

$quantity    = mysqli_real_escape_string($link, $_POST['quantity']);
$part_number = mysqli_real_escape_string($link, $_POST['part_number']);
$unit_price  = mysqli_real_escape_string($link, $_POST['unit_price']);
$description = mysqli_real_escape_string($link, $_POST['description']);
$department  = mysqli_real_escape_string($link, $_POST['department']);
$cost_code   = mysqli_real_escape_string($link, $_POST['cost_code']);


// Make the current request inactive
$sql = "UPDATE order_request
        SET active = 0
        WHERE request_ID IN (SELECT request_ID FROM purchase_order
                            WHERE order_ID = '$order_ID');";
$result = mysqli_query($link, $sql);
if(!$result){
  die("error " . mysqli_error($link));
}

//Put this request as the new main request for this PO
$newMainRequestSql = "UPDATE purchase_order
                      SET request_ID = '$request_ID'
                      WHERE order_ID = '$order_ID';";
$newMainRequestResult = mysqli_query($link, $newMainRequestSql);
if(!$newMainRequestResult){
  die("error " . mysqli_error($link));
}

// We also have to link this PO to this request the other way around
// Since PO can have multiple requests as requests can have multiple POs
$linkRequestToPoSql = "UPDATE order_request
                       SET order_ID = '$order_ID'
                       WHERE request_ID = '$request_ID';";
$linkRequestToPoResult = mysqli_query($link, $linkRequestToPoSql);
if(!$linkRequestToPoResult){
  die("error " . mysqli_error($link));
}

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
