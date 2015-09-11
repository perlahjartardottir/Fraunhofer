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

$sql = "SELECT quote_ID, image
        FROM quote
        WHERE create_request = 1;";
$result = mysqli_query($link, $sql);

$departmentSql = "SELECT department_name
                  FROM department;";
$departmentResult = mysqli_query($link, $departmentSql);
?>
<head>
  <title>Fraunhofer CCD</title>
</head>
<body>
  <?php include '../header.php'; ?>
  <?php echo "<input type='hidden' id='employee_ID' value='".$employee_ID."'>"; ?>
  <div class='container'>
    <div id='invalidRequest'></div>
    <div class='row well well-lg'>
      <form>
        <h5>*Only "Description" field is required. More information makes it easier to process the order.</h5>
        <h5>*Currently the page reloads when you add quote, so please add quote <strong><u>before</u></strong> you enter other information.</h5>
        <h4>Make a request for a purchase order</h4>
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
      <div class='col-md-12'>
        <h4>Add Quotes</h4>
        <form action="../InsertPHP/addQuote.php" method="post" enctype="multipart/form-data" onsubmit="return checkSize(1000000)">
          <div class='col-md-3'>
            <label>Quote number: </label>
            <input type='text' class='form-control' name='quote_number' id='quote_number' name='quote_number'>
          </div>
          <div class='col-md-3'>
            <label>Description: </label>
            <input type='text' class='form-control' name='description' id='description' name='description'>
          </div>
          <div class='col-md-3'>
            <label>Select image to upload:</label>
            <!-- hidden type which is used to redirect to the correct view -->
            <input type='hidden' value='requestQuote' id='redirect' name='redirect'>
            <input type="file" name="fileToUpload" id="fileToUpload" accept="image/jpeg/pdf">
          </div>
          <div class='col-md-3'>
            <input type="submit" class='btn btn-primary col-md-12' value="Add quote" name="submit" style='margin-top:25px;'>
          </div>
        </form>
        <div class='col-md-12'>
          <?php
          while($row = mysqli_fetch_array($result)){
            echo"<div class='col-md-3'>
                  <input type='image' src='../Scan/getRequestQuoteImage.php?id=".$row[0]."' style='margin-top:5px;' width='100' height='90' onerror=\"this.src='../images/noimage.jpg'\" onclick=\"window.open('../Printouts/quoteRequestPrintout.php?id=".$row[0]."')\">
                  <button class='btn btn-danger' style='margin-top:5px; margin-right:20px' onclick='deleteQuote(".$row[0].")'>Delete</button>
                </div>";
          }
           ?>
        </div>
      </div>
      <input class='form-control btn btn-primary' type="button" value="Request" onclick='orderRequest()' style='margin-top:25px;'>
    </div>
  </div>
  <script>
  $(document).ready(function() {
      updateCostCode();
  });
  </script>
</body>
