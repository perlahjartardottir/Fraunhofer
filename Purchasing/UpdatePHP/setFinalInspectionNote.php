<?php
include '../../connection.php';
$order_ID               = mysqli_real_escape_string($link, $_POST['order_ID']);
$order_final_inspection = mysqli_real_escape_string($link, $_POST['order_final_inspection']);
$rating_price           = mysqli_real_escape_string($link, $_POST['rating_price']);
$rating_quality         = mysqli_real_escape_string($link, $_POST['rating_quality']);
$rating_timeliness      = mysqli_real_escape_string($link, $_POST['rating_timeliness']);


$sql = "UPDATE purchase_order
        SET order_final_inspection = '$order_final_inspection'
        WHERE order_ID = '$order_ID';";
$result = mysqli_query($link, $sql);
if(!$result){
  die(mysqli_error($link));
}
$ratingSql = "INSERT INTO order_rating(order_ID, rating_timeliness, rating_price, rating_quality)
              VALUES ('$order_ID','$rating_timeliness', '$rating_price', '$rating_quality');";
$ratingResult = mysqli_query($link, $ratingSql);
if(!$ratingResult){
  die(mysqli_error($link));
}

?>
