<!-- The header file for almost all views. -->
<!-- This file includes all the .css and .js needed  -->
<!-- It also displays the top of the page and the user who is logged in -->

<?php
include '../connection.php';
session_start();
// find the current user
$user = $_SESSION["username"];

// find his level of security
$secSql = "SELECT security_level
           FROM employee
           WHERE employee_name = '$user'";
$secResult = mysqli_query($link, $secSql);

while($row = mysqli_fetch_array($secResult)){
  $securityLevel = $row[0];
}
$securityLevel = str_split($securityLevel);
$securityLevel = $securityLevel[2];

$_SESSION["securityLevelDA"] = $securityLevel;

?>

<meta charset="utf-8">
<meta name="google" content="notranslate">
<meta http-equiv="Content-Language" content="en">
<link href='../css/header.css' rel='stylesheet'>
<link href='../css/main.css' rel='stylesheet'>
<link href='/css/bootstrap.min.css' rel='stylesheet'>
<link href='/css/jquery-ui.min.css' rel='stylesheet'>
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<!-- For formating input date -->
<script src='https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.3/moment.min.js'></script>
<script src='../js/app.js'></script>
<script src='../js/analysis.js'></script>
<script src='../js/bootstrap.js'></script>
<div class="navbar navbar-default navbar-static-top">
  <div class="container">
    <div class="navbar-header">
      <div class='navbar-brand'>
        <a href='../../Views/menu.php'>Menu</a>
        <a href='../views/dataAnalysis.php' style="margin-left:7px;">Home</a>
        <span class='username'><strong><?php echo $_SESSION["username"];?></strong></span>
        <a href='../../Views/editProfile.php'>Edit profile</a>
      </div>
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navHeaderCollapse">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
          </button>
    </div>
    <div class="collapse navbar-collapse navHeaderCollapse">
        <ul class='navbar-right btn-group' role='group'>
          <a href='../../Tooling/Views/feedback.php' class='btn btn-primary headerbutton active' role='button'>Comment</a>
          <a href='https://github.com/Freyr12/Fraunhofer/tree/master/DataAnalysis' target='_blank' class='btn btn-primary headerbutton active' role='button'>?</a>
          <a onclick='logout()' class='btn btn-danger headerbutton active' role='button'>Logout</a>
        </ul>
    </div>
  </div>
</div>
