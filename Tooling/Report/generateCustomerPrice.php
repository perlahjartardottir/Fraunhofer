<?php
include '../connection.php';
// this file generates the price displayed in the priceTables.php view
// when the user enters info in to the edit pricing fields
$customer = mysqli_real_escape_string($link, $_POST['customer_ID']);
$diameter = mysqli_real_escape_string($link, $_POST['diameter']);
$length   = mysqli_real_escape_string($link, $_POST['length']);

$sql = "SELECT amount
        FROM price
        WHERE customer_ID = '$customer'
        AND diameter = '$diameter'
        AND length = '$length';";

$result = mysqli_query($link, $sql);

$row = mysqli_fetch_array($result);

if(!$result){
  echo mysqli_error($link);
}
echo $row[0];

 ?>
