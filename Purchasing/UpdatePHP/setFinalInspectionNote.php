<?php
include '../../connection.php';
$order_item_ID     = mysqli_real_escape_string($link, $_POST['order_item_ID']);
$order_ID          = mysqli_real_escape_string($link, $_POST['order_ID']);

$final_inspection  = mysqli_real_escape_string($link, $_POST['final_inspection']);
$ok                = mysqli_real_escape_string($link, $_POST['ok']);

if($ok == 'on'){
  $sql = "UPDATE order_item
          SET final_inspection = 'OK'
          WHERE order_item_ID = '$order_item_ID';";
}else{
  $sql = "UPDATE order_item
          SET final_inspection = '$final_inspection'
          WHERE order_item_ID = '$order_item_ID';";
}
$result = mysqli_query($link, $sql);
if(!$result){
  die(mysqli_error($link));
}
?>
