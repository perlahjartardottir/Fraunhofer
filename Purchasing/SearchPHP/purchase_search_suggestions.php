<?php
include '../../connection.php';
$notReceived = mysqli_real_escape_string($link, $_POST['notReceived']);
$noFinalInspection = mysqli_real_escape_string($link, $_POST['noFinalInspection']);
$order_name  = mysqli_real_escape_string($link, $_POST['order_name']);
$supplier_name  = mysqli_real_escape_string($link, $_POST['supplier_name']);
$first_date  = mysqli_real_escape_string($link, $_POST['first_date']);
$last_date   = mysqli_real_escape_string($link, $_POST['last_date']);


$order_name .= '%';
$supplier_name .= '%';
$totalFinalPrice = 0; // A variable that shows the complete price of all the PO's

?>
<div id='output'>
  <table class='table table-responsive table-striped table-condensed'>
    <thead>
      <tr>
        <th>Purchase number</th>
        <th>Supplier</th>
        <th>Order date</th>
        <th>Receiving date</th>
        <th>Comment</th>
        <th>Final Price</th>
      </tr>
    </thead>
    <tbody>
      <?php
      $sql = "SELECT order_ID, order_date, order_receive_date, order_final_inspection, order_name, supplier_ID
              FROM purchase_order
              WHERE order_name LIKE '$order_name'
              AND supplier_ID = ANY(SELECT supplier_ID
                                    FROM supplier
                                    WHERE supplier_name LIKE '$supplier_name') ";
      if($notReceived == 'on'){
      	$sql .= "AND order_receive_date IS NULL ";
      }
      if($noFinalInspection == 'on'){
      	$sql .= "AND order_final_inspection IS NULL ";
      }
      if(!empty($first_date)){
      	$sql .= "AND order_date >= '$first_date' ";
      }
      if(!empty($last_date)){
      	$sql .= "AND order_date <= '$last_date' ";
      }
      $sql .= "ORDER BY order_ID DESC;";
      $result = mysqli_query($link, $sql);
      while($row = mysqli_fetch_array($result)){
        $supplierSql = "SELECT supplier_name
                        FROM supplier
                        WHERE supplier_ID = '$row[5]';";
        $supplierResult = mysqli_query($link, $supplierSql);
        $supplierRow = mysqli_fetch_array($supplierResult);
        $finalPrice = 0;
        // Query to find the final price of each purchase Order
        $orderItemSql = "SELECT quantity, unit_price
                         FROM order_item
                         WHERE order_ID = '$row[0]';";
        $orderItemResult = mysqli_query($link, $orderItemSql);
        while($orderItemRow = mysqli_fetch_array($orderItemResult)){
          $finalPrice += $orderItemRow[0] * $orderItemRow[1];
        }
        echo"
          <tr>
            <td><a href='#' onclick='setSessionIDSearch(".$row[0].")' data-toggle='modal' data-target='#".$row[0]."'>".$row[4]."</a></td>
            <td>".$supplierRow[0]."</td>
            <td>".$row[1]."</td>
            <td>".$row[2]."</td>
            <td>".$row[3]."</td>
            <td>$".number_format((float)$finalPrice, 2, '.', '')."</td>
          </tr>";
          $totalFinalPrice += $finalPrice;
      }
      echo"
        <tr>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <th>Total price:</th>
          <th><u style='border-bottom: 1px solid black'>$".number_format((float)$totalFinalPrice, 2, '.', '')."</u></th>
        </tr>";
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
            <span>Order date: ".$row[1]."</span>";
            if(empty($row[2])){
              echo"
                <button type='button' style='float:right;' class='btn btn-primary' onclick='packageReceived(".$row[0].", this)'>Package received</button>
                <input type='date' id='receiveDate' style='float:right; margin-right:5px;'>";
            }
            echo"
          </div>
          <div class='modal-footer' style='margin-top:10px'>
            <div class='btn-group' style='float:left;'>
                <button type='button' class='btn btn-primary dropdown-toggle' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
                  Edit <span class='caret'></span>
                </button>
                <ul class='dropdown-menu' role='menu'>
                  <li><a href='../Views/purchaseOrderReceived.php'>Edit received info</a></li>
                  <li><a href='../Views/addOrderItem.php'>Edit PO</a></li>
                </ul>
            </div>
            <button type='button' onclick='printoutInfo(".$row[0].")' class='btn btn-primary' style='float:left; margin-left:5px'>Printout</button>
            <a href='../Views/viewAllImages.php' class='btn btn-primary' style='float:left'>View Scan</a>
            <button type='button' style='float:right;' class='btn' data-dismiss='modal'>Close</button>
          </div>
        </div>
      </div>
    </div>";
  }
  ?>
</div>
