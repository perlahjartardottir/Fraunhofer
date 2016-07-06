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
  $user_sec_lvl = $row[2];
}
$user_sec_lvl = str_split($user_sec_lvl);
$user_sec_lvl = $user_sec_lvl[0];
?>
  <html>
  <head>
    <title>Fraunhofer CCD</title>
    <link href='../css/bootstrap.min.css' rel='stylesheet'>
  </head>
  <body>
    <?php include '../header.php'; ?>
      <div class='container'>
        <div id='invalidMachine'></div>
        <div class='row well well-lg'>
          <form>
            <h3>Add new analysis equipment</h3>
            <p class='col-md-6 form-group'>
              <label for='name'>Name: </label>
              <input type='text' name='name' id='name' class='form-control'>
            </p>
            <p class='col-md-6 form-group'>
              <label for='macro'>Comment: </label>
              <input type='text' name='comment' id='macro' class='form-control'>
            </p>
            <input class='form-control btn btn-primary' type="button" value="Add Analysis Equipment" onclick='addAnalysisEquipment()'>
          </form>
        </div>
      </div>
  </body>
  </html>
