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
// if the user security level is not high enough we kill the page and give him a link to the log in page
if($user_sec_lvl < 2){
  echo "<a href='../../Login/login.php'>Login Page</a></br>";
  die("You don't have the privileges to view this site.");
}
?>
<html>
<head>
  <link href='../css/bootstrap.min.css' rel='stylesheet'>
  <link href='../css/main.css' rel='stylesheet'>



  <title>Fraunhofer CCD</title>
</head>
<body>
  <?php include '../header.php'; ?>
  <div class='container'>
    <div class='row well well-lg'>
      <div class='col-md-6'>
        <h2>Add a new PO</h2>
        <div class='btn-group'>
          <a href='addNewPO.php' class='btn btn-primary btn-lg' >
            Add new PO!
          </a>
        </div>
      </div>
    </div>
    <div class='row well well-lg'>
      <div class='col-md-6'>
        <h2>Add tools to existing PO</h2>
        <div class='input-group col-md-8'>
          <span class="btn-group">
            <a href='addTools2.php' class='btn btn-primary btn-lg' type='submit'>Enter</a>
          </span>
        </div>
      </div>
    </div>
    <div class='row well well-lg'>
      <div class='col-md-6'>
        <h2>Generate a track sheet for your PO</h2>
        <p class='lead'></p>
        <div class='input-group col-md-8'>
          <span class="btn-group">
            <a href='generateTrackSheet.php' class='btn btn-primary btn-lg' type='submit'>Enter</a>
          </span>
        </div>
      </div>
    </div>
     <div class='row well well-lg'>
      <div class='col-md-6'>
        <h2>Generate a packing list for your PO</h2>
        <p class='lead'></p>
        <div class='input-group col-md-8'>
          <span class="btn-group">
            <a href='../printouts/packinglist.php' class='btn btn-primary btn-lg' type='submit'>Enter</a>
          </span>
        </div>
      </div>
    </div>
</div>
</body>
</html>
