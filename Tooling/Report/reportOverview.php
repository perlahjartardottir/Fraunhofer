<!DOCTYPE html>
<html>
<head>
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
if($user_sec_lvl < 4){
  echo "<a href='../Login/login.php'>Login Page</a></br>";
  die("You don't have the privileges to view this site.");
}
?>
  <title>Fraunhofer CCD</title>
  <link href='../css/bootstrap.min.css' rel='stylesheet'>
  
  
</head>
<body>
<?php include '../header.php'; ?>
  <div class='container'>
    <h1>Tool coating overview</h1>
    <div class='row well well-lg'>
      <?php 
      if($user_sec_lvl >= 4){
        echo 
        "<div class='col-md-12'>
          <div class='col-md-4'>
            <a href='customerOverview.php' class='btn btn-primary'>Customer overview</a>
            <p></p>
          </div>
          <div class='col-md-4'>
            <a href='machineOverview.php' class='btn btn-primary'>Machine overview</a>
            <p></p>
          </div>
          <div class='col-md-4'>
            <a href='coatingOverview.php' class='btn btn-primary'>Coating overview</a>
            <p></p>
          </div>
        </div>";
      }
      ?>
    </div>
    <h1>Data graphs</h1>
    <div class='row well well-lg'>
      <?php 
      if($user_sec_lvl >= 4){
        echo 
        "<div class='col-md-12'>
          <div class='col-md-6'>
            <a href='databaseStatus.php' class='btn btn-primary'>Database status</a>
            <p></p>
          </div>
          <div class='col-md-6'>
            <a href='geoData.php' class='btn btn-primary'>Where are our orders coming from</a>
            <p></p>
          </div>
          <div class='col-md-6'>
            <a href='overall.php' class='btn btn-primary'>Overall for all customers</a>
            <p></p>
          </div>
          <div class='col-md-6'>
            <a href='allCharts.php' class='btn btn-primary'>Line chart for each customer</a>
            <p></p>
          </div>
        </div>";
      }
      ?>
    </div>
  </div>
</body>
</html>
