<?php
/*
	This file searches for POs that are older than 7 years old
*/
include '../connection.php';
session_start();

$sql = "SELECT po_ID, po_number, receiving_date, shipping_date, final_price
        FROM pos
        WHERE receiving_date < DATE_SUB(NOW(), INTERVAL 7 YEAR);";
$result = mysqli_query($link, $sql);
?>

<div id='output'>
  <table class='table table-responsive'>
    <thead>
      <tr>
        <th>PO number</th>
        <th>Receiving date</th>
        <th>Shipping date</th>
        <th>Final price</th>
      </tr>
    </thead>
    <tbody>
      <?php
      while($row = mysqli_fetch_array($result)){
        echo"
        <tr>
          <td>".$row[1]."</td>
          <td>".$row[2]."</td>
          <td>".$row[3]."</td>
          <td>".$row[4]."<button style='float: right; margin-right:-50px;' class='btn btn-danger' onclick='delOldPO(".$row[0].")'>X</button></td>
        </tr>";
      }?>
    </tbody>
  </table>
</div>
