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
// Find all suppliers
$supplierSql = "SELECT supplier_name
                FROM supplier;";
$supplierResult = mysqli_query($link, $supplierSql);

// Find all quotes recently created
$sql = "SELECT quote_ID, image
        FROM quote
        WHERE create_request = 1;";
$result = mysqli_query($link, $sql);
?>
<head>
  <title>Fraunhofer CCD</title>
</head>
<body>
  <?php include '../header.php'; ?>
  <div class='container'>
    <div class='row well'>
      <div class='col-md-12'>
        <h3><center>Add Quotes</center></h3>
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
            <label>Supplier: </label>
              <input type='text' list="suppliers" name="supplierList" id='supplierList' value='' class='col-md-12 form-control'>
              <datalist id="suppliers">
                <?
                while($row = mysqli_fetch_array($supplierResult)){
                  echo"<option value='".$row[0]."'></option>";
                }
                ?>
              </datalist>
          </div>
          <div class='col-md-3'>
            <label>Select image to upload:</label>
            <!-- hidden type which is used to redirect to the correct view -->
            <input type='hidden' value='addQuote' id='redirect' name='redirect'>
            <input type="file" name="fileToUpload" id="fileToUpload" accept="image/jpeg/pdf">
          </div>
          <div class='col-md-12'>
            <input type="submit" class='btn btn-primary col-md-6 col-md-offset-3' value="Add quote" name="submit" style='margin-top:25px;'>
          </div>
        </form>
        <div class='col-md-12'>
          <?php
          while($row = mysqli_fetch_array($result)){
            echo"<div class='col-md-3'>
                  <input type='image' src='../Scan/getRequestQuoteImage.php?id=".$row[0]."' style='margin-top:5px;' width='100' height='90' onerror=\"this.src='../images/noimage.jpg'\" onclick=\"window.open('../Printouts/quoteRequestPrintout.php?id=".$row[0]."')\">
                  <button class='btn btn-danger' style='margin-top:5px; margin-right:200px' onclick='removeQuoteFromRequest(".$row[0].")'>Deactivate</button>
                </div>";
          }
           ?>
        </div>
      </div>
    </div>
  </div>
</body>
