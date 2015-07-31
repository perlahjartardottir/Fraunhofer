<?php
include '../../connection.php';
session_start();
$order_ID = $_SESSION['order_ID'];
$sql = "SELECT quantity, part_number, unit_price, description, order_item_ID
        FROM order_item
        WHERE order_ID = '$order_ID';";
$result = mysqli_query($link, $sql);
if(mysqli_num_rows($result) == 0){
  die();
}
?>
<div class='row well well-lg'>
  <table class='table table-responsive' style='width:90%;'>
    <thead>
      <tr>
        <th>Pos. No.</th>
        <th>Quantity</th>
        <th>Part #</th>
        <th>Description</th>
        <th>USD Unit</th>
        <th>USD Total</th>
      </tr>
    </thead>
    <tbody>
      <?php
      $counter = 1;
      while($row = mysqli_fetch_array($result)){
        $total = $row[0] * $row[2];
        echo"<tr>
              <td>".$counter."</td>
              <td>".$row[0]."</td>
              <td>".$row[1]."</td>
              <td>".$row[3]."</td>
              <td>".$row[2]."</td>
              <td>".$total."<button style='float:right; margin-right:-50px' onclick='delOrderItem(".$row[4].")' class='btn btn-danger'><span class='glyphicon glyphicon-remove' aria-hidden='true'></span></button></td>
            </tr>";
        $counter = $counter + 1;
      } ?>
    </tbody>
  </table>
</div>
