<!DOCTYPE html>
<html lang="en">
<head>
  <?php
  include 'connection.php';
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
  ?>
  <title>Login Fraunhofer CCD</title>
  <link href="/css/bootstrap.min.css" rel="stylesheet">
  <!-- Custom styles for this template -->
</head>
<body>
  <?php include 'header.php'; ?>
  <div class="container">
    <div class="btn-group-vertical col-md-12" role="group" aria-label="...">
      <?php
      if($user_sec_lvl[0] > 0){
        echo "<a href='Tooling/Views/selection.php' class='btn btn-primary btn-lg'>Tooling</a>";
      }
      if($user_sec_lvl[1] > 0){
        echo "<a href='Purchasing/Views/purchasing.php' class='btn btn-primary btn-lg'>Purchasing</a>";
      }
      if($user_sec_lvl[2] > 0){
        echo "<a href='DataAnalysis/Views/dataAnalysis.php' class='btn btn-primary btn-lg'>Data Analysis</a>";
      }
      ?>
    </div>
  </div>
  <div class="container">
    <div class='btn-group col-md-4 pull-right' style="position: absolute; bottom: 10px; right:25px">
      <?php
        if($user_sec_lvl >= 3){
          echo "<a href='Views/addNewEmployee.php' class='btn btn-primary'>Add new employee</a>";
        }
      ?>
      <a href='/Views/viewAllEmployees.php' class='btn btn-primary'>View all employees</a>
    </div>
  </div>
</body>
</html>
