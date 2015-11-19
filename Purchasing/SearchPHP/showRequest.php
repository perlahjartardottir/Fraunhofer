<?php
include '../../connection.php';
session_start();

//find the current user
$user = $_SESSION["username"];
$request_ID = mysqli_real_escape_string($link, $_POST['request_ID']);
$employee_name = mysqli_real_escape_string($link, $_POST['employee_name']);

$sql = "SELECT request_ID, employee_ID, approved_by_employee, request_date, request_description, active, request_supplier, department, timeframe, part_number, quantity, cost_code, request_price
        FROM order_request
        WHERE request_ID = '$request_ID';";
$result = mysqli_query($link, $sql);
$row = mysqli_fetch_array($result);
$quoteSql = "SELECT quote_ID, image, quote_number, supplier_ID, quote_date
             FROM quote
             WHERE request_ID = '$request_ID';";
$quoteResult = mysqli_query($link, $quoteSql);
echo"
<script type='text/javascript'>
  var supplier = document.getElementById('supplierList');
  supplier.value = '".$row[6]."';
  var employee = document.getElementById('employeeList');
  employee.value = '".$employee_name."';
</script>
<div id='output'>
  <div class='row well well-lg col-md-5 col-md-offset-1'>
    <form>
      <h4> Request ID: <span id='activeRequest' value='".$row[0]."'>".$row[0]."</span></h4>
      <p> Employee: ".$employee_name."</p>
      <p> Date: ".$row[3]."</p>
      <p> Order timeframe: ".$row[8]."</p>
      <p> Part number: ".$row[9]."</p>
      <p> Quantity: ".$row[10]."</p>
      <p> Total price: $".$row[12]."</p>
      <p> Supplier: ".$row[6]."</p>
      <p> Department: ".$row[7]."</p>
      <p> Cost code: ".$row[11]."</p>
      <p> Description: ".$row[4]."</p>
    </form>";
    while($quoteRow = mysqli_fetch_array($quoteResult)){
      $supplierNameSql = "SELECT supplier_name
                          FROM supplier
                          WHERE supplier_ID = '$quoteRow[3]';";
      $supplierNameResult = mysqli_query($link, $supplierNameSql);
      $supplierNameRow = mysqli_fetch_array($supplierNameResult);
      echo"<div class='col-md-3'>
            <p><input type='image' src='../Scan/getQuoteImage.php?id=".$quoteRow[0]."' width='100' height='100' onerror=\"this.src='../images/noimage.jpg'\" onclick=\"window.open('../Printouts/quotePrintout.php?id=".$quoteRow[0]."')\"/></p>
            <button class='btn btn-danger' style='margin-top:5px; margin-right:20px' onclick='deleteQuote(".$quoteRow[0].")'>Delete</button>
          </div>
          <div class='col-md-3'>
            <p><strong>Quote number: </strong><a href='../SelectPHP/download.php?id=".$quoteRow[0]."'>".$quoteRow[2]."</a><br></p>
            <p><strong>Supplier: </strong>".$supplierNameRow[0]."</p>
            <p><strong>Date issued: </strong>".$quoteRow[4]."</p>
          </div>";
    }
  echo"
  </div>
</div>";
?>
