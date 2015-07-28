<!DOCTYPE html>
<?php
include '../connection.php';
session_start();
//find the current user
$user = $_SESSION["username"];
//find his level of security
$secsql = "SELECT security_level
           FROM employee
           WHERE employee_name = '$user'";
$secResult = mysqli_query($link, $secsql);

while($row = mysqli_fetch_array($secResult)){
  $user_sec_lvl = $row[0];
}
?>
  <html>
  <head>
    <title>Fraunhofer CCD</title>
    <link href='../css/bootstrap.min.css' rel='stylesheet'>
    <link href='../css/main.css' rel='stylesheet'>
  </head>
  <body>
    <?php include '../header.php'; ?>
      <div class='container'>
        <div id='invalidCustomer'></div>
        <div class='row well well-lg'>
          <div class='col-md-12'>
            <h4>Add new customer</h4>
            <form id='customerForm' onsubmit="return false">
              <p class='col-md-4 form-group'>
                <label for="cName" required>Company Name: </label>
                <input type="text" name="cName" id="cName" class='form-control'>
              </p>
              <p class='col-md-4 form-group'>
                <label for="cAddress">Company Address: </label>
                <input type="text" name="cAddress" id="cAddress" class='form-control'>
              </p>
              <p class='col-md-4 form-group'>
                <label for="cContact">Contact Name: </label>
                <input type="text" name="cContact" id="cContact" class='form-control'>
              </p>
              <p class='col-md-4 form-group'>
                <label for="cEmail">Company Email: </label>
                <input type="rDate" name='cEmail' id='cEmail' class='form-control'>
              </p>
              <p class='col-md-4 form-group'>
                <label for="cPhone">Company Phone: </label>
                <input type="text" name="cPhone" id="cPhone" class='form-control'>
              </p>
              <p class='col-md-4 form-group'>
                <label for="cFax">Company Fax: </label>
                <input type="text" name='cFax' id='cFax' class='form-control'>
              </p>
              <p class='col-md-12 form-group'>
                <label for="cNotes">Notes </label>
                <textarea form='customerForm' name='cNotes' id='cNotes' cols='35' class='form-control'></textarea>
              </p>
              <input type="submit" value="Add customer to Database" onclick='addCustomer()' class='form-control col-md-3 btn btn-primary'>
            </form>
          </div>
        </div>
      </div>
  </body>
  </html>
