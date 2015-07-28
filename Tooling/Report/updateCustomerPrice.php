<?php
include '../connection.php';

$customer  = mysqli_real_escape_string($link, $_POST['customer_ID']);
$diameter  = mysqli_real_escape_string($link, $_POST['diameter']);
$length    = mysqli_real_escape_string($link, $_POST['length']);
$new_price = mysqli_real_escape_string($link, $_POST['new_price']);

// first we check if the customer has a price for this tool size already
// if they dont have one we insert a new price, else we update a old one

$checkSizeSql = "SELECT price_ID
                 FROM price
                 WHERE customer_ID = '$customer'
                 AND diameter = '$diameter'
                 AND length = '$length';";

$checkSizeResult = mysqli_query($link, $checkSizeSql);

if(mysqli_num_rows($checkSizeResult) > 0){
    $sql = "UPDATE price
            SET amount = '$new_price'
            WHERE customer_ID = '$customer'
            AND diameter = '$diameter'
            AND length = '$length'";

    $result = mysqli_query($link, $sql);

    if(!$result){
      echo mysqli_error($link);
    }
}else{
  $sql = "INSERT INTO price(customer_ID, diameter, length, amount)
          VALUES('$customer', '$diameter', '$length', '$new_price')";
  $result = mysqli_query($link, $sql);

  if(!$result){
    echo mysqli_error($link);
  }
}

 ?>
