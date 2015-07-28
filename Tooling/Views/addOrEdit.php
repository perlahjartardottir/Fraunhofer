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
if($user_sec_lvl < 2){
  echo "<a href='../Login/login.php'>Login Page</a></br>";
  die("You don't have the privileges to view this site.");
}
?>
<html>
<head>
  <link href='../css/bootstrap.min.css' rel='stylesheet'>
  <link href='../css/main.css' rel='stylesheet'>
</head>
<title>Fraunhofer CCD</title>
<body>
  <?php include '../header.php'; ?>
  <div class='container'>
    <div class='row well well-lg'>
      <div class='col-md-12'>
        <h2>Customer info</h2>
        <p class='lead'>Customer menu</p>
        <div class='btn-group'>
          <?php
            if($user_sec_lvl >= 3){
              echo "<a href='addNewCustomer.php' class='btn btn-primary btn-lg'>Add new customer</a>";
            }
          ?>
        <div class='btn-group'>
          <a href='viewAllCustomers.php' class='btn btn-success btn-lg'>View all customers</a>
        </div>
      </div>
    </div>
  </div>
<div class='row well well-lg'>
  <div class='col-md-12'>
    <h2>Employee info</h2>
    <p class='lead'>Employee menu</p>
    <div class='btn-group'>
          <?php
            if($user_sec_lvl >= 3){
              echo "<a href='addNewEmployee.php' class='btn btn-primary btn-lg'>Add new employee</a>";
            }
          ?>
            <a href='viewAllEmployees.php' class='btn btn-success btn-lg'>View all employees</a>
    </div>
  </div>
</div>
  <div class='row well well-lg'>
    <div class='col-md-12'>
      <h2>Machine info</h2>
      <p class='lead'>Machine menu</p>
      <div class='btn-group'>
          <?php
            if($user_sec_lvl >= 3){
              echo "<a href='addNewMachine.php' class='btn btn-primary btn-lg' >Add new machine</a>";
            }
          ?>
        <a href='viewAllMachines.php' class='btn btn-success btn-lg'>View all machines</a>
      </div>
    </div>
  </div>
    <div class='row well well-lg'>
      <div class='col-md-12'>
        <h2>Coating info</h2>
        <p class='lead'>Coating menu</p>
        <div class='btn-group'>
          <?php
            if($user_sec_lvl >= 3)
            {
              echo "<a href='addNewCoating.php' class='btn btn-primary btn-lg' >Add new coating</a>";
            }
          ?>
            <a href='viewAllCoatings.php' class='btn btn-success btn-lg'>View all coatings</a>
        </div>
      </div>
    </div>
      <div class='row well well-lg'>
        <div class='col-md-12'>
          <h2>Price tables</h2>
          <p class='lead'>Price tables</p>
          <div class='btn-group'>
            <a href='priceTables.php' class='btn btn-primary btn-lg'>View all price tables</a>
          </div>
        </div>
      </div>
    </div>
  </body>
  </html>
