<?php
include '../../connection.php';
session_start();
// Find the current user.
$user = $_SESSION["username"];
// Find his level of security.
$secsql = "SELECT security_level, employee_ID
           FROM employee
           WHERE employee_name = '$user'";
$secResult = mysqli_query($link, $secsql);

while($row = mysqli_fetch_array($secResult)){
  $user_sec_lvl = $row[0];
  $employee_ID = $row[1];
}
$user_sec_lvl = str_split($user_sec_lvl);
$user_sec_lvl = $user_sec_lvl[1];
// If the user security level is not high enough we kill the page and give him a link to the log in page.
if($user_sec_lvl < 2){
  echo "<a href='../../Login/login.php'>Login Page</a></br>";
  die("You don't have the privileges to view this site.");
}
?>

<head>
  <title>Fraunhofer CCD</title>
</head>
<body>
  <?php include '../header.php'; ?>
  <?php echo "<input type='hidden' id='employee_ID' value='".$employee_ID."'>"; ?>
  <div class='container'>
    <div id='invalidRequest'></div>
    <div class='row well well-lg'>
    	<h5>Here will be a form to add a new sample</h5>
    </div>
  </div>
</body>