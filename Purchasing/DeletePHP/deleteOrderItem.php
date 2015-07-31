<?php
include '../../connection.php';

$order_item_ID = mysqli_real_escape_string($link, $_POST['order_item_ID']);

$sql = "DELETE FROM order_item
				WHERE order_item_ID ='$order_item_ID';";
$Result = mysqli_query($link, $sql);

mysqli_close($link);
?>
