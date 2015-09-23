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
$sql = "SELECT quote_ID, image, quote_number, supplier_ID, quote_date
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
        <form action="../InsertPHP/addQuote.php" method="post" enctype="multipart/form-data">
          <div class='col-md-3'>
            <label>Quote number: </label>
            <input type='text' class='form-control' name='quote_number' id='quote_number'>
          </div>
          <div class='col-md-3'>
            <label>Description: </label>
            <input type='text' class='form-control' name='quoteDescription' id='quoteDescription'>
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
            <label>Quote issued: </label>
            <input type='date' class='form-control' name='quote_date' id='quote_date'>
          </div>
          <div class='col-md-3'>
            <label>Select image to upload:</label>
            <!-- hidden type which is used to redirect to the correct view -->
            <input type='hidden' value='addQuote' id='redirect' name='redirect'>
            <input type='hidden' name='MAX_FILE_SIZE' value='2000000'>
            <input type="file" name="fileToUpload" id="fileToUpload">
          </div>
          <div class='col-md-9'>
            <input type="submit" class='btn btn-primary col-md-8' value="Add quote" name="submit" style='margin-top:25px;'>
          </div>
        </form>
        <div class='col-md-12'>
          <?php
          while($row = mysqli_fetch_array($result)){
            $supplierNameSql = "SELECT supplier_name
                                FROM supplier
                                WHERE supplier_ID = '$row[3]';";
            $supplierNameResult = mysqli_query($link, $supplierNameSql);
            $supplierNameRow = mysqli_fetch_array($supplierNameResult);
            echo"<div class='col-md-2' style='margin-top:30px'>
                  <p><input type='image' src='../Scan/getQuoteImage.php?id=".$row[0]."' width='100' height='100' onerror=\"this.src='../images/noimage.jpg'\" onclick=\"window.open('../Printouts/quotePrintout.php?id=".$row[0]."')\"/></p>
                  <button class='btn btn-danger' style='margin-top:5px; margin-right:200px' onclick='removeQuoteFromRequest(".$row[0].")'>Deactivate</button>
                </div>
                <div class='col-md-2' style='margin-top:30px; margin-left:-35px;'>
                  <p><strong>Quote number: </strong><a href='../SelectPHP/download.php?id=".$row[0]."'>".$row[2]."</a><br></p>
                  <p><strong>Supplier: </strong>".$supplierNameRow[0]."</p>
                  <p><strong>Date issued: </strong>".$row[4]."</p>
                </div>";
          }
           ?>
        </div>
        <div class='col-md-6' style='margin-top:30;'>
          <a href='request.php' class='btn btn-primary col-md-12'>Create request</a>
        </div>
        <div class='col-md-6' style='margin-top:30;'>
          <button class='btn btn-primary col-md-12' onclick='addQuoteToOverview()'>Overview</button>
        </div>
      </div>
    </div>
  </div>
</body>
