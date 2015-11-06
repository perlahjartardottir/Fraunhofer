<?php
include '../../connection.php';
$order_ID          = mysqli_real_escape_string($link, $_POST['order_ID']);
$receiveDate       = mysqli_real_escape_string($link, $_POST['receiveDate']);
$rating_timeliness = mysqli_real_escape_string($link, $_POST['rating_timeliness']);
$rating_price      = mysqli_real_escape_string($link, $_POST['rating_price']);
$customer_service      = mysqli_real_escape_string($link, $_POST['customer_service']);
$rating_quality    = mysqli_real_escape_string($link, $_POST['rating_quality']);
$order_final_inspection    = mysqli_real_escape_string($link, $_POST['order_final_inspection']);

if(empty($receiveDate)){
  $sql = "UPDATE purchase_order
          SET order_receive_date = CURDATE(), order_final_inspection = '$order_final_inspection'
          WHERE order_ID = '$order_ID';";
} else{
  $sql = "UPDATE purchase_order
          SET order_receive_date = '$receiveDate', order_final_inspection = '$order_final_inspection'
          WHERE order_ID = '$order_ID';";
}
$result = mysqli_query($link, $sql);
if(!$result){
  die(mysqli_error($link));
}
$ratingSql = "INSERT INTO order_rating(order_ID, rating_timeliness, rating_price, rating_quality, customer_service)
              VALUES ('$order_ID','$rating_timeliness', '$rating_price', '$rating_quality', '$customer_service');";
$ratingResult = mysqli_query($link, $ratingSql);
if(!$ratingResult){
  die(mysqli_error($link));
}
?>
