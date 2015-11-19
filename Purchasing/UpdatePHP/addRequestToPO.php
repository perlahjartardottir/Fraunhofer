<?php
include '../../connection.php';
session_start();

$user = $_SESSION["username"];
$order_ID = $_SESSION["order_ID"];
$request_ID = mysqli_real_escape_string($link, $_POST['request_ID']);

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
?>
