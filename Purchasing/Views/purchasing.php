<!-- In this view we only display some parts if the security level is high enough -->
<!-- This is the front page -->
<!DOCTYPE html>
<html>
<head>
  <?php
  include 'connection.php';
  session_start();
  // find the current user
  $user = $_SESSION["username"];

  // find his level of security
  $secsql = "SELECT security_level
             FROM employee
             WHERE employee_name = '$user'";
  $secResult = mysqli_query($link, $secsql);

  while($row = mysqli_fetch_array($secResult)){
    $user_sec_lvl = $row[0];
  }
  ?>
  <title>Fraunhofer CCD</title>
  <link href='../css/bootstrap.min.css' rel='stylesheet'>
</head>
<body>
  <?php include '../header.php'; ?>
  <div class="container">
    <h1> Here we will have the purchasing view </h1>
  </div>
</body>
</html>
