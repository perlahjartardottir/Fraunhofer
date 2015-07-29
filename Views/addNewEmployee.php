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
    <div id='invalidEmployee'></div>
    <div class='row well well-lg'>
        <h4>Add new employee</h4>
        <form onsubmit='return false'>
          <p class='col-md-4 form-group'>
            <label for="eName">Employe name: </label>
            <input type="text" name="eName" id="eName" class='form-control'>
          </p>
          <p class='col-md-4 form-group'>
            <label for="sec_lvl">Security level 1-4: </label>
            <input type="text" name="sec_lvl" id="sec_lvl" class='form-control'>
          </p>
          <p class='col-md-4 form-group'>
            <label for="ePhoneNumber">Phone Number: </label>
            <input type="text" name="ePhoneNumber" id="ePhoneNumber" class='form-control'>
          </p>
          <p class='col-md-4 form-group'>
            <label for="eEmail">Employee Email: </label>
            <input type="rDate" name='eEmail' id='eEmail' class='form-control'>
          </p>
          <p class='col-md-4 form-group'>
            <label for="ePass">Password: </label>
            <input type="password" name="ePass" id="ePass" class='form-control'>
          </p>
          <p class='col-md-4 form-group'>
            <label for="ePassAgain">Confirm Password:</label>
            <input type="password" name="ePassAgain" id="ePassAgain" onkeyup="checkPass(); return false;" class='form-control'>
            <span id="confirmMessage" class="confirmMessage"></span>
          </p>
          <input type="submit" value="Add Employee to Database" onclick='addEmployee()' class='form-control btn btn-primary'>
        </form>
    </div>
  </div>
</body>
</html>
