<!DOCTYPE html>
<?php
include '../connection.php';
session_start();

$securityLevel = $_SESSION["securityLevelDA"];

// if the user security level is not high enough we kill the page and give him a link to the log in page
if($securityLevel < 4){
  echo "<a href='../../Login/login.php'>Login Page</a></br>";
  die("You don't have the privileges to view this site.");
} 

?>
  <html>
  <head>
    <title>Data Analysis</title>
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
