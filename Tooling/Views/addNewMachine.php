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
        <div id='invalidMachine'></div>
        <div class='row well well-lg'>
          <form>
            <h4>Add new machine</h4>
            <p class='col-md-6 form-group'>
              <label for="mname">Machine Name: </label>
              <input type="text" name="mname" id="mname" class='form-control'>
            </p>
            <p class='col-md-6 form-group'>
              <label for="macro">Acronym: </label>
              <input type="text" name='macro' id='macro' class='form-control' placeholder="For example: K2">
            </p>
            <input class='form-control btn btn-primary' type="button" value="Add Machine to Database" onclick='addNewMachine()'>
          </form>
        </div>
      </div>
  </body>
  </html>
