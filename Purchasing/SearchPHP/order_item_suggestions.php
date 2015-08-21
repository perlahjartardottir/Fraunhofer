<?php
include '../../connection.php';
$noFinalInspection = mysqli_real_escape_string($link, $_POST['noFinalInspection']);
$part_number  = mysqli_real_escape_string($link, $_POST['part_number']);
$description  = mysqli_real_escape_string($link, $_POST['description']);
$department  = mysqli_real_escape_string($link, $_POST['department']);
$first_date  = mysqli_real_escape_string($link, $_POST['first_date']);
$last_date   = mysqli_real_escape_string($link, $_POST['last_date']);

// Make both part number and description start with '%' so that it looks for every character
// Makes it easier to filter
$part_number .= '%';
$description .= '%';

// added '%' in front of description so that I can search for substring in middle of description
// and not just in the beginning
$description = '%' . $description;

$totalFinalPrice = 0; // A variable that shows the complete price of all the PO's

// Query to find the department ID
$departmentSql = "SELECT department_ID
                  FROM department
                  WHERE department_name = '$department';";
$departmentResult = mysqli_query($link, $departmentSql);
$row = mysqli_fetch_array($departmentResult);
$department_ID = $row[0];

?>
<div id='output'>
  <table class='table table-responsive table-striped'>
    <thead>
      <tr>
        <th>Part Number</th>
        <th>Description</th>
        <th>Quantity</th>
        <th>Unit Price</th>
        <th>Final Price</th>
      </tr>
    </thead>
    <tbody>
      <?php
      $sql = "SELECT oi.order_item_ID, oi.part_number, oi.description, oi.quantity, oi.unit_price, oi.department_ID
              FROM order_item oi
              WHERE oi.part_number LIKE '$part_number'
              AND oi.description LIKE '$description' ";
      if(!empty($department_ID)){
      	$sql .= "AND oi.department_ID = '$department_ID' ";
      }
      if(!empty($first_date)){
      	$sql .= "AND (SELECT po.order_date FROM purchase_order po WHERE po.order_ID = oi.order_ID) >= '$first_date' ";
      }
      if(!empty($last_date)){
      	$sql .= "AND (SELECT po.order_date FROM purchase_order po WHERE po.order_ID = oi.order_ID) <= '$last_date' ";
      }
      $sql .= "ORDER BY order_item_ID DESC;";
      $result = mysqli_query($link, $sql);
      while($row = mysqli_fetch_array($result)){
        $finalPrice = 0;
        // Query to find the final price of each order item
        $orderItemSql = "SELECT quantity, unit_price
                         FROM order_item
                         WHERE order_item_ID = '$row[0]';";
        $orderItemResult = mysqli_query($link, $orderItemSql);
        while($orderItemRow = mysqli_fetch_array($orderItemResult)){
          $finalPrice += $orderItemRow[0] * $orderItemRow[1];
          $totalFinalPrice += $finalPrice;
        }
        echo"
          <tr>
            <td>".$row[1]."</td>
            <td>".$row[2]."</td>
            <td>".$row[3]."</td>
            <td>$".number_format((float)$row[4], 2, '.', '')."</td>
            <td>$".number_format((float)$finalPrice, 2, '.', '')."</td>
          </tr>";
      }
      echo"
        <tr>
          <td></td>
          <td></td>
          <td></td>
          <th>Total price:</th>
          <th><u style='border-bottom: 1px solid black'>$".number_format((float)$totalFinalPrice, 2, '.', '')."</u></th>
        </tr>";
      ?>
    </tbody>
  </table>
