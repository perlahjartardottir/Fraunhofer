<?php
include '../../connection.php';
session_start();

//find the current user
$user = $_SESSION["username"];
$request_ID = mysqli_real_escape_string($link, $_POST['request_ID']);
$employee_name = mysqli_real_escape_string($link, $_POST['employee_name']);

$sql = "SELECT request_ID, employee_ID, approved_by_employee, request_date, request_description, active, request_supplier, department, timeframe, part_number, quantity, cost_code
        FROM order_request
        WHERE request_ID = '$request_ID';";
$result = mysqli_query($link, $sql);
$row = mysqli_fetch_array($result);
echo"
<div id='output'>
  <div class='row well well-lg col-md-5 col-md-offset-1'>
    <form>
      <h4> Request ID: <span id='activeRequest' value='".$row[0]."'>".$row[0]."</span></h4>
      <p> Employee: ".$employee_name."</p>
      <p> Date: ".$row[3]."</p>
      <p> Order timeframe: ".$row[8]."</p>
      <p> Part number: ".$row[9]."</p>
      <p> Quantity: ".$row[10]."</p>
      <p> Supplier: ".$row[6]."</p>
      <p> Department: ".$row[7]."</p>
      <p> Cost code: ".$row[11]."</p>
      <p> Approved by: ".$row[2]."</p>
      <p> Description: ".$row[4]."</p>
    </form>
  </div>
</div>"
?>
