<?php
include '../../connection.php';
$order_ID = mysqli_real_escape_string($link, $_POST['order_ID']);

// delete all line items that have this ID
$sql = "DELETE FROM order_item
				WHERE order_ID ='$order_ID';";
$result = mysqli_query($link, $sql);
if(!$result){
	die("Could not delete order item: ".mysqli_error($link));
}

// Delete the purchase order
$orderSql = "DELETE FROM purchase_order
				     WHERE order_ID ='$order_ID';";
$orderResult = mysqli_query($link, $orderSql);
if(!$orderResult){
	die("Could not delete order item: ".mysqli_error($link));
}

mysqli_close($link);
?>
