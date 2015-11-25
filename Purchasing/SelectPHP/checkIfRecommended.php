<?php
include '../../connection.php';
session_start();

$supplier_name = mysqli_real_escape_string($link, $_POST['supplier_name']);

// Get the ID and the net terms from the supplier name
$supplierSql = "SELECT supplier_ID
                FROM supplier
                WHERE supplier_name = '$supplier_name';";
$supplierResult = mysqli_query($link, $supplierSql);

$row = mysqli_fetch_array($supplierResult);
$supplier_ID = $row[0];

$ratingSql = "SELECT ROUND((ROUND((AVG(rating_timeliness) + AVG(rating_price) + AVG(rating_quality) + AVG(customer_service)) / 4, 2) / 2) * 5, 2)
              FROM purchase_order o, order_rating r
              WHERE o.order_ID = r.order_ID
              AND o.supplier_ID = '$supplier_ID';";
$ratingResult = mysqli_query($link, $ratingSql);
if(!$ratingResult){
  echo mysqli_error($link);
}

$ratingRow = mysqli_fetch_array($ratingResult);
if($ratingRow[0] < 3){
  echo("Not Recommended");
} else{
  echo("Recommended");
}
?>
