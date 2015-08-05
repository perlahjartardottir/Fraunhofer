<?php
include '../../connection.php';
$order_ID = mysqli_real_escape_string($link, $_POST['order_ID']);
$sql = "UPDATE purchase_order
        SET order_receive_date = CURDATE()
        WHERE order_ID = '$order_ID';";
$result = mysqli_query($link, $sql);
?>
