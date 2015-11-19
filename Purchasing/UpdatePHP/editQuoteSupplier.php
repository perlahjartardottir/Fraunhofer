<?php

session_start();
include '../../connection.php';

$supplier_name = mysqli_real_escape_string($link, $_POST['supplier_name']);
$quote_ID = mysqli_real_escape_string($link, $_POST['quote_ID']);

// Get supplier ID
$supplierSql = "SELECT supplier_ID FROM supplier
                WHERE supplier_name = '$supplier_name'";
$supplierResult = mysqli_query($link, $supplierSql);
$supplierRow = mysqli_fetch_array($supplierResult);

if($supplier_name == ""){
  $sql = "UPDATE quote
          SET supplier_ID = NULL
          WHERE quote_ID = '$quote_ID';";
} else{
  $sql = "UPDATE quote
          SET supplier_ID = '$supplierRow[0]'
          WHERE quote_ID = '$quote_ID';";
}

$result = mysqli_query($link, $sql);
if(!$result){
  die("error: " + mysqli_error($link));
}
?>
