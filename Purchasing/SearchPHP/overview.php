<?php

// This file generates the table for the overview of purchases for each department
include '../../connection.php';

$department    = mysqli_real_escape_string($link, $_POST['department']);
$cost_code_name = mysqli_real_escape_string($link, $_POST['cost_code']);
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

// Get cost code ID from the cost code name
$costCodeSql = "SELECT cost_code_ID
                  FROM cost_code
                  WHERE cost_code_name = '$cost_code_name';";
$costCodeResult = mysqli_query($link, $costCodeSql);
$costCodeRow = mysqli_fetch_array($costCodeResult);

$totalFinalPrice = 0; // A variable that shows the complete price of all the PO's
if($department == 'department'){
  $sql = "SELECT oi.department_ID, COUNT(oi.order_item_ID), SUM(oi.quantity * oi.unit_price)
          FROM purchase_order po, order_item oi
          WHERE oi.order_ID = po.order_ID ";
} else if($department == 'costCode'){
  $sql = "SELECT oi.cost_code_ID, COUNT(oi.order_item_ID), SUM(oi.quantity * oi.unit_price)
          FROM purchase_order po, order_item oi
          WHERE oi.order_ID = po.order_ID ";
} else if($departmentRow[0] == NULL){
  $sql = "SELECT DATE_FORMAT(po.order_date, '".$date_format."'), COUNT(oi.order_item_ID), SUM(oi.quantity * oi.unit_price)
          FROM purchase_order po, order_item oi
          WHERE oi.order_ID = po.order_ID ";
} else {
  $sql = "SELECT DATE_FORMAT(po.order_date, '".$date_format."'), COUNT(oi.order_item_ID), SUM(oi.quantity * oi.unit_price)
          FROM purchase_order po, order_item oi
          WHERE oi.department_ID = '$departmentRow[0]' ";
	if($department != $cost_code_name){
		$sql .= "AND oi.cost_code_ID = '$costCodeRow[0]' ";
	}
  $sql .= "AND oi.order_ID = po.order_ID ";
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
} else if($department == 'costCode'){
  $sql .= "GROUP BY oi.cost_code_ID;";
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
				<?php
				if($department == 'department'){
					echo"<th>Department</th>";
				}else{
					echo"<th>Date</th>";
				}
				?>
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

        // Find the cost code name for each cost code when we have grouped each row by
        // their cost code_ID, since we want to show their name and not just their ID
        $costCodeNameSql = "SELECT cost_code_name
                              FROM cost_code
                              WHERE cost_code_ID = '$row[0]';";
        $costCodeNameResult = mysqli_query($link, $costCodeNameSql);
        $costCodeNameRow = mysqli_fetch_array($costCodeNameResult);

        echo"<tr>";
        if($department == 'department'){
					if($departmentNameRow[0] == ""){
						echo"<td>N/A</td>";
					}else{
						echo"<td>".$departmentNameRow[0]."</td>";
					}
        } else if($department == 'costCode'){
					if($costCodeNameRow[0] == ""){
						echo"<td>N/A</td>";
					}else{
						echo"<td>".$costCodeNameRow[0]."</td>";
					}
        } else{
          echo"<td>".$row[0]."</td>";
        }
        echo"
              <td>".$row[1]."</td>
              <td>$".number_format((float)$row[2], 2, '.', '')."</td>
            </tr>";
      }
      ?>
    </tbody>
  </table>
</div>
