<?php

include '../connection.php';
$customer         = mysqli_real_escape_string($link, $_POST['customer_ID']);
$price_multiplier = mysqli_real_escape_string($link, $_POST['price_multiplier']);

$sql = "UPDATE price
        SET amount = amount * '$price_multiplier'
        WHERE customer_ID = '$customer';";

$result = mysqli_query($link, $sql);

if(!$result){
  echo mysqli_error($link);
}
?>
