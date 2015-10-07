<?php
include '../../connection.php';
$order_item_ID = mysqli_real_escape_string($link, $_POST['order_item_ID']);

// delete the line item that has this ID
$sql = "DELETE FROM order_item
				WHERE order_item_ID ='$order_item_ID';";
$result = mysqli_query($link, $sql);
if(!$result){
	die("Could not delete order item: ".mysqli_error($link));
}
mysqli_close($link);
?>
