<?php
include '../../connection.php';
$notReceived = mysqli_real_escape_string($link, $_POST['notReceived']);
$order_name  = mysqli_real_escape_string($link, $_POST['order_name']);
$first_date  = mysqli_real_escape_string($link, $_POST['first_date']);
$last_date   = mysqli_real_escape_string($link, $_POST['last_date']);
$order_name .= '%';
?>
<div id='output'>
  <table class='table table-responsive'>
    <thead>
      <tr>
        <th>Purchase number</th>
        <th>Order date</th>
        <th>Receiving date</th>
        <th class='col-md-3'>Final inspection</th>
      </tr>
    </thead>
    <tbody>
      <?php
      $sql = "SELECT order_ID, order_date, order_receive_date, order_final_inspection
              FROM purchase_order
              WHERE order_ID LIKE '$order_name' ";
      if($notReceived == 'on'){
      	$sql .= "AND order_receive_date IS NULL ";
      }
      if(!empty($first_date)){
      	$sql .= "AND order_date >= '$first_date' ";
      }
      if(!empty($last_date)){
      	$sql .= "AND order_date <= '$last_date' ";
      }
      $result = mysqli_query($link, $sql);
      while($row = mysqli_fetch_array($result)){
        echo"
          <tr>
            <td><a href='#' data-toggle='modal' data-target='#".$row[0]."'>".$row[0]."</a></td>
            <td>".$row[1]."</td>
            <td>".$row[2]."</td>
            <td>".$row[3]."</td>
          </tr>";
      }
      ?>
    </tbody>
  </table>
  <?php
  $result = mysqli_query($link, $sql);
  while($row = mysqli_fetch_array($result)){
    $orderItemSql = "SELECT quantity, part_number, description, unit_price
                     FROM order_item
                     WHERE order_ID = '$row[0]';";
    $orderItemResult = mysqli_query($link, $orderItemSql);
    echo"
    <div class='modal fade' id='".$row[0]."' tabindex='-1' role='dialog' aria-labelledby='".$row[0]."' aria-hidden='true'>
      <div class='modal-dialog'>
        <div class='modal-content'>
          <div class='modal-header'>
            <h4>Purchase order: ".$row[0]."</h4>
          </div>
          <div class='modal-body'>
            <table class='table table-responsive'>
              <thead>
                <tr>
                  <th>Pos. #</th>
                  <th>Quantity</th>
                  <th>Part #</th>
                  <th>Description</th>
                  <th>USD Unit</th>
                  <th>USD Total</th>
                </tr>
              </thead>
              <tbody>";
                $counter = 1;
                $totalOrderPrice = 0;
                while($orderItemRow = mysqli_fetch_array($orderItemResult)){
                  $total = $orderItemRow[0] * $orderItemRow[3];
                  $totalOrderPrice = $totalOrderPrice + $total;
                  echo"
                    <tr>
                      <td>".$counter."</td>
                      <td>".$orderItemRow[0]."</td>
                      <td>".$orderItemRow[1]."</td>
                      <td>".$orderItemRow[2]."</td>
                      <td>$".number_format((float)$orderItemRow[3], 2, '.', '')."</td>
                      <td>$".number_format((float)$total, 2, '.', '')."</td>";
                      $counter = $counter + 1;
                }
              echo"
                <tr>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <th>Total Order Price:</th>
                  <th><u style='border-bottom: 1px solid black'>$".number_format((float)$totalOrderPrice, 2, '.', '')."</u></th>
                </tr>
              </tbody>
            </table>
            <span>Order date: ".$row[1]."</span>
              <button type='button' style='float:right;' class='btn btn-primary' onclick='packageReceived(".$row[0].")'>Package received</button>
          </div>
          <div class='modal-footer' style='margin-top:10px'>
            <button type='button' onclick='printoutInfo(".$row[0].")' class='btn btn-primary' style='float:left;'>Printout</button>
            <button type='button' onclick='POInfo(".$row[0].")' class='btn btn-primary' style='float:left; margin-left:5px'>Edit PO</button>
            <button type='button' class='btn' data-dismiss='modal'>Close</button>
          </div>
        </div>
      </div>
    </div>";
  }
  ?>
</div>
