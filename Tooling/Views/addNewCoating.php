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
    <?
          if($user_sec_lvl >3)
    {
      echo"
        <div class='row well well-lg'>
          <div id='coatingAdded'></div>
          <div id='invalidCoating'></div>
          <form>
          <h4>Add a new coating</h4>
              <div class='col-md-6 form-group'>
                <label>Coating type:</label>
                <input type='text' id='coating_type' class='form-control' required placeholder='Fx. AlTi'/>
              </div>
              <div class='col-md-6 form-group'>
                <label>Coating description:</label>
                <input type='text' id='coating_description' class='form-control' required placeholder='Fx. 60% Aluminum 40% Titanium'/>
              </div>
            <div class='col-md-12'>
              <button type='button' onclick='addCoating(this.form)' class='btn btn-primary col-md-12' style='float:right; margin-top:24px'>Insert</button>
            </div>
          </form>";
      }
    ?>
    </div>
  </div>
</body>
</html>
