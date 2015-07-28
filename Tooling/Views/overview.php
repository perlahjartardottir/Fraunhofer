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
</head>
<body>
<?php include '../header.php'; ?>
  <div class='container'>
    <div class='row well well-lg'>
      <div class='col-md-6'>
        <h2>Search for POs</h2>
        <div class='input-group col-md-8'>
          <span class="btn-group">
            <a href='filterPOS.php' class='btn btn-primary btn-lg' type='submit'>Enter</a>
          </span>
        </div>
      </div>
    </div>
    <div class='row well well-lg'>
      <div class='col-md-6'>
        <h2>Search for runs</h2>
        <div class='input-group col-md-8'>
          <span class="btn-group">
            <a href='filterRuns.php' class='btn btn-primary btn-lg' type='submit'>Enter</a>
          </span>
        </div>
      </div>
    </div>
    <div class='row well well-lg'>
      <div class='col-md-6'>
        <h2>Search for tools</h2>
        <div class='input-group col-md-8'>
          <span class="btn-group">
            <a href='filterTools.php' class='btn btn-primary btn-lg' type='submit'>Enter</a>
          </span>
        </div>
      </div>
    </div>
    <?php
    if($user_sec_lvl >= 4){
      echo"
      <div class='row well well-lg'>
        <div class='col-md-6'>
          <h2>Show old POs</h2>
          <div class='input-group col-md-8'>
            <a href='oldPOs.php' class='btn btn-primary btn-lg' type='submit'>Enter</a>
          </div>
        </div>
      </div>";
    }?>
  </div>
</body>
</html>
<?php
mysql_close($link);
?>
