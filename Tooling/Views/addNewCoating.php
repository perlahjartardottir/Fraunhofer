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
    <div id="invalidCoating"></div>
    <div class='row well well-lg'>
      <form>
        <h4>Add new coating</h4>
        <p class='col-md-6 form-group'>
          <label for="coatingType">Coating type</label>
          <input type="text" name="coatingType" id="coatingType" class='form-control' placeholder="Fx. AlTin">
        </p>
        <p class='col-md-6 form-group'>
          <label for="coatingDesc">Coating Description</label>
          <input type="text" name="coatingDesc" id="coatingDesc" class='form-control' placeholder="Fx. 60% Aluminum 40% Titanium">
        </p>
        <input class='form-control btn btn-primary'type="button" value="Add coating to database" onclick='addCoating()'>
      </form>
    </div>
  </div>
</body>
</html>
