<?php
include '../../connection.php';
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
// if the user security level is not high enough we kill the page and give him a link to the log in page
if($user_sec_lvl < 2){
  echo "<a href='../../Login/login.php'>Login Page</a></br>";
  die("You don't have the privileges to view this site.");
}
?>
<head>
  <title>Fraunhofer CCD</title>
</head>
<body>
  <?php include '../header.php'; ?>
  <div class='container'>
    <div id='invalidSupplier'></div>
    <div class='row well well-lg'>
      <form>
        <h3>Add a new supplier</h3>
        <div class='form-group col-md-4'>
          <label>Name:</label>
          <input type='text' class='form-control' id='supplier_name'>
        </div>
        <div class='form-group col-md-4'>
          <label>Address:</label>
          <input type='text' class='form-control' id='supplier_address'>
        </div>
        <div class='form-group col-md-4'>
          <label>Contact:</label>
          <input type='text' class='form-control' id='supplier_contact'>
        </div>
        <div class='form-group col-md-4'>
          <label>Phone:</label>
          <input type='text' class='form-control' id='supplier_phone'>
        </div>
        <div class='form-group col-md-4'>
          <label>Fax:</label>
          <input type='text' class='form-control' id='supplier_fax'>
        </div>
        <div class='form-group col-md-4'>
          <label>Email:</label>
          <input type='text' class='form-control' id='supplier_email'>
        </div>
        <div class='form-group col-md-4'>
          <label>Website:</label>
          <input type='text' class='form-control' id='supplier_website'>
        </div>
        <div class='form-group col-md-4'>
          <label>Login:</label>
          <input type='text' class='form-control' id='supplier_login'>
        </div>
        <div class='form-group col-md-4'>
          <label>Password:</label>
          <input type='text' class='form-control' id='supplier_password'>
        </div>
        <div class='form-group col-md-4'>
          <label>Account Nr:</label>
          <input type='text' class='form-control' id='supplier_accountNr'>
        </div>
        <div class='form-group col-md-4'>
          <label>Notes:</label>
          <textarea class='form-control' id='supplier_notes'></textarea>
        </div>
        <button type='button' class='btn btn-primary form-control' onclick='addNewSupplier()'>Add</button>
      </form>
    </div>
  </div>
</body>
