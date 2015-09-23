<?php
include '../../connection.php';
session_start();
//find the current user
$user = $_SESSION["username"];
//find his level of security
$secsql = "SELECT security_level, employee_ID
           FROM employee
           WHERE employee_name = '$user'";
$secResult = mysqli_query($link, $secsql);

while($row = mysqli_fetch_array($secResult)){
  $user_sec_lvl = $row[0];
  $employee_ID = $row[1];
}
// if the user security level is not high enough we kill the page and give him a link to the log in page
if($user_sec_lvl < 2){
  echo "<a href='../../Login/login.php'>Login Page</a></br>";
  die("You don't have the privileges to view this site.");
}

$sql = "SELECT quote_ID, image, quote_number, supplier_ID, quote_date
        FROM quote
        WHERE create_request = 1;";
$result = mysqli_query($link, $sql);

$departmentSql = "SELECT department_name
                  FROM department;";
$departmentResult = mysqli_query($link, $departmentSql);

// Query for supplier list
$supplierSql = "SELECT supplier_name
                FROM supplier;";
$supplierResult = mysqli_query($link, $supplierSql);
?>
<head>
  <title>Fraunhofer CCD</title>
</head>
<body>
  <?php include '../header.php'; ?>
  <?php echo "<input type='hidden' id='employee_ID' value='".$employee_ID."'>"; ?>
  <div class='container'>
    <div id='invalidRequest'></div>
    <?php if(mysqli_num_rows($result) > 0){ ?>
    <div class='row well'>
      <h3><center>Quotes</center></h3>
      <div class='col-md-12'>
        <?php
        while($row = mysqli_fetch_array($result)){
          $supplierNameSql = "SELECT supplier_name
                              FROM supplier
                              WHERE supplier_ID = '$row[3]';";
          $supplierNameResult = mysqli_query($link, $supplierNameSql);
          $supplierNameRow = mysqli_fetch_array($supplierNameResult);
          echo"<div class='col-md-4'>
                  <div class='col-md-5'>
                  <p><input type='image' src='../Scan/getQuoteImage.php?id=".$row[0]."' width='100' height='100' onerror=\"this.src='../images/noimage.jpg'\" onclick=\"window.open('../Printouts/quotePrintout.php?id=".$row[0]."')\"/></p>
                  <button class='btn btn-danger' style='margin-top:5px; margin-right:200px' onclick='removeQuoteFromRequest(".$row[0].")'>Deactivate</button>
                </div>
                <div class='col-md-7'>
                  <p><strong>Quote number: </strong><a href='../SelectPHP/download.php?id=".$row[0]."'>".$row[2]."</a><br></p>
                  <p><strong>Supplier: </strong>".$supplierNameRow[0]."</p>
                  <p><strong>Date issued: </strong>".$row[4]."</p>
                </div>
               </div>";
        }
         ?>
      </div>
    </div>
    <?php } ?>
    <div class='row well well-lg'>
      <h5>*Only "Description" field is required. More information makes it easier to process the order.</h5>

      <form>
        <h3>Make a request for a purchase order</h3>
        <div class='col-md-4 form-group'>
          <label>Supplier: </label>
          <input type="text" id='request_supplier' class='form-control'>
        </div>
        <div class='col-md-4 form-group'>
          <label>Approved by: </label>
          <input type="text" id='approved_by_employee' class='form-control'>
        </div>
        <div class='col-md-4 form-group'>
          <label>Part #: </label>
          <input type="text" id='part_number' class='form-control'>
        </div>
        <div class='col-md-4 form-group'>
          <label>Department: </label>
          <select id='department' class='form-control' onchange='updateCostCode()'>
            <option value=''>All departments</option>
            <?php
            while($departmentRow = mysqli_fetch_array($departmentResult)){
              echo "<option value='".$departmentRow[0]."'>".$departmentRow[0]."</option>";
            }?>
          </select>
        </div>
        <div class='form-group col-md-4 result'>
        </div>
        <div class='col-md-4 form-group'>
          <label>Order timeframe: </label>
          <select id='orderTimeframe' class='form-control'>
            <option value='With next order'>With next order</option>
            <option value='This week'>This week</option>
            <option value='Today'>Today</option>
            <option value='Other'>Other</option>
          </select>
        </div>
        <div class='col-md-4 form-group'>
          <label>Quantity: </label>
          <input type="number" id='quantity' class='form-control'>
        </div>
        <div class='col-md-4 form-group'>
          <label>Description: </label>
          <textarea id='request_description' class='form-control' rows='4'></textarea>
        </div>
      </form>
      <input class='form-control btn btn-primary' type="button" value="Request" onclick='orderRequest()' style='margin-top:25px;'>
    </div>
  </div>
  <script>
  $(document).ready(function() {
      updateCostCode();
  });
  </script>
</body>
