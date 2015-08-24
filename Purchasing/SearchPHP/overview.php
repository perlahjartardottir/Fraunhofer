<?php

// This file generates the table for the overview of purchases for each department
include '../../connection.php';

$department    = mysqli_real_escape_string($link, $_POST['department']);
$date_type     = mysqli_real_escape_string($link, $_POST['timeInterval']);
$date_to       = mysqli_real_escape_string($link, $_POST['date_to']);
$date_from     = mysqli_real_escape_string($link, $_POST['date_from']);


// Getting the correct date type
$date_format = "";
if($date_type == "Year"){
	$date_format = "%Y";
}
if($date_type == "Month"){
	$date_format = "%Y/%m";
}
if($date_type == "Week"){
	$date_format = "%Y/%m/%u";
}

// Get department ID from the department name
$departmentSql = "SELECT department_ID
                  FROM department
                  WHERE department_name = '$department';";
$departmentResult = mysqli_query($link, $departmentSql);
$departmentRow = mysqli_fetch_array($departmentResult);

$totalFinalPrice = 0; // A variable that shows the complete price of all the PO's
if($department == 'department'){
  $sql = "SELECT oi.department_ID, COUNT(oi.order_item_ID), SUM(oi.quantity * oi.unit_price)
          FROM purchase_order po, order_item oi
          WHERE oi.order_ID = po.order_ID ";
} else if($departmentRow[0] == NULL){
  $sql = "SELECT DATE_FORMAT(po.order_date, '".$date_format."'), COUNT(oi.order_item_ID), SUM(oi.quantity * oi.unit_price)
          FROM purchase_order po, order_item oi
          WHERE oi.order_ID = po.order_ID ";
} else {
  $sql = "SELECT DATE_FORMAT(po.order_date, '".$date_format."'), COUNT(oi.order_item_ID), SUM(oi.quantity * oi.unit_price)
          FROM purchase_order po, order_item oi
          WHERE oi.department_ID = '$departmentRow[0]'
          AND oi.order_ID = po.order_ID ";
}
if(!empty($date_from)){
  $sql .= "AND po.order_date >= '$date_from' ";
}
if(!empty($date_to)){
  $sql .= "AND po.order_date <= '$date_to' ";
}

// If "all departments" is selected then we want to group by each department
// Otherwise we group by the date (year, month or week)
if($department == 'department'){
  $sql .= "GROUP BY oi.department_ID;";
} else{
  $sql .= "GROUP BY YEAR(po.order_date), ".$date_type." (po.order_date) DESC;";
}

$result = mysqli_query($link, $sql);
if(!$result){
  die(mysqli_error($link));
}

?>

<div class='row well'>
  <table class='table table-responsive'>
    <thead>
      <tr>
        <th>Date</th>
        <th># of orders</th>
        <th>Total cost</th>
      </tr>
    </thead>
    <tbody>
      <?php
      while($row = mysqli_fetch_array($result)){

        // Find the department name for each department when we have grouped each row by
        // their department_ID, since we want to show their name and not just their ID
        $departmentNameSql = "SELECT department_name
                              FROM department
                              WHERE department_ID = '$row[0]';";
        $departmentNameResult = mysqli_query($link, $departmentNameSql);
        $departmentNameRow = mysqli_fetch_array($departmentNameResult);

        echo"<tr>";
        if($department == 'department'){
          echo"<td>".$departmentNameRow[0]."</td>";
        } else{
          echo"<td>".$row[0]."</td>";
        }
        echo"
              <td>".$row[1]."</td>
              <td>$".$row[2]."</td>
            </tr>";
      }
      ?>
    </tbody>
  </table>
</div>
