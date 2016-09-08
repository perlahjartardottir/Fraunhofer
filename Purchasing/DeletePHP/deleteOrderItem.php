<?php
include '../../connection.php';
session_start();
$order_item_ID = mysqli_real_escape_string($link, $_POST['order_item_ID']);
$order_ID = $_SESSION['order_ID'];
// delete the line item that has this ID
$sql = "DELETE FROM order_item
				WHERE order_item_ID ='$order_item_ID';";
$result = mysqli_query($link, $sql);
if(!$result){
	die("Could not delete order item: ".mysqli_error($link));
}

// If total order price is now under $1000 it does not need approval anymore.
$totalSql = "
SELECT SUM((quantity*unit_price))
FROM order_item
WHERE order_ID = '$order_ID';";
$total = mysqli_fetch_row(mysqli_query($link, $totalSql))[0];
if($total < 1000){

	// Get the current approved_by status
	$approvalStatusSql = "SELECT approval_status
	FROM purchase_order
	WHERE order_ID = '$order_ID';";
	$approvalStatus = mysqli_fetch_row(mysqli_query($link, $approvalStatusSql))[0];
	if($approvedBy == "pending"){
			$removePendingSql = "UPDATE purchase_order
        	SET approval_status = NULL
        	WHERE order_ID = '$order_ID';";
	$result = mysqli_query($link, $sql);
	}
}


mysqli_close($link);
?>
