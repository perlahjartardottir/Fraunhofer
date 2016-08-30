<?php
include '../../connection.php';
$order_ID = mysqli_real_escape_string($link, $_POST['order_ID']);

// Set the price for all the items to 0. 
$setPriceSql = "UPDATE order_item SET unit_price = 0 WHERE order_ID = '$order_ID';";
$setPriceResult = mysqli_query($link, $setPriceSql);

$inspectionNote = "Order canceled on ".date("Y-m-d").".";
$changeOrderInfoSql = "UPDATE purchase_order SET order_name = 'CANCELED', order_receive_date = CURDATE(), order_final_inspection = '$inspectionNote'
						WHERE order_ID = '$order_ID';";
$changeOrderInfoResult = mysqli_query($link, $changeOrderInfoSql);
if(!$changeOrderInfoResult){
	echo "Could not cancel request: ".mysqli_error($link);
}

mysqli_close($link);
?>
