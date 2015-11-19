<?php
include '../../connection.php';

$order_ID      = mysqli_real_escape_string($link, $_POST['order_ID']);
$comment  = mysqli_real_escape_string($link, $_POST['comment']);

$sql = "UPDATE purchase_order
        SET comment = '$comment'
        WHERE order_ID = '$order_ID';";
$result = mysqli_query($link, $sql);
if(!$result){
  die(mysqli_error($link));
}
?>
