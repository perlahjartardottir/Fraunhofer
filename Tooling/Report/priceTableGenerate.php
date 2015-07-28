<?php

/*
    This file generates the price table for the selected customer
*/
include '../connection.php';
session_start();

$customer = mysqli_real_escape_string($link, $_POST['customer_ID']);

$sql = "SELECT customer_ID, customer_name
        FROM customer
        WHERE customer_ID = '$customer';";
$result = mysqli_query($link, $sql);
while($row = mysqli_fetch_array($result)){
  $customer_name = $row[1];
}

$priceSql = "SELECT price_ID, customer_ID, diameter, length, amount
             FROM price
             WHERE customer_ID = '$customer';";
$priceResult = mysqli_query($link, $priceSql);
?>
<div id='priceTable'>
  <h4><?php echo $customer_name;?></h4>
  <table class='table table-responsive table-striped'>
    <thead>
      <tr>
        <th>Diameter</th>
        <th>Length</th>
        <th>Price</th>
        <th>Dlc price</th>
      </tr>
    </thead>
    <tbody>
    <?php
    while($priceRow = mysqli_fetch_array($priceResult)){
      $dlc = $priceRow[4] * 2;
      echo"
          <tr>
            <td>".$priceRow[2]."</td>
            <td>".$priceRow[3]."</td>
            <td class='table_price'>".round($priceRow[4], 2)."</td>
            <td class='table_price'>".round($dlc, 2)."</td>
          </tr>";
    }
     ?>
   </tbody>
  </table>
</div>
